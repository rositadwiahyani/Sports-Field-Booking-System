@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">💳 Konfirmasi Pembayaran</h1>
        <p class="text-gray-500 text-sm">Kelola dan konfirmasi pembayaran dari pelanggan</p>
    </div>
    <a href="/admin/dashboard"
        class="gradient-btn text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition inline-flex items-center gap-2 self-start">
        ← Kembali ke Dashboard
    </a>
</div>

{{-- ================= STATS CARDS ================= --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6 text-center border-l-4 border-yellow-400">
        <p class="text-4xl font-bold text-yellow-500 mb-1">{{ $totalPending }}</p>
        <p class="text-gray-500 text-sm">⏳ Menunggu Konfirmasi</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 text-center border-l-4 border-green-400">
        <p class="text-4xl font-bold text-green-500 mb-1">{{ $totalLunas }}</p>
        <p class="text-gray-500 text-sm">✅ Dikonfirmasi (Lunas)</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 text-center border-l-4 border-red-400">
        <p class="text-4xl font-bold text-red-500 mb-1">{{ $totalGagal }}</p>
        <p class="text-gray-500 text-sm">❌ Ditolak (Gagal)</p>
    </div>

</div>

{{-- ================= SEARCH & FILTER BAR ================= --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <form action="/admin/konfirmasi" method="GET" class="flex flex-col md:flex-row gap-4 items-end">

        {{-- Search --}}
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-gray-500 mb-1">🔍 Cari</label>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari nama user atau nama lapangan..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
        </div>

        {{-- Filter Status --}}
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-gray-500 mb-1">📋 Status</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition bg-white">
                <option value="pending" {{ ($filterStatus ?? 'pending') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                <option value="lunas" {{ ($filterStatus ?? '') == 'lunas' ? 'selected' : '' }}>✅ Lunas</option>
                <option value="gagal" {{ ($filterStatus ?? '') == 'gagal' ? 'selected' : '' }}>❌ Gagal</option>
                <option value="semua" {{ ($filterStatus ?? '') == 'semua' ? 'selected' : '' }}>📊 Semua Status</option>
            </select>
        </div>

        {{-- Filter Metode --}}
        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-gray-500 mb-1">💰 Metode Bayar</label>
            <select name="metode"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition bg-white">
                <option value="semua" {{ ($filterMetode ?? '') == 'semua' || !($filterMetode ?? '') ? 'selected' : '' }}>Semua Metode</option>
                <option value="transfer" {{ ($filterMetode ?? '') == 'transfer' ? 'selected' : '' }}>🏦 Transfer</option>
                <option value="tunai" {{ ($filterMetode ?? '') == 'tunai' ? 'selected' : '' }}>💵 Tunai</option>
                <option value="qris" {{ ($filterMetode ?? '') == 'qris' ? 'selected' : '' }}>📱 QRIS</option>
            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-2">
            <button type="submit"
                class="gradient-btn text-white px-5 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition whitespace-nowrap">
                Cari
            </button>
            <a href="/admin/konfirmasi"
                class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition whitespace-nowrap">
                Reset
            </a>
        </div>

    </form>
</div>

{{-- ================= HASIL FILTER INFO ================= --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-gray-500 text-sm">
        Menampilkan <span class="font-bold text-gray-800">{{ $pembayaran->count() }}</span> data pembayaran
        @if($search)
            untuk pencarian "<span class="font-semibold text-blue-600">{{ $search }}</span>"
        @endif
    </p>
</div>

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-xl shadow overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="konfirmasi-table">

            <thead>
                <tr class="gradient-hero text-white">
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                    <th class="px-4 py-3 text-left font-semibold">Lapangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam</th>
                    <th class="px-4 py-3 text-left font-semibold">Metode</th>
                    <th class="px-4 py-3 text-left font-semibold">Nominal</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Waktu Bayar</th>
                    <th class="px-4 py-3 text-center font-semibold">Bukti</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pembayaran as $index => $p)

                <tr class="border-t border-gray-100 hover:bg-blue-50/40 transition-colors">

                    {{-- No --}}
                    <td class="px-4 py-3 text-gray-500 font-medium">
                        {{ $index + 1 }}
                    </td>

                    {{-- User --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full gradient-hero flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($p->pemesanan->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $p->pemesanan->user->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $p->pemesanan->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Lapangan --}}
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ optional(optional($p->pemesanan->jadwal)->lapangan)->nama_lapangan ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ optional(optional($p->pemesanan->jadwal)->lapangan)->tipe_lapangan ?? '' }}</p>
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-4 py-3 text-gray-700">
                        {{ optional($p->pemesanan->jadwal)->tanggal ?? '-' }}
                    </td>

                    {{-- Jam --}}
                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                        @if($p->pemesanan->jadwals->isNotEmpty())
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($p->pemesanan->jadwals->min('jam_mulai'))->format('H:i') }} - {{ \Carbon\Carbon::parse($p->pemesanan->jadwals->max('jam_selesai'))->format('H:i') }} ({{ $p->pemesanan->jadwals->count() }} Jam)
                            </div>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Metode --}}
                    <td class="px-4 py-3">
                        @php
                            $metodeColors = [
                                'transfer' => 'bg-blue-100 text-blue-700',
                                'tunai'    => 'bg-green-100 text-green-700',
                                'qris'     => 'bg-purple-100 text-purple-700',
                            ];
                            $metodeIcons = [
                                'transfer' => '🏦',
                                'tunai'    => '💵',
                                'qris'     => '📱',
                            ];
                            $mColor = $metodeColors[$p->metode_bayar] ?? 'bg-gray-100 text-gray-700';
                            $mIcon  = $metodeIcons[$p->metode_bayar] ?? '💳';
                        @endphp
                        <span class="{{ $mColor }} text-xs px-2 py-1 rounded-full font-semibold whitespace-nowrap">
                            {{ $mIcon }} {{ ucfirst($p->metode_bayar) }}
                        </span>
                    </td>

                    {{-- Nominal --}}
                    <td class="px-4 py-3">
                        <span class="font-bold text-blue-600">Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        @if($p->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-semibold">
                                ⏳ Pending
                            </span>
                        @elseif($p->status == 'lunas')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-semibold">
                                ✅ Lunas
                            </span>
                        @elseif($p->status == 'gagal')
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-semibold">
                                ❌ Ditolak
                            </span>
                        @endif
                    </td>

                    {{-- Waktu Bayar --}}
                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                        {{ $p->waktu_bayar ?? $p->created_at->format('d M Y H:i') }}
                    </td>

                    {{-- Bukti --}}
                    <td class="px-4 py-3 text-center">
                        @if($p->bukti_bayar)
                            <button onclick="openBuktiModal('{{ asset('storage/' . $p->bukti_bayar) }}', '{{ $p->pemesanan->user->name ?? '-' }}')"
                                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold hover:bg-blue-200 transition cursor-pointer">
                                🖼️ Lihat
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Tidak ada</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3 text-center">
                        @if($p->status == 'pending')
                            <div class="flex items-center justify-center gap-2">
                                <form action="/admin/konfirmasi/{{ $p->id }}/approve" method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi pembayaran ini?')"
                                        class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-green-600 transition"
                                        title="Approve">
                                        ✓ Approve
                                    </button>
                                </form>
                                <form action="/admin/konfirmasi/{{ $p->id }}/reject" method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Tolak pembayaran ini?')"
                                        class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-600 transition"
                                        title="Reject">
                                        ✗ Reject
                                    </button>
                                </form>
                            </div>
                        @elseif($p->status == 'lunas')
                            <span class="text-green-600 text-xs font-semibold">Dikonfirmasi</span>
                        @elseif($p->status == 'gagal')
                            <span class="text-red-600 text-xs font-semibold">Ditolak</span>
                        @endif
                    </td>

                </tr>

                {{-- Detail Row (expandable) --}}
                <tr class="hidden detail-row-{{ $p->id }} bg-gray-50">
                    <td colspan="11" class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-bold text-gray-700 mb-2 text-sm">📋 Detail Pemesanan</h4>
                                <div class="space-y-1 text-xs text-gray-600">
                                    <p><span class="text-gray-400">ID Pemesanan:</span> #{{ $p->pemesanan->id ?? '-' }}</p>
                                    <p><span class="text-gray-400">Status Pemesanan:</span> {{ ucfirst(str_replace('_', ' ', $p->pemesanan->status_pemesanan ?? '-')) }}</p>
                                    <p><span class="text-gray-400">Total Harga:</span> Rp {{ number_format($p->pemesanan->total_harga ?? 0, 0, ',', '.') }}</p>
                                    <p><span class="text-gray-400">Batas Bayar:</span> {{ $p->pemesanan->batas_bayar ?? '-' }}</p>
                                </div>
                            </div>
                            @if($p->bukti_bayar)
                            <div>
                                <h4 class="font-bold text-gray-700 mb-2 text-sm">🖼️ Bukti Pembayaran</h4>
                                <img src="{{ asset('storage/' . $p->bukti_bayar) }}"
                                    class="w-48 rounded-lg border border-gray-200 object-cover cursor-pointer hover:opacity-80 transition"
                                    onclick="openBuktiModal('{{ asset('storage/' . $p->bukti_bayar) }}', '{{ $p->pemesanan->user->name ?? '-' }}')"
                                    alt="Bukti Bayar">
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="11" class="text-center py-16 text-gray-400">
                        <p class="text-5xl mb-3">📭</p>
                        <p class="text-lg font-medium">Tidak ada data pembayaran ditemukan.</p>
                        <p class="text-sm mt-1">Coba ubah filter atau kata pencarian.</p>
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

{{-- ================= BUKTI MODAL ================= --}}
<div id="buktiModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm"
    onclick="closeBuktiModal(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden transform transition-all"
        onclick="event.stopPropagation()">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800" id="buktiModalTitle">Bukti Pembayaran</h3>
            <button onclick="closeBuktiModal()"
                class="text-gray-400 hover:text-gray-700 text-2xl leading-none transition">&times;</button>
        </div>
        <div class="p-6">
            <img id="buktiModalImage" src="" alt="Bukti Pembayaran"
                class="w-full rounded-lg border border-gray-200 object-contain max-h-96">
        </div>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
<script>
    // Bukti Modal
    function openBuktiModal(imageSrc, userName) {
        const modal = document.getElementById('buktiModal');
        document.getElementById('buktiModalImage').src = imageSrc;
        document.getElementById('buktiModalTitle').textContent = 'Bukti Pembayaran — ' + userName;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeBuktiModal(event) {
        if (event && event.target !== event.currentTarget) return;
        const modal = document.getElementById('buktiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeBuktiModal();
    });

    // Toggle detail row
    function toggleDetail(id) {
        const row = document.querySelector('.detail-row-' + id);
        if (row) row.classList.toggle('hidden');
    }
</script>

@endsection