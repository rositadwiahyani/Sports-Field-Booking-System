<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('pemesanan_id')
                  ->nullable()
                  ->constrained('pemesanan')
                  ->onDelete('cascade');

            $table->string('judul');
            $table->text('pesan');

            $table->enum('tipe', [
                'booking_berhasil',
                'pembayaran_diterima',
                'booking_dibatalkan',
                'pengingat_bayar'
            ]);

            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};