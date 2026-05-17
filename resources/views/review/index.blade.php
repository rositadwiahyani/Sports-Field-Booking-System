@extends('layouts.app')

@section('title', 'Review Lapangan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">⭐ Review Lapangan</h1>
    <a href="/" class="text-blue-600 text-sm font-medium hover:underline">← Kembali ke Beranda</a>
</div>

<div class="space-y-6">
    @forelse($pemesanan as $p)
    <div class="bg-white rounded-xl shadow p-6">

        {{-- Detail Pemesanan --}}
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $p->jadwal->lapangan->nama_lapangan ?? '-' }}</h2>
                <p class="text-sm text-gray-500">📅 {{ $p->jadwal->tanggal ?? '-' }}</p>
                <p class="text-sm text-blue-600 font-semibold">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
            </div>

            @if($p->review)
                <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">✓ Sudah Direview</span>
            @else
                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-semibold">Belum Direview</span>
            @endif
        </div>

        @if($p->review)
            {{-- Tampilkan review yang sudah ada --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center gap-1 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $p->review->rating)
                            <span class="text-yellow-400 text-lg">⭐</span>
                        @else
                            <span class="text-gray-300 text-lg">⭐</span>
                        @endif
                    @endfor
                    <span class="text-sm text-gray-500 ml-2">{{ $p->review->rating }}/5</span>
                </div>
                <p class="text-sm text-gray-600">{{ $p->review->komentar ?? '-' }}</p>
            </div>
        @else
            {{-- Form Review --}}
            <form action="/review" method="POST">
                @csrf
                <input type="hidden" name="pemesanan_id" value="{{ $p->id }}">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select name="rating"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="5">⭐⭐⭐⭐⭐ (5) — Sangat Bagus</option>
                        <option value="4">⭐⭐⭐⭐ (4) — Bagus</option>
                        <option value="3">⭐⭐⭐ (3) — Cukup</option>
                        <option value="2">⭐⭐ (2) — Kurang</option>
                        <option value="1">⭐ (1) — Sangat Kurang</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                    <textarea name="komentar" rows="3" placeholder="Tulis pengalamanmu..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <button type="submit"
                    class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                    Kirim Review
                </button>
            </form>
        @endif
    </div>
    @empty
        <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
            <p class="text-4xl mb-2">⭐</p>
            <p>Belum ada pemesanan yang selesai dibayar.</p>
            <a href="/pemesanan" class="text-blue-600 font-medium hover:underline">Booking sekarang!</a>
        </div>
    @endforelse
</div>

@endsection