@extends('layouts.app')

@section('title', 'Booking Lapangan')

@section('content')

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">🏸 Booking Lapangan</h1>

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

        <form action="/pemesanan" method="POST">
            @csrf

            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Jadwal</label>
            <select name="jadwal_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 mb-6">
                @foreach($jadwal as $j)
                    <option value="{{ $j->id }}">
                        {{ $j->lapangan->nama_lapangan }} |
                        {{ $j->tanggal }} |
                        {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-3">
                <button type="submit"
                    class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                    Booking Sekarang
                </button>
                <a href="/pemesanan"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection