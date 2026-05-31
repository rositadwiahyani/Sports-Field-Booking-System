<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Buat pivot table
        Schema::create('pemesanan_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('pemesanan')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Pindahkan data dari pemesanan ke pivot table
        $pemesanans = DB::table('pemesanan')->whereNotNull('jadwal_id')->get();
        foreach ($pemesanans as $p) {
            DB::table('pemesanan_jadwal')->insert([
                'pemesanan_id' => $p->id,
                'jadwal_id' => $p->jadwal_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Hapus kolom jadwal_id dari pemesanan
        Schema::table('pemesanan', function (Blueprint $table) {
            // Karena SQLite sering bermasalah dengan drop foreign, kita pakai cara aman
            // Tapi karena default Laravel 11 support dropForeign, kita coba:
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom jadwal_id ke pemesanan
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->onDelete('cascade');
        });

        // Kembalikan data dari pivot
        $pivot = DB::table('pemesanan_jadwal')->get();
        foreach ($pivot as $p) {
            DB::table('pemesanan')->where('id', $p->pemesanan_id)->update([
                'jadwal_id' => $p->jadwal_id
            ]);
        }

        // Hapus pivot table
        Schema::dropIfExists('pemesanan_jadwal');
    }
};
