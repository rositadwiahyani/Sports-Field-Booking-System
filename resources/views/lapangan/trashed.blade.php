@extends('layouts.app')

@section('title', 'Lapangan Dinonaktifkan')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🗑️ Lapangan Dinonaktifkan</h1>
    <a href="/lapangan" class="text-blue-600 text-sm font-medium hover:underline">← Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($lapangan as $l)
    <div class="bg-white rounded-xl shadow overflow-hidden opacity-75 border-2 border-red-200">
        @if($l->foto)
            <img src="{{ asset('storage/' . $l->foto) }}" class="w-full h-40 object-cover grayscale">
        @else
            <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                <span class="text-4xl">🏸</span>
            </div>
        @endif

        <div class="p-4">
            <h2 class="text-lg font-bold text-gray-500 mb-1">{{ $l->nama_lapangan }}</h2>
            <p class="text-gray-400 text-xs mb-1">Dinonaktifkan: {{ $l->deleted_at->format('d M Y') }}</p>
            <p class="text-gray-400 text-sm mb-4">Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }} / jam</p>

            <div class="flex gap-2">
                {{-- Restore --}}
                <form action="/lapangan/{{ $l->id }}/restore" method="POST">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Pulihkan lapangan ini?')"
                        class="gradient-btn text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                        ♻️ Pulihkan
                    </button>
                </form>

                {{-- Hard Delete --}}
                <form action="/lapangan/{{ $l->id }}/force-delete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('HAPUS PERMANEN lapangan ini? Data tidak bisa dipulihkan!')"
                        class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium hover:opacity-90 transition">
                        🗑️ Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400 bg-white rounded-xl shadow">
        <p class="text-4xl mb-2">✅</p>
        <p>Tidak ada lapangan yang dinonaktifkan.</p>
    </div>
    @endforelse
</div>

@endsection