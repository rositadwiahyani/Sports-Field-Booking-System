@extends('layouts.app')

@section('title', 'Data Lapangan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Lapangan</h1>
    @auth
    @if(Auth::user()->isAdmin())
    <a href="/lapangan/create"
        class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
        + Tambah Lapangan
    </a>
    @endif
    @endauth
</div>

{{-- SEARCH & FILTER --}}
<form method="GET" action="/lapangan" class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama lapangan atau tipe..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <select name="status"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="terpesan" {{ request('status') == 'terpesan' ? 'selected' : '' }}>Terpesan</option>
                <option value="libur" {{ request('status') == 'libur' ? 'selected' : '' }}>Libur</option>
            </select>
        </div>
        <button type="submit"
            class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
            🔍 Cari
        </button>
        @if(request('search') || request('status'))
            <a href="/lapangan"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- HASIL SEARCH --}}
@if(request('search') || request('status'))
    <p class="text-sm text-gray-500 mb-4">
        Menampilkan {{ $lapangan->count() }} hasil
        @if(request('search')) untuk "<strong>{{ request('search') }}</strong>" @endif
        @if(request('status')) dengan status "<strong>{{ request('status') }}</strong>" @endif
    </p>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($lapangan as $l)
    <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-md transition">

        @if($l->foto)
            <img src="{{ asset('storage/' . $l->foto) }}" class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                <span class="text-4xl">🏸</span>
            </div>
        @endif

        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <a href="/lapangan/{{ $l->id }}" class="text-lg font-bold text-gray-800 hover:text-blue-600 transition">
                    {{ $l->nama_lapangan }}
                </a>
                @if($l->status == 'tersedia')
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">Tersedia</span>
                @elseif($l->status == 'terpesan')
                    <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-semibold">Terpesan</span>
                @else
                    <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-semibold">Libur</span>
                @endif
            </div>

            <span class="inline-block gradient-btn text-white text-xs font-bold px-3 py-1 rounded-lg mb-2">
                🏟️ {{ $l->tipe_lapangan }}
            </span>

            <p class="text-blue-600 font-semibold text-sm mb-1">
                Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }} / jam
            </p>
            <p class="text-gray-500 text-xs mb-1">👥 {{ $l->kapasitas }} orang</p>

            @if($l->deskripsi)
                <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $l->deskripsi }}</p>
            @endif
            @if($l->fasilitas)
                <p class="text-gray-500 text-xs mb-3 line-clamp-2">🎯 {{ $l->fasilitas }}</p>
            @endif

            <div class="flex gap-2 mt-3">
                <a href="/lapangan/{{ $l->id }}"
                    class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-gray-200 transition">
                    Detail
                </a>

                @auth
                @if(!Auth::user()->isAdmin())
                    @if($l->status == 'tersedia')
                        <a href="/lapangan/{{ $l->id }}/book"
                            class="gradient-btn text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                            🏸 Booking
                        </a>
                    @else
                        <button disabled
                            class="bg-gray-200 text-gray-400 px-3 py-1 rounded-lg text-xs font-medium cursor-not-allowed">
                            {{ $l->status == 'terpesan' ? 'Terpesan' : 'Libur' }}
                        </button>
                    @endif
                @endif

                @if(Auth::user()->isAdmin())
                    <a href="/lapangan/{{ $l->id }}/edit"
                        class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                        Edit
                    </a>
                    <form action="/lapangan/{{ $l->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Yakin hapus lapangan ini?')"
                            class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                            Hapus
                        </button>
                    </form>
                @endif
                @endauth
            </div>
        </div>
    </div>

    @empty
        <div class="col-span-3 text-center py-12 text-gray-400">
            <p class="text-4xl mb-2">🔍</p>
            <p>Tidak ada lapangan yang ditemukan.</p>
            <a href="/lapangan" class="text-blue-600 text-sm font-medium hover:underline mt-2 block">Reset pencarian</a>
        </div>
    @endforelse
</div>

@endsection
