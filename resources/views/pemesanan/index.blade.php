@extends('layouts.app')

@section('title', 'Data Pemesanan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Pemesanan</h1>
    <div class="flex gap-3">
        <a href="/pemesanan/create"
            class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
            + Booking
        </a>
        <a href="/notifikasi"
            class="bg-yellow-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
            🔔 Notifikasi
        </a>
        <a href="/review"
            class="bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
            ⭐ Review
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="gradient-hero text-white">
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Lapangan</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Opsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesanan as $p)
            <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                <td class="px-4 py-3">{{ $p->user->name ?? 'User' }}</td>

                {{-- 🔥 NULL SAFE --}}
                <td class="px-4 py-3">
                    {{ $p->jadwal?->lapangan?->nama_lapangan ?? '-' }}
                </td>
                <td class="px-4 py-3">
                    {{ $p->jadwal?->tanggal ?? '-' }}
                </td>

                <td class="px-4 py-3 font-medium">
                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                </td>

                {{-- 🔥 STATUS --}}
                <td class="px-4 py-3">
                    @if($p->status_pemesanan == 'menunggu')
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">
                            ⏳ Menunggu
                        </span>
                    @elseif($p->status_pemesanan == 'menunggu_konfirmasi')
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                            🔍 Menunggu Konfirmasi
                        </span>
                    @elseif($p->status_pemesanan == 'dibayar')
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-semibold">
                            ✓ Lunas
                        </span>
                    @elseif($p->status_pemesanan == 'expired')
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                            ✗ Expired
                        </span>
                    @endif
                </td>

                {{-- 🔥 OPSI --}}
                <td class="px-4 py-3">
                    @if($p->status_pemesanan == 'menunggu')
                        <div class="flex gap-2">
                            <a href="/pembayaran/create/{{ $p->id }}"
                                class="gradient-btn text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                                Bayar
                            </a>

                            <form action="/pemesanan/{{ $p->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Yakin batalkan pemesanan ini?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                                    Batalkan
                                </button>
                            </form>
                        </div>

                    @elseif($p->status_pemesanan == 'menunggu_konfirmasi')
                        <span class="text-blue-500 text-xs font-medium">
                            Menunggu verifikasi admin
                        </span>

                    @elseif($p->status_pemesanan == 'dibayar')
                        <a href="/review"
                            class="bg-purple-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                            ⭐ Review
                        </a>

                    @elseif($p->status_pemesanan == 'expired')
                        <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- EMPTY STATE --}}
    @if($pemesanan->isEmpty())
        <div class="text-center py-12 text-gray-400">
            <p class="text-4xl mb-2">🏸</p>
            <p>Belum ada pemesanan.</p>
            <a href="/pemesanan/create" class="text-blue-600 font-medium hover:underline">
                Booking sekarang!
            </a>
        </div>
    @endif
</div>

@endsection