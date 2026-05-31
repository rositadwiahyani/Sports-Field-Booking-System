@extends('layouts.app')

@section('title', 'Sports Field Booking System - Sistem Booking Lapangan Olahraga')

@section('content')

{{-- HERO SECTION --}}
<div class="gradient-hero rounded-2xl text-white px-8 py-16 mb-10 text-center">
    <p class="text-blue-200 text-sm font-medium mb-2 tracking-widest uppercase">Selamat Datang di</p>
    <h1 class="text-5xl font-bold mb-4">🏟️ Sports Field Booking System</h1>
    <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">
        Sistem booking lapangan badminton yang mudah, cepat, dan terpercaya.
        Pesan lapangan favoritmu sekarang!
    </p>
    <div class="flex flex-col sm:flex-row flex-wrap gap-4 justify-center">
        <a href="/lapangan"
            class="bg-white text-blue-700 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50 transition">
            Lihat Lapangan
        </a>
        <a href="/pemesanan/create"
            class="border-2 border-white text-white font-semibold px-6 py-3 rounded-xl hover:bg-white hover:text-blue-700 transition">
            Booking Sekarang
        </a>
    </div>
</div>

{{-- FITUR SECTION --}}
<div class="mb-10">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Kenapa Sports Field Booking System?</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-4xl mb-3">⚡</div>
            <h3 class="font-bold text-gray-800 mb-2">Booking Cepat</h3>
            <p class="text-gray-500 text-sm">Pesan lapangan dalam hitungan detik tanpa antri panjang.</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-4xl mb-3">🔔</div>
            <h3 class="font-bold text-gray-800 mb-2">Notifikasi Real-time</h3>
            <p class="text-gray-500 text-sm">Dapatkan notifikasi langsung untuk setiap update pemesananmu.</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <div class="text-4xl mb-3">💳</div>
            <h3 class="font-bold text-gray-800 mb-2">Pembayaran Mudah</h3>
            <p class="text-gray-500 text-sm">Bayar via Transfer, Tunai, atau QRIS sesuai kenyamananmu.</p>
        </div>
    </div>
</div>

{{-- LAPANGAN TERSEDIA --}}
<div class="mb-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lapangan Tersedia</h2>
        <a href="/lapangan" class="text-blue-600 text-sm font-medium hover:underline">Lihat Semua →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach(App\Models\Lapangan::where('status', 'tersedia')->take(3)->get() as $l)
        <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-md transition">
            @if($l->foto)
                <img src="{{ asset('storage/' . $l->foto) }}" class="w-full h-40 object-cover">
            @else
                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                    <span class="text-4xl">🏸</span>
                </div>
            @endif
            <div class="p-4">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-bold text-gray-800">{{ $l->nama_lapangan }}</h3>
                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold">Tersedia</span>
                </div>
                <p class="text-blue-600 font-semibold text-sm mb-1">
                    Rp {{ number_format($l->harga_per_jam, 0, ',', '.') }} / jam
                </p>
                <p class="text-gray-500 text-xs mb-3">👥 {{ $l->kapasitas }} orang</p>
                <a href="/pemesanan/create"
                    class="gradient-btn text-white px-4 py-2 rounded-lg text-xs font-medium hover:opacity-90 transition block text-center">
                    Booking Sekarang
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- CTA SECTION --}}
<div class="bg-white rounded-2xl shadow p-8 text-center">
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Siap Bermain? 🏸</h2>
    <p class="text-gray-500 mb-6">Daftar sekarang dan nikmati kemudahan booking lapangan badminton.</p>
    <a href="/pemesanan/create"
        class="gradient-btn text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition">
        Mulai Booking
    </a>
</div>

@endsection

