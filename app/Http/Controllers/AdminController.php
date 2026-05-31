<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPemesanan = Pemesanan::count();
        $totalLapangan  = Lapangan::count();
        $totalUser      = User::where('role', 'pelanggan')->count();

        $pemesananTerbaru = Pemesanan::with('user', 'jadwals.lapangan')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPemesanan',
            'totalLapangan',
            'totalUser',
            'pemesananTerbaru'
        ));
    }

    //  HALAMAN LIST PEMBAYARAN — SEARCH, FILTER, STATS
    public function konfirmasiIndex(Request $request)
    {
        $search       = $request->input('search');
        $filterStatus = $request->input('status', 'pending'); // default: pending
        $filterMetode = $request->input('metode');

        $query = Pembayaran::with('pemesanan.user', 'pemesanan.jadwal.lapangan');

        // Filter status
        if ($filterStatus && $filterStatus !== 'semua') {
            $query->where('status', $filterStatus);
        }

        // Filter metode bayar
        if ($filterMetode && $filterMetode !== 'semua') {
            $query->where('metode_bayar', $filterMetode);
        }

        // Search by user name or lapangan name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pemesanan.user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('pemesanan.jadwal.lapangan', function ($l) use ($search) {
                    $l->where('nama_lapangan', 'like', "%{$search}%");
                });
            });
        }

        $pembayaran = $query->latest()->get();

        // Stats
        $totalPending = Pembayaran::where('status', 'pending')->count();
        $totalLunas   = Pembayaran::where('status', 'lunas')->count();
        $totalGagal   = Pembayaran::where('status', 'gagal')->count();

        return view('admin.konfirmasi', compact(
            'pembayaran',
            'totalPending',
            'totalLunas',
            'totalGagal',
            'search',
            'filterStatus',
            'filterMetode'
        ));
    }

    // ✅ APPROVE PEMBAYARAN
    public function approve($id)
    {
        $pembayaran = Pembayaran::with('pemesanan.jadwal.lapangan')->findOrFail($id);
        $pemesanan  = $pembayaran->pemesanan;

        $pembayaran->status = 'lunas';
        $pembayaran->save();

        $pemesanan->status_pemesanan = 'dibayar';
        $pemesanan->save();

        Notifikasi::create([
            'user_id'      => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'judul'        => 'Pembayaran Dikonfirmasi!',
            'pesan'        => 'Pembayaran lapangan ' .
                              $pemesanan->jadwal->lapangan->nama_lapangan .
                              ' pada ' . $pemesanan->jadwal->tanggal .
                              ' telah dikonfirmasi. Selamat bermain!',
            'tipe'         => 'pembayaran_diterima',
        ]);

        return redirect('/admin/konfirmasi')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    // ❌ REJECT PEMBAYARAN
    public function reject($id)
    {
        $pembayaran = Pembayaran::with('pemesanan.jadwal')->findOrFail($id);
        $pemesanan  = $pembayaran->pemesanan;

        $pembayaran->status = 'gagal';
        $pembayaran->save();

        // 🔥 Kembalikan status biar user bisa bayar ulang
        $pemesanan->status_pemesanan = 'menunggu';
        $pemesanan->save();

        Notifikasi::create([
            'user_id'      => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'judul'        => 'Pembayaran Ditolak',
            'pesan'        => 'Bukti pembayaran kamu ditolak oleh admin. Silakan upload ulang bukti yang valid.',
            'tipe'         => 'booking_dibatalkan',
        ]);

        return redirect('/admin/konfirmasi')->with('error', 'Pembayaran ditolak.');
    }
}


