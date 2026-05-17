<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 🔥 RELASI
use App\Models\Pemesanan;
use App\Models\Review;
use App\Models\Notifikasi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 🔥 ditambah
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔥 ROLE CHECK
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    // 🔥 RELASI
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}