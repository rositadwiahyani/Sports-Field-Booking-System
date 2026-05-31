@extends('layouts.app')

@section('title', 'Generate Jadwal Otomatis')

@section('content')

<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">⚡ Generate Jadwal Otomatis</h1>
        <p class="text-gray-500 text-sm mt-1">
            Buat jadwal lapangan secara otomatis (slot per 1 jam dari 10:00 hingga 22:00) pada rentang tanggal yang dipilih.
        </p>
    </div>

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

        <form action="/jadwal" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lapangan</label>
                <select name="lapangan_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="semua">-- Semua Lapangan --</option>
                    @foreach($lapangan as $l)
                        <option value="{{ $l->id }}">{{ $l->nama_lapangan }} ({{ $l->tipe_lapangan }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Pilih "Semua Lapangan" untuk men-generate jadwal bagi semua lapangan yang ada.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required value="{{ date('Y-m-d') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <h4 class="font-semibold text-blue-800 text-sm mb-1">ℹ️ Info Generate</h4>
                <ul class="text-xs text-blue-600 list-disc list-inside space-y-1">
                    <li>Sistem otomatis membuat slot: 10:00-11:00, 11:00-12:00, dst hingga 21:00-22:00.</li>
                    <li>Sistem akan mengabaikan slot yang sudah ada sebelumnya (mencegah jadwal ganda).</li>
                </ul>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition w-full md:w-auto text-center">
                    Mulai Generate
                </button>
                <a href="/jadwal"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition w-full md:w-auto text-center flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection