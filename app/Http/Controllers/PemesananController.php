<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('jadwals.lapangan', 'user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pemesanan.index', compact('pemesanan'));
    }

    public function create()
    {
        $jadwal = Jadwal::where('status', 'tersedia')->with('lapangan')->get();
        return view('pemesanan.create', compact('jadwal'));
    }

    // 🔥 TAMBAHAN: booking langsung dari lapangan
    public function bookFromLapangan(Request $request, $lapangan_id)
    {
        $lapangan = Lapangan::with('jadwal')->findOrFail($lapangan_id);

        $jadwal = Jadwal::with('lapangan')
            ->where('lapangan_id', $lapangan_id)
            ->where('status', 'tersedia')
            ->get();

        return view('pemesanan.book', compact('lapangan', 'jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|array|min:1',
            'jadwal_id.*' => 'exists:jadwal,id',
        ]);

        $jadwals = Jadwal::with('lapangan')->whereIn('id', $request->jadwal_id)->get();

        // Validasi semua jadwal tersedia dan di tanggal yang sama
        $firstTanggal = null;
        $totalHarga = 0;
        
        foreach ($jadwals as $jadwal) {
            if ($jadwal->status !== 'tersedia') {
                return back()->with('error', 'Salah satu jadwal yang dipilih sudah tidak tersedia.');
            }
            if ($firstTanggal === null) {
                $firstTanggal = $jadwal->tanggal;
            } elseif ($jadwal->tanggal !== $firstTanggal) {
                return back()->with('error', 'Anda hanya bisa memilih jadwal pada hari yang sama.');
            }

            $durasi = (strtotime($jadwal->jam_selesai) - strtotime($jadwal->jam_mulai)) / 3600;
            $totalHarga += $jadwal->lapangan->harga_per_jam * $durasi;
        }

        // Buat Pemesanan Induk
        $pemesanan = Pemesanan::create([
            'user_id'          => Auth::id() ?? 1,
            'total_harga'      => $totalHarga,
            'status_pemesanan' => 'menunggu',
            'batas_bayar'      => now()->addHours(2),
        ]);

        // Attach jadwal ke pivot dan update status
        foreach ($jadwals as $jadwal) {
            $pemesanan->jadwals()->attach($jadwal->id);
            $jadwal->status = 'terpesan';
            $jadwal->save();
        }

        $lapanganNames = $jadwals->pluck('lapangan.nama_lapangan')->unique()->implode(', ');
        $jamMulai = \Carbon\Carbon::parse($jadwals->min('jam_mulai'))->format('H:i');
        $jamSelesai = \Carbon\Carbon::parse($jadwals->max('jam_selesai'))->format('H:i');

        // 🔔 Notifikasi booking_berhasil
        Notifikasi::create([
            'user_id'      => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'judul'        => 'Booking Berhasil!',
            'pesan'        => 'Booking lapangan ' . $lapanganNames .
                              ' pada ' . $firstTanggal . ' jam ' . $jamMulai . '-' . $jamSelesai . 
                              ' (' . $jadwals->count() . ' Jam) berhasil. Segera bayar sebelum ' .
                              $pemesanan->batas_bayar . '.',
            'tipe'         => 'booking_berhasil',
        ]);

        return redirect('/pemesanan')->with('success', 'Booking berhasil!');
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('jadwals.lapangan')->findOrFail($id);
        return view('pemesanan.show', compact('pemesanan'));
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::with('jadwals.lapangan')->findOrFail($id);
        $userId = $pemesanan->user_id;

        $lapanganNames = $pemesanan->jadwals->pluck('lapangan.nama_lapangan')->unique()->implode(', ');
        $tanggal = $pemesanan->jadwals->first() ? $pemesanan->jadwals->first()->tanggal : 'Unknown';

        // Kembalikan semua jadwal menjadi tersedia
        foreach ($pemesanan->jadwals as $jadwal) {
            $jadwal->status = 'tersedia';
            $jadwal->save();
        }

        $pemesanan->jadwals()->detach();
        $pemesanan->delete();

        // 🔔 Notifikasi booking_dibatalkan
        Notifikasi::create([
            'user_id'      => $userId,
            'pemesanan_id' => null,
            'judul'        => 'Booking Dibatalkan',
            'pesan'        => 'Booking lapangan ' . $lapanganNames .
                              ' pada ' . $tanggal . ' telah dibatalkan.',
            'tipe'         => 'booking_dibatalkan',
        ]);

        return redirect('/pemesanan')->with('success', 'Pemesanan dibatalkan');
    }
}