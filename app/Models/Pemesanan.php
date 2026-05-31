<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'total_harga',
        'status_pemesanan',
        'batas_bayar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->belongsToMany(Jadwal::class, 'pemesanan_jadwal');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // 🔥 Tambah relasi review
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}