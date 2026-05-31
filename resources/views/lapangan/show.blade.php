@extends('layouts.app')

@section('title', $lapangan->nama_lapangan)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Foto --}}
    @if($lapangan->foto)
        <img src="{{ asset('storage/' . $lapangan->foto) }}"
            class="w-full h-72 object-cover rounded-2xl mb-6 shadow">
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Info Utama --}}
        <div class="col-span-2">
            <div class="flex items-start justify-between mb-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $lapangan->nama_lapangan }}</h1>
                @if($lapangan->status == 'tersedia')
                    <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full font-semibold">✅ Tersedia</span>
                @elseif($lapangan->status == 'terpesan')
                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full font-semibold">⏳ Terpesan</span>
                @else
                    <span class="bg-red-100 text-red-700 text-sm px-3 py-1 rounded-full font-semibold">❌ Libur</span>
                @endif
            </div>

            {{-- Tipe Lapangan -- BESAR & MENONJOL --}}
            <div class="inline-block gradient-hero text-white text-sm font-bold px-4 py-2 rounded-xl mb-4 shadow">
                🏟️ {{ $lapangan->tipe_lapangan }}
            </div>

            <p class="text-2xl font-bold text-blue-600 mb-4">
                Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                <span class="text-base font-normal text-gray-400">/ jam</span>
            </p>

            <div class="flex flex-wrap gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl px-4 py-3 text-center flex-1 min-w-[120px]">
                    <p class="text-xs text-gray-400 mb-1">Kapasitas</p>
                    <p class="font-bold text-gray-800">👥 {{ $lapangan->kapasitas }} orang</p>
                </div>
                <div class="bg-gray-50 rounded-xl px-4 py-3 text-center flex-1 min-w-[120px]">
                    <p class="text-xs text-gray-400 mb-1">Nomor</p>
                    <p class="font-bold text-gray-800">{{ $lapangan->nomor_lapangan }}</p>
                </div>
            </div>

            @if($lapangan->deskripsi)
            <div class="mb-6">
                <h3 class="font-bold text-gray-700 mb-2">📋 Deskripsi</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $lapangan->deskripsi }}</p>
            </div>
            @endif

            @if($lapangan->fasilitas)
            <div class="mb-6">
                <h3 class="font-bold text-gray-700 mb-2">🎯 Fasilitas</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $lapangan->fasilitas }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar Booking --}}
        <div class="col-span-1">
            <div class="bg-white rounded-xl shadow p-5 sticky top-6">
                <h3 class="font-bold text-gray-800 mb-4">Pilih Jadwal</h3>

                @if($jadwal->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-4">Tidak ada jadwal tersedia.</p>
                @else
                    <form action="/pemesanan" method="POST">
                        @csrf
                        <select name="jadwal_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 mb-4">
                            @foreach($jadwal as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->tanggal }} | {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="gradient-btn text-white w-full py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                            🏸 Booking Sekarang
                        </button>
                    </form>
                @endif

                <a href="/lapangan"
                    class="block text-center text-gray-400 text-xs mt-3 hover:underline">
                    ← Kembali ke Daftar Lapangan
                </a>
            </div>
        </div>

    </div>
</div>

@endsection

