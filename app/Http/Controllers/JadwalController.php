<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Lapangan;

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
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'required|in:tersedia,terpesan,libur'
        ]);

        Jadwal::create($validated);

        return redirect('/jadwal')->with('success', 'Jadwal berhasil ditambahkan');
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
