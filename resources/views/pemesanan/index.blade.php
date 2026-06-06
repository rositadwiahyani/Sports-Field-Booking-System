@extends('layouts.app')

@section('title', 'Data Pemesanan')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <h1 class="text-2xl font-bold text-[#131b2e]">Data Pemesanan</h1>

    <div class="flex flex-wrap gap-3">
        <a href="/pemesanan/create" class="bg-[#335495] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#254175] hover:shadow-lg hover:shadow-[#335495]/20 transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Booking
        </a>

        <a href="/review"
            class="bg-[#a855f7] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#9333ea] hover:shadow-lg hover:shadow-[#a855f7]/20 transition-all active:scale-95 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">star</span>
            Review
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-[#c3c6d7]/30 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[600px]">
            <thead class="bg-[#f2f3ff] border-b border-[#c3c6d7]/30">
                <tr>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">User</th>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">Lapangan</th>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">Tanggal</th>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">Total</th>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">Status</th>
                    <th class="py-4 px-6 text-sm font-bold text-[#335495] whitespace-nowrap">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#c3c6d7]/20">
                @foreach($pemesanan as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-[#434655]">{{ $p->user->name ?? 'User' }}</td>

                    <td class="py-4 px-6 text-sm text-[#434655]">
                        {{ $p->jadwals->first()->lapangan?->nama_lapangan ?? '-' }}
                    </td>

                    <td class="py-4 px-6 text-sm text-[#434655]">
                        {{ $p->jadwals->first()->tanggal ?? '-' }}
                    </td>

                    <td class="py-4 px-6 text-sm font-bold text-[#131b2e]">
                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                    </td>

                    <td class="py-4 px-6">
                        @if($p->status_pemesanan == 'menunggu')
                            <span class="bg-[#fef08a] text-[#854d0e] px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1 w-max whitespace-nowrap">
                                <span class="material-symbols-outlined text-[14px]">hourglass_empty</span>
                                Menunggu
                            </span>
                        @elseif($p->status_pemesanan == 'menunggu_konfirmasi')
                            <span class="bg-[#bfdbfe] text-[#1e3a8a] px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1 w-max whitespace-nowrap">
                                <span class="material-symbols-outlined text-[14px]">search</span>
                                Verifikasi
                            </span>
                        @elseif($p->status_pemesanan == 'dibayar')
                            <span class="bg-[#bbf7d0] text-[#166534] px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1 w-max whitespace-nowrap">
                                <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                Lunas
                            </span>
                        @elseif($p->status_pemesanan == 'expired')
                            <span class="bg-[#fecaca] text-[#991b1b] px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1 w-max whitespace-nowrap">
                                <span class="material-symbols-outlined text-[14px]">cancel</span>
                                Expired
                            </span>
                        @endif
                    </td>

                    <td class="py-4 px-6">
                        @if($p->status_pemesanan == 'menunggu')
                            <div class="flex gap-2">
                                <a href="/pembayaran/create/{{ $p->id }}"
                                    class="bg-[#10b981] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#059669] transition-colors active:scale-95 whitespace-nowrap">
                                    Bayar
                                </a>

                                <form action="/pemesanan/{{ $p->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Yakin batalkan pemesanan ini?')"
                                        class="bg-[#ef4444] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#dc2626] transition-colors active:scale-95 whitespace-nowrap">
                                        Batalkan
                                    </button>
                                </form>
                            </div>

                        @elseif($p->status_pemesanan == 'menunggu_konfirmasi')
                            <span class="text-[#335495] text-xs font-bold bg-[#f2f3ff] px-3 py-1.5 rounded-lg border border-[#335495]/20 whitespace-nowrap w-max inline-block">
                                Menunggu admin
                            </span>

                        @elseif($p->status_pemesanan == 'dibayar')
                            <a href="/review"
                                class="bg-[#a855f7] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#9333ea] transition-colors active:scale-95 flex items-center gap-1 w-max whitespace-nowrap">
                                <span class="material-symbols-outlined text-[14px]">star</span>
                                Review
                            </a>

                        @elseif($p->status_pemesanan == 'expired')
                            <span class="text-[#a0a3bd] text-xs font-bold px-3 py-1.5 bg-gray-100 rounded-lg whitespace-nowrap">Expired</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pemesanan->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 bg-[#f2f3ff] rounded-full flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-[#335495] text-[32px]">sports_tennis</span>
            </div>
            <h3 class="text-lg font-bold text-[#131b2e] mb-1">Belum ada pemesanan</h3>
            <p class="text-sm text-[#73778b] mb-4">Kamu belum melakukan pemesanan lapangan apapun.</p>
            <a href="/pemesanan/create" class="text-[#335495] text-sm font-bold hover:text-[#254175] hover:underline flex items-center gap-1">
                Booking sekarang <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    @endif
</div>

@endsection