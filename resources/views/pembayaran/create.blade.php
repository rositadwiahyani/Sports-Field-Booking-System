@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">💳 Form Pembayaran</h1>

    <div class="bg-white rounded-xl shadow p-6">

        {{-- Detail Pemesanan --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-3">Detail Pemesanan</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Lapangan</span>
                    <span class="font-medium">{{ $pemesanan->jadwal->lapangan->nama_lapangan }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-medium">{{ $pemesanan->jadwal->tanggal }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jam</span>
                    <span class="font-medium">{{ $pemesanan->jadwal->jam_mulai }} - {{ $pemesanan->jadwal->jam_selesai }}</span>
                </div>
                <div class="flex justify-between border-t pt-2 mt-2">
                    <span class="text-gray-700 font-semibold">Total</span>
                    <span class="text-blue-600 font-bold text-lg">
                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <form action="/pembayaran" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="metode_bayar"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="transfer">🏦 Transfer Bank</option>
                    <option value="tunai">💵 Tunai</option>
                    <option value="qris">📱 QRIS</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Bukti Pembayaran <span class="text-red-500">*</span>
                </label>
                <input type="file" name="bukti_bayar" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p class="text-xs text-gray-400 mt-1">
                    Upload foto bukti pembayaran (JPG, PNG)
                </p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                    Bayar Sekarang
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