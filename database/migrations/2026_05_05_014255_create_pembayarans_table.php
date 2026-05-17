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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            // 🔥 RELASI KE PEMESANAN
            $table->foreignId('pemesanan_id')
                  ->constrained('pemesanan')
                  ->onDelete('cascade');

            $table->string('metode_bayar'); // transfer, e-wallet, dll
            $table->decimal('nominal_bayar', 10, 2);
            $table->string('bukti_bayar')->nullable(); // upload bukti
            $table->string('status'); // pending, lunas, gagal
            $table->timestamp('waktu_bayar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};