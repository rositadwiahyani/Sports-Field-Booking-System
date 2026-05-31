<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Services\JadwalService;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $lapanganList = Lapangan::withCount('jadwal')->get();
        $selectedLapangan = $request->input('lapangan_id');

        $query = Jadwal::with('lapangan');

        if ($selectedLapangan) {
            $query->where('lapangan_id', $selectedLapangan);
        }

        $jadwal = $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai')->get();

        return view('jadwal.index', compact('jadwal', 'lapanganList', 'selectedLapangan'));
    }

    public function create()
    {
        $lapangan = Lapangan::all();
        return view('jadwal.create', compact('lapangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required', // Bisa 'semua' atau ID integer
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $lapanganIds = [];
        if ($request->lapangan_id == 'semua') {
            $lapanganIds = Lapangan::pluck('id')->toArray();
        } else {
            // Validasi ID spesifik
            $request->validate(['lapangan_id' => 'exists:lapangan,id']);
            $lapanganIds = [$request->lapangan_id];
        }

        if (empty($lapanganIds)) {
            return back()->with('error', 'Tidak ada lapangan yang tersedia untuk di-generate.');
        }

        // Jalankan service generator
        $added = JadwalService::generateJadwal(
            $lapanganIds, 
            $request->tanggal_mulai, 
            $request->tanggal_selesai
        );

        return redirect('/jadwal')->with('success', "Proses selesai! $added slot jadwal baru berhasil di-generate.");
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $lapangan = Lapangan::all();

        return view('jadwal.edit', compact('jadwal', 'lapangan'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'required|in:tersedia,terpesan,libur'
        ]);

        $jadwal->update($validated);

        return redirect('/jadwal')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete(); // soft delete

        return redirect('/jadwal')->with('success', 'Jadwal dihapus');
    }
}