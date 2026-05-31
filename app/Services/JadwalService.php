<?php

namespace App\Services;

use App\Models\Jadwal;
use Carbon\Carbon;

class JadwalService
{
    /**
     * Generate jadwal untuk lapangan pada rentang tanggal tertentu.
     * Secara otomatis membuat slot per 1 jam dari 10:00 hingga 22:00.
     *
     * @param array $lapanganIds ID lapangan yang akan di-generate (contoh: [1, 2])
     * @param string $startDate Format YYYY-MM-DD
     * @param string $endDate Format YYYY-MM-DD
     * @return int Jumlah jadwal baru yang berhasil ditambahkan
     */
    public static function generateJadwal(array $lapanganIds, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $newJadwalCount = 0;

        // Loop untuk setiap hari
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $tanggalString = $date->format('Y-m-d');

            foreach ($lapanganIds as $lapanganId) {
                // Loop dari jam 10:00 sampai 21:00 (untuk slot 10-11, 11-12... 21-22)
                for ($hour = 10; $hour < 22; $hour++) {
                    $jamMulai = sprintf('%02d:00:00', $hour);
                    $jamSelesai = sprintf('%02d:00:00', $hour + 1);

                    // Cek apakah jadwal sudah ada untuk menghindari duplikat
                    $exists = Jadwal::where('lapangan_id', $lapanganId)
                        ->where('tanggal', $tanggalString)
                        ->where('jam_mulai', $jamMulai)
                        ->exists();

                    if (!$exists) {
                        Jadwal::create([
                            'lapangan_id' => $lapanganId,
                            'tanggal'     => $tanggalString,
                            'jam_mulai'   => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'status'      => 'tersedia',
                        ]);
                        $newJadwalCount++;
                    }
                }
            }
        }

        return $newJadwalCount;
    }
}
