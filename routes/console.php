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
    $pemesanans = Pemesanan::with('jadwal.lapangan')
        ->where('status_pemesanan', 'menunggu')
        ->where('batas_bayar', '<', now())
        ->get();

    foreach ($pemesanans as $pemesanan) {
        $jadwal = $pemesanan->jadwal;
        $namaLapangan = $jadwal->lapangan->nama_lapangan;
        $tanggal      = $jadwal->tanggal;
        $userId       = $pemesanan->user_id;

        // Kembalikan slot jadwal → tersedia
        $jadwal->status = 'tersedia';
        $jadwal->save();

        // Update status pemesanan → expired
        $pemesanan->status_pemesanan = 'expired';
        $pemesanan->save();

        // 🔔 Notifikasi booking_dibatalkan
        Notifikasi::create([
            'user_id'      => $userId,
            'pemesanan_id' => null,
            'judul'        => 'Booking Expired!',
            'pesan'        => 'Booking lapangan ' . $namaLapangan .
                              ' pada ' . $tanggal .
                              ' otomatis dibatalkan karena melewati batas waktu pembayaran.',
            'tipe'         => 'booking_dibatalkan',
        ]);
    }
})->everyMinute()->name('auto-expired-booking');