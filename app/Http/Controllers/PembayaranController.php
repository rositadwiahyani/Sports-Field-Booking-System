<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        return redirect('/pemesanan');
    }

    public function create($pemesanan_id)
    {
        $pemesanan = Pemesanan::with('jadwal.lapangan')->findOrFail($pemesanan_id);
        return view('pembayaran.create', compact('pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'metode_bayar' => 'required|in:transfer,tunai,qris',
            'bukti_bayar'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pemesanan = Pemesanan::with('jadwal.lapangan')->findOrFail($request->pemesanan_id);

        if ($pemesanan->status_pemesanan == 'dibayar') {
            return back()->with('error', 'Pemesanan sudah dibayar.');
        }

        // 🔥 Upload bukti (simplify, karena sudah wajib)
        $buktiPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        // 🔥 Simpan sebagai pending (bukan langsung lunas)
        Pembayaran::create([
            'pemesanan_id'  => $pemesanan->id,
            'metode_bayar'  => $request->metode_bayar,
            'nominal_bayar' => $pemesanan->total_harga,
            'bukti_bayar'   => $buktiPath,
            'status'        => 'pending',
            'waktu_bayar'   => now(),
        ]);

        // 🔥 Update status pemesanan → menunggu konfirmasi admin
        $pemesanan->status_pemesanan = 'menunggu_konfirmasi';
        $pemesanan->save();

        // 🔔 Notifikasi ke user
        Notifikasi::create([
            'user_id'      => $pemesanan->user_id,
            'pemesanan_id' => $pemesanan->id,
            'judul'        => 'Bukti Pembayaran Dikirim!',
            'pesan'        => 'Bukti pembayaran lapangan ' .
                              $pemesanan->jadwal->lapangan->nama_lapangan .
                              ' sudah dikirim. Menunggu konfirmasi admin.',
            'tipe'         => 'pembayaran_diterima',
        ]);

        return redirect('/pemesanan')->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.');
    }
}