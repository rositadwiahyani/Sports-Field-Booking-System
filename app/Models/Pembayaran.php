<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'metode_bayar',
        'nominal_bayar',
        'bukti_bayar',
        'status',
        'waktu_bayar'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}