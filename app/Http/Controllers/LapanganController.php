<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    // ================= INDEX + SEARCH =================
    public function index(Request $request)
    {
        $query = Lapangan::query();

        // search nama / tipe lapangan
        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where('nama_lapangan', 'like', '%' . $request->search . '%')
                  ->orWhere('tipe_lapangan', 'like', '%' . $request->search . '%');

            });
        }

        // filter status
        if ($request->filled('status')) {

            $query->where('status', $request->status);

        }

        $lapangan = $query->get();

        return view('lapangan.index', compact('lapangan'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('lapangan.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'nomor_lapangan' => 'required',
            'nama_lapangan'  => 'required',
            'tipe_lapangan'  => 'required',
            'harga_per_jam'  => 'required|numeric',
            'status'         => 'required|in:tersedia,terpesan,libur',
            'kapasitas'      => 'required|integer',
            'deskripsi'      => 'nullable|string',
            'fasilitas'      => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {

            $fotoPath = $request->file('foto')->store('lapangan', 'public');

        }

        Lapangan::create([
            'nomor_lapangan' => $request->nomor_lapangan,
            'nama_lapangan'  => $request->nama_lapangan,
            'tipe_lapangan'  => $request->tipe_lapangan,
            'harga_per_jam'  => $request->harga_per_jam,
            'status'         => $request->status,
            'kapasitas'      => $request->kapasitas,
            'deskripsi'      => $request->deskripsi,
            'fasilitas'      => $request->fasilitas,
            'foto'           => $fotoPath,
        ]);

        return redirect('/lapangan')
            ->with('success', 'Data berhasil ditambahkan');
    }

    // ================= SHOW =================
    public function show($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $jadwal = Jadwal::where('lapangan_id', $id)
            ->where('status', 'tersedia')
            ->get();

        return view('lapangan.show', compact('lapangan', 'jadwal'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        return view('lapangan.edit', compact('lapangan'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $request->validate([
            'nomor_lapangan' => 'required',
            'nama_lapangan'  => 'required',
            'tipe_lapangan'  => 'required',
            'harga_per_jam'  => 'required|numeric',
            'status'         => 'required|in:tersedia,terpesan,libur',
            'kapasitas'      => 'required|integer',
            'deskripsi'      => 'nullable|string',
            'fasilitas'      => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $lapangan->foto;

        // upload foto baru
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($lapangan->foto) {

                Storage::disk('public')->delete($lapangan->foto);

            }

            $fotoPath = $request->file('foto')
                ->store('lapangan', 'public');
        }

        $lapangan->update([
            'nomor_lapangan' => $request->nomor_lapangan,
            'nama_lapangan'  => $request->nama_lapangan,
            'tipe_lapangan'  => $request->tipe_lapangan,
            'harga_per_jam'  => $request->harga_per_jam,
            'status'         => $request->status,
            'kapasitas'      => $request->kapasitas,
            'deskripsi'      => $request->deskripsi,
            'fasilitas'      => $request->fasilitas,
            'foto'           => $fotoPath,
        ]);

        return redirect('/lapangan')
            ->with('success', 'Data berhasil diupdate');
    }

    // ================= SOFT DELETE =================
    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $lapangan->delete();

        return redirect('/lapangan')
            ->with('success', 'Lapangan berhasil dinonaktifkan.');
    }

    // ================= HALAMAN TRASH =================
    public function trashed()
    {
        $lapangan = Lapangan::onlyTrashed()->get();

        return view('lapangan.trashed', compact('lapangan'));
    }

    // ================= RESTORE =================
    public function restore($id)
    {
        $lapangan = Lapangan::withTrashed()
            ->findOrFail($id);

        $lapangan->restore();

        return redirect('/lapangan/trashed')
            ->with('success', 'Lapangan berhasil dipulihkan.');
    }

    // ================= HARD DELETE =================
    public function forceDelete($id)
    {
        $lapangan = Lapangan::withTrashed()
            ->findOrFail($id);

        // hapus foto dari storage
        if ($lapangan->foto) {

            Storage::disk('public')->delete($lapangan->foto);

        }

        // hapus permanen dari database
        $lapangan->forceDelete();

        return redirect('/lapangan/trashed')
            ->with('success', 'Lapangan berhasil dihapus permanen.');
    }
}