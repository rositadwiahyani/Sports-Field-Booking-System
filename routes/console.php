<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Notifikasi;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ⏰ Auto Expired Booking
Schedule::call(function () {
    $pemesanans = Pemesanan::with('jadwals.lapangan')
        ->where('status_pemesanan', 'menunggu')
        ->where('batas_bayar', '<', now())
        ->get();

    foreach ($pemesanans as $pemesanan) {
        $lapanganNames = $pemesanan->jadwals->pluck('lapangan.nama_lapangan')->unique()->implode(', ');
        $tanggal = $pemesanan->jadwals->first() ? $pemesanan->jadwals->first()->tanggal : 'Unknown';
        $userId       = $pemesanan->user_id;

        // Kembalikan slot jadwal → tersedia
        foreach ($pemesanan->jadwals as $jadwal) {
            $jadwal->status = 'tersedia';
            $jadwal->save();
        }

        // Update status pemesanan → expired
        $pemesanan->status_pemesanan = 'expired';
        $pemesanan->save();

        // 🔔 Notifikasi booking_dibatalkan
        Notifikasi::create([
            'user_id'      => $userId,
            'pemesanan_id' => null,
            'judul'        => 'Booking Expired!',
            'pesan'        => 'Booking lapangan ' . $lapanganNames .
                              ' pada ' . $tanggal .
                              ' otomatis dibatalkan karena melewati batas waktu pembayaran.',
            'tipe'         => 'booking_dibatalkan',
        ]);
    }
})->everyMinute()->name('auto-expired-booking');