@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">📅 Data Jadwal Lapangan</h1>
        <p class="text-gray-500 text-sm">Klik lapangan untuk melihat jadwalnya</p>
    </div>
    <div class="flex gap-3 self-start">
        @if($selectedLapangan)
            <a href="/jadwal"
                class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                Tampilkan Semua
            </a>
        @endif
        <a href="/jadwal/create"
            class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
            + Tambah Jadwal
        </a>
    </div>
</div>

{{-- ================= LAPANGAN CARDS ================= --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @foreach($lapanganList as $l)
        <a href="/jadwal?lapangan_id={{ $l->id }}"
            class="relative bg-white rounded-xl shadow p-5 transition-all duration-200 border-2
                {{ $selectedLapangan == $l->id
                    ? 'border-blue-500 ring-2 ring-blue-200 shadow-lg scale-[1.02]'
                    : 'border-transparent hover:border-blue-300 hover:shadow-md' }}">

            {{-- Indicator aktif --}}
            @if($selectedLapangan == $l->id)
                <div class="absolute -top-2 -right-2 w-6 h-6 gradient-hero rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            @endif

            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg gradient-hero flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                    {{ $l->nomor_lapangan ?? substr($l->nama_lapangan, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-gray-800 text-sm truncate">{{ $l->nama_lapangan }}</h3>
                    <p class="text-xs text-gray-400">{{ $l->tipe_lapangan ?? 'Badminton' }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">
                    📋 {{ $l->jadwal_count }} jadwal
                </span>
                @if($l->status == 'tersedia')
                    <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">Aktif</span>
                @else
                    <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-semibold">Nonaktif</span>
                @endif
            </div>
        </a>
    @endforeach
</div>

{{-- ================= SELECTED INFO ================= --}}
@if($selectedLapangan)
    @php
        $currentLapangan = $lapanganList->firstWhere('id', $selectedLapangan);
    @endphp
    @if($currentLapangan)
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-3 mb-6 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg gradient-hero flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                {{ $currentLapangan->nomor_lapangan ?? substr($currentLapangan->nama_lapangan, 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-800">
                    Menampilkan jadwal untuk: {{ $currentLapangan->nama_lapangan }}
                </p>
                <p class="text-xs text-blue-500">
                    {{ $currentLapangan->tipe_lapangan ?? 'Badminton' }} • Rp {{ number_format($currentLapangan->harga_per_jam, 0, ',', '.') }}/jam • Kapasitas {{ $currentLapangan->kapasitas }} orang
                </p>
            </div>
        </div>
    @endif
@else
    <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 mb-6">
        <p class="text-sm text-gray-500">
            📊 Menampilkan <span class="font-bold text-gray-800">semua jadwal</span> dari {{ $lapanganList->count() }} lapangan — total <span class="font-bold text-gray-800">{{ $jadwal->count() }}</span> jadwal
        </p>
    </div>
@endif

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="gradient-hero text-white">
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    @if(!$selectedLapangan)
                        <th class="px-4 py-3 text-left font-semibold">Lapangan</th>
                    @endif
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Mulai</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam Selesai</th>
                    <th class="px-4 py-3 text-left font-semibold">Durasi</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $index => $j)
                <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition-colors">

                    <td class="px-4 py-3 text-gray-500 font-medium">{{ $index + 1 }}</td>

                    @if(!$selectedLapangan)
                        <td class="px-4 py-3">
                            <a href="/jadwal?lapangan_id={{ $j->lapangan->id ?? '' }}"
                                class="font-medium text-blue-600 hover:underline">
                                {{ $j->lapangan->nama_lapangan ?? '-' }}
                            </a>
                        </td>
                    @endif

                    <td class="px-4 py-3 text-gray-700 font-medium">{{ $j->tanggal }}</td>

                    <td class="px-4 py-3 text-gray-700">{{ substr($j->jam_mulai, 0, 5) }}</td>

                    <td class="px-4 py-3 text-gray-700">{{ substr($j->jam_selesai, 0, 5) }}</td>

                    <td class="px-4 py-3 text-gray-500 text-xs">
                        @php
                            $mulai = \Carbon\Carbon::parse($j->jam_mulai);
                            $selesai = \Carbon\Carbon::parse($j->jam_selesai);
                            $durasi = $mulai->diffInMinutes($selesai);
                            $jam = intdiv($durasi, 60);
                            $menit = $durasi % 60;
                        @endphp
                        {{ $jam > 0 ? $jam . ' jam' : '' }}{{ $menit > 0 ? ' ' . $menit . ' mnt' : '' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($j->status == 'tersedia')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                                ✅ Tersedia
                            </span>
                        @elseif($j->status == 'terpesan')
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-semibold">
                                📌 Terpesan
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-semibold">
                                🚫 Libur
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/jadwal/{{ $j->id }}/edit"
                                class="gradient-btn text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:opacity-90 transition">
                                ✏️ Edit
                            </a>
                            <form action="/jadwal/{{ $j->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Yakin hapus jadwal ini?')"
                                    class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="{{ $selectedLapangan ? 7 : 8 }}" class="text-center py-16 text-gray-400">
                        <p class="text-5xl mb-3">📭</p>
                        <p class="text-lg font-medium">Belum ada jadwal{{ $selectedLapangan ? ' untuk lapangan ini' : '' }}.</p>
                        <a href="/jadwal/create" class="text-blue-600 text-sm font-medium hover:underline mt-2 inline-block">
                            + Tambah Jadwal Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
