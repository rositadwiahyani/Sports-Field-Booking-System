@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">💳 Konfirmasi Pembayaran</h1>
    <a href="/admin/dashboard" class="text-blue-600 text-sm font-medium hover:underline">← Dashboard</a>
</div>

<div class="space-y-6">
    @forelse($pembayaran as $p)
    <div class="bg-white rounded-xl shadow p-6">
        <div class="grid grid-cols-2 gap-6">

            {{-- Info Pemesanan --}}
            <div>
                <h2 class="font-bold text-gray-800 mb-3">Detail Pemesanan</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">User</span>
                        <span class="font-medium">{{ $p->pemesanan->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Lapangan</span>
                        <span class="font-medium">{{ $p->pemesanan->jadwal->lapangan->nama_lapangan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-medium">{{ $p->pemesanan->jadwal->tanggal }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-medium uppercase">{{ $p->metode_bayar }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-700 font-semibold">Total</span>
                        <span class="text-blue-600 font-bold">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Tombol Approve/Reject --}}
                <div class="flex gap-3 mt-6">
                    <form action="/admin/konfirmasi/{{ $p->id }}/approve" method="POST">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Konfirmasi pembayaran ini?')"
                            class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                            ✓ Approve
                        </button>
                    </form>
                    <form action="/admin/konfirmasi/{{ $p->id }}/reject" method="POST">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Tolak pembayaran ini?')"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                            ✗ Reject
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div>
                <h2 class="font-bold text-gray-800 mb-3">Bukti Pembayaran</h2>
                @if($p->bukti_bayar)
                    <img src="{{ asset('storage/' . $p->bukti_bayar) }}"
                        class="w-full rounded-lg border border-gray-200 object-cover max-h-64">
                @else
                    <div class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                        <p class="text-sm">Tidak ada bukti</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
        <p class="text-4xl mb-2">✅</p>
        <p>Tidak ada pembayaran yang perlu dikonfirmasi.</p>
    </div>
    @endforelse

@endsection