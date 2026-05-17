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
        $pemesanan = Pemesanan::with('jadwal.lapangan', 'user')
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
            'jadwal_id' => 'required|exists:jadwal,id',
        ]);

        $jadwal = Jadwal::with('lapangan')->findOrFail($request->jadwal_id);

        if ($jadwal->status !== 'tersedia') {
            return back()->with('error', 'Jadwal sudah tidak tersedia');
        }

        $durasi = (strtotime($jadwal->jam_selesai) - strtotime($jadwal->jam_mulai)) / 3600;
        $harga  = $jadwal->lapangan->harga_per_jam * $durasi;

        $pemesanan = Pemesanan::create([
            'user_id'          => Auth::id() ?? 1,
            'jadwal_id'        => $jadwal->id,
            'total_harga'      => $harga,
            'status_pemesanan' => 'menunggu',
            'batas_bayar'      => now()->addHours(2),
        ]);

        $jadwal->status = 'terpesan';
        $jadwal->save();

        // 🔔 Notifikasi booking_berhasil
        Notifikasi::create([
            'user_id'      => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'judul'        => 'Booking Berhasil!',
            'pesan'        => 'Booking lapangan ' . $jadwal->lapangan->nama_lapangan .
                              ' pada ' . $jadwal->tanggal .
                              ' berhasil. Segera bayar sebelum ' .
                              $pemesanan->batas_bayar . '.',
            'tipe'         => 'booking_berhasil',
        ]);

        return redirect('/pemesanan')->with('success', 'Booking berhasil!');
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with('jadwal.lapangan')->findOrFail($id);
        return view('pemesanan.show', compact('pemesanan'));
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::with('jadwal.lapangan')->findOrFail($id);

        $jadwal = $pemesanan->jadwal;
        $namaLapangan = $jadwal->lapangan->nama_lapangan;
        $tanggal      = $jadwal->tanggal;
        $userId       = $pemesanan->user_id;

        $jadwal->status = 'tersedia';
        $jadwal->save();

        $pemesanan->delete();

        // 🔔 Notifikasi booking_dibatalkan
        Notifikasi::create([
            'user_id'      => $userId,
            'pemesanan_id' => null,
            'judul'        => 'Booking Dibatalkan',
            'pesan'        => 'Booking lapangan ' . $namaLapangan .
                              ' pada ' . $tanggal . ' telah dibatalkan.',
            'tipe'         => 'booking_dibatalkan',
        ]);

        return redirect('/pemesanan')->with('success', 'Pemesanan dibatalkan');
    }
}