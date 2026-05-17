<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;

    protected $table = 'jadwal';

    protected $fillable = [
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}