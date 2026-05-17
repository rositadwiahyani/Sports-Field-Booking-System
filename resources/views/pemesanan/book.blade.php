@extends('layouts.app')

@section('title', 'Booking ' . $lapangan->nama_lapangan)

@section('content')

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">🏸 Booking Lapangan</h1>
    <p class="text-gray-500 text-sm mb-6">{{ $lapangan->nama_lapangan }}</p>

    <div class="bg-white rounded-xl shadow p-6 mb-4">
        {{-- Info Lapangan --}}
        @if($lapangan->foto)
            <img src="{{ asset('storage/' . $lapangan->foto) }}" class="w-full h-40 object-cover rounded-lg mb-4">
        @endif
        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
            <div>
                <span class="text-gray-500">Tipe</span>
                <p class="font-medium">{{ $lapangan->tipe_lapangan }}</p>
            </div>
            <div>
                <span class="text-gray-500">Kapasitas</span>
                <p class="font-medium">{{ $lapangan->kapasitas }} orang</p>
            </div>
            <div>
                <span class="text-gray-500">Harga</span>
                <p class="font-medium text-blue-600">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam</p>
            </div>
            @if($lapangan->fasilitas)
            <div>
                <span class="text-gray-500">Fasilitas</span>
                <p class="font-medium">{{ $lapangan->fasilitas }}</p>
            </div>
            @endif
        </div>
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

        @if($jadwal->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <p class="text-4xl mb-2">😔</p>
                <p>Tidak ada slot jadwal tersedia untuk lapangan ini.</p>
                <a href="/lapangan" class="text-blue-600 text-sm font-medium hover:underline mt-2 block">← Kembali</a>
            </div>
        @else
            <form action="/pemesanan" method="POST">
                @csrf

                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jadwal</label>
                <select name="jadwal_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 mb-6">
                    @foreach($jadwal as $j)
                        <option value="{{ $j->id }}">
                            {{ $j->tanggal }} | {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-3">
                    <button type="submit"
                        class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                        Booking Sekarang
                    </button>
                    <a href="/lapangan"
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        @endif
    </div>
</div>

@endsection