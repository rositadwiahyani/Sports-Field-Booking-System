@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">👑 Admin Dashboard</h1>
    <p class="text-gray-500 text-sm">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-4xl font-bold text-blue-600 mb-1">{{ $totalPemesanan }}</p>
        <p class="text-gray-500 text-sm">Total Pemesanan</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-4xl font-bold text-blue-600 mb-1">{{ $totalLapangan }}</p>
        <p class="text-gray-500 text-sm">Total Lapangan</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-4xl font-bold text-blue-600 mb-1">{{ $totalUser }}</p>
        <p class="text-gray-500 text-sm">Total Pelanggan</p>
    </div>
</div>

{{-- Menu Admin --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="/admin/lapangan" class="bg-white rounded-xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <span class="text-4xl">🏸</span>
        <div>
            <h3 class="font-bold text-gray-800">Kelola Lapangan</h3>
            <p class="text-gray-500 text-sm">Tambah, edit, hapus lapangan</p>
        </div>
    </a>
    <a href="/admin/jadwal" class="bg-white rounded-xl shadow p-6 hover:shadow-md transition flex items-center gap-4">
        <span class="text-4xl">📅</span>
        <div>
            <h3 class="font-bold text-gray-800">Kelola Jadwal</h3>
            <p class="text-gray-500 text-sm">Atur slot jadwal lapangan</p>
        </div>
    </a>
</div>

{{-- Pemesanan Terbaru --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-bold text-gray-800">Pemesanan Terbaru</h2>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="gradient-hero text-white">
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Lapangan</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesananTerbaru as $p)
            <tr class="border-t border-gray-100 hover:bg-gray-50">
                <td class="px-4 py-3">{{ $p->user->name ?? '-' }}</td>
                <td class="px-4 py-3">{{ $p->jadwals->first()->lapangan->nama_lapangan ?? '-' }}</td>
                <td class="px-4 py-3">{{ $p->jadwals->first()->tanggal ?? '-' }} ({{ $p->jadwals->count() }} Jam)</td>
                <td class="px-4 py-3">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    @if($p->status_pemesanan == 'menunggu')
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">⏳ Menunggu</span>
                    @elseif($p->status_pemesanan == 'dibayar')
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">✓ Lunas</span>
                    @elseif($p->status_pemesanan == 'expired')
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">✗ Expired</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-400">Belum ada pemesanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection