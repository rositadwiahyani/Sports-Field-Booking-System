@extends('layouts.app')

@section('title', 'Edit Lapangan')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Lapangan</h1>

    <div class="bg-white rounded-xl shadow p-6">

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/lapangan/{{ $lapangan->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Lapangan</label>
                    <input type="text" name="nomor_lapangan"
                        value="{{ old('nomor_lapangan', $lapangan->nomor_lapangan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lapangan</label>
                    <input type="text" name="nama_lapangan"
                        value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Lapangan</label>
                    <input type="text" name="tipe_lapangan"
                        value="{{ old('tipe_lapangan', $lapangan->tipe_lapangan) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Jam</label>
                    <input type="number" name="harga_per_jam"
                        value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="tersedia" {{ $lapangan->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="terpesan" {{ $lapangan->status == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
                        <option value="libur" {{ $lapangan->status == 'libur' ? 'selected' : '' }}>Libur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas</label>
                    <input type="number" name="kapasitas"
                        value="{{ old('kapasitas', $lapangan->kapasitas) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <textarea name="fasilitas" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('fasilitas', $lapangan->fasilitas) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Lapangan</label>
                @if($lapangan->foto)
                    <img src="{{ asset('storage/' . $lapangan->foto) }}"
                        class="w-40 h-28 object-cover rounded-lg mb-2">
                    <p class="text-xs text-gray-400 mb-2">Upload foto baru untuk mengganti foto lama</p>
                @endif
                <input type="file" name="foto" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                    Update
                </button>
                <a href="/lapangan"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection