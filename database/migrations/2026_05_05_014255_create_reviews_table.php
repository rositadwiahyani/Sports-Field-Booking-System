<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('lapangan_id')
                  ->constrained('lapangan')
                  ->onDelete('cascade');

            // 🔥 TAMBAH INI untuk anti-duplikat per pemesanan
            $table->foreignId('pemesanan_id')
                  ->unique() // 1 pemesanan hanya boleh 1 review
                  ->constrained('pemesanan')
                  ->onDelete('cascade');

            $table->integer('rating'); // 1 - 5
            $table->text('komentar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};