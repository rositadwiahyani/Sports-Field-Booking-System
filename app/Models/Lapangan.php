<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lapangan extends Model
{
    use SoftDeletes;

    protected $table = 'lapangan';

    protected $fillable = [
        'nomor_lapangan',
        'nama_lapangan',
        'tipe_lapangan',
        'harga_per_jam',
        'status',
        'deskripsi',
        'fasilitas',
        'foto',
        'kapasitas'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}