<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Notifikasi;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPemesanan = Pemesanan::count();
        $totalLapangan  = Lapangan::count();
        $totalUser      = User::where('role', 'pelanggan')->count();

        $pemesananTerbaru = Pemesanan::with('user', 'jadwal.lapangan')
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

    // 🔥 HALAMAN LIST PEMBAYARAN PENDING
    public function konfirmasiIndex()
    {
        $pembayaran = Pembayaran::with('pemesanan.user', 'pemesanan.jadwal.lapangan')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.konfirmasi', compact('pembayaran'));
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