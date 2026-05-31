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
            <div class="max-h-[60vh] overflow-y-auto pr-2 mb-6 space-y-6">
                @foreach($jadwal->groupBy('lapangan_id') as $lapanganId => $lapanganGroup)
                    @php $namaLapangan = $lapanganGroup->first()->lapangan->nama_lapangan; @endphp
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h3 class="font-bold text-lg text-blue-700 mb-4 flex items-center gap-2">
                            🏟️ {{ $namaLapangan }}
                        </h3>
                        
                        <div class="space-y-4 pl-3 border-l-2 border-blue-200 ml-2">
                            @foreach($lapanganGroup->groupBy('tanggal') as $tanggal => $jadwalGroup)
                                <div>
                                    <h4 class="text-sm font-bold text-gray-600 mb-2">
                                        📅 {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($jadwalGroup as $j)
                                            <label class="cursor-pointer">
                                                <input type="checkbox" name="jadwal_id[]" value="{{ $j->id }}" data-tanggal="{{ $tanggal }}" class="peer hidden jadwal-checkbox">
                                                <div class="text-center py-2 px-3 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:border-blue-400 transition">
                                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                @if($jadwal->isEmpty())
                    <p class="text-gray-400 text-center py-8">Tidak ada jadwal tersedia saat ini.</p>
                @endif
            </div>

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

<script>
    // Membatasi pilihan hanya dalam 1 hari (tanggal yang sama)
    const checkboxes = document.querySelectorAll('.jadwal-checkbox');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            let selectedDate = null;
            
            // Cek jika ada yang dicentang, simpan tanggalnya
            checkboxes.forEach(c => {
                if(c.checked) selectedDate = c.dataset.tanggal;
            });

            // Enable/disable checkbox lain berdasarkan tanggal
            checkboxes.forEach(c => {
                if (selectedDate && c.dataset.tanggal !== selectedDate) {
                    c.disabled = true;
                    c.parentElement.style.opacity = '0.5';
                    c.parentElement.style.cursor = 'not-allowed';
                } else {
                    c.disabled = false;
                    c.parentElement.style.opacity = '1';
                    c.parentElement.style.cursor = 'pointer';
                }
            });
        });
    });
</script>

@endsection