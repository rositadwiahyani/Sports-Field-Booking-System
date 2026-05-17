@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Jadwal</h1>
    <a href="/jadwal/create"
        class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
        + Tambah Jadwal
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="gradient-hero text-white">
                <th class="px-4 py-3 text-left">Lapangan</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Jam</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwal as $j)
            <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium">{{ $j->lapangan->nama_lapangan ?? '-' }}</td>
                <td class="px-4 py-3">{{ $j->tanggal }}</td>
                <td class="px-4 py-3">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                <td class="px-4 py-3">
                    @if($j->status == 'tersedia')
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">Tersedia</span>
                    @elseif($j->status == 'terpesan')
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-semibold">Terpesan</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-semibold">Libur</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="/jadwal/{{ $j->id }}/edit"
                            class="gradient-btn text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                            Edit
                        </a>
                        <form action="/jadwal/{{ $j->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Yakin hapus jadwal ini?')"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-12 text-gray-400">
                    <p class="text-4xl mb-2">📅</p>
                    <p>Belum ada jadwal.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection