@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🔔 Notifikasi</h1>
    <a href="/notifikasi/read-all"
        class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
        Tandai Semua Dibaca
    </a>
</div>

<div class="space-y-4">
    @forelse($notifikasi as $n)
        <div class="bg-white rounded-xl shadow p-4 border-l-4 {{ $n->is_read ? 'border-gray-200' : 'border-blue-500' }}">
            <div class="flex items-center justify-between mb-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-800">{{ $n->judul }}</span>
                    @if(!$n->is_read)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Baru</span>
                    @endif
                </div>
                <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-xs text-gray-400 mb-2">
                @if($n->tipe == 'booking_berhasil') 🏸
                @elseif($n->tipe == 'pembayaran_diterima') 💳
                @elseif($n->tipe == 'booking_dibatalkan') ❌
                @elseif($n->tipe == 'pengingat_bayar') ⏰
                @endif
                {{ $n->tipe }}
            </p>
            <p class="text-sm text-gray-600 mb-3">{{ $n->pesan }}</p>
            @if(!$n->is_read)
                <a href="/notifikasi/{{ $n->id }}/read"
                    class="text-blue-600 text-xs font-medium hover:underline">
                    Tandai Sudah Dibaca
                </a>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
            <p class="text-4xl mb-2">🔔</p>
            <p>Belum ada notifikasi.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    <a href="/pemesanan" class="text-blue-600 text-sm font-medium hover:underline">← Kembali ke Pemesanan</a>
</div>

@endsection