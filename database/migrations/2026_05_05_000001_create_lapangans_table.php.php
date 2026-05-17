<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_lapangan');
            $table->string('nama_lapangan');
            $table->string('tipe_lapangan');
            $table->decimal('harga_per_jam', 10, 2);
            $table->string('status'); // tersedia / maintenance
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('foto')->nullable();
            $table->integer('kapasitas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};