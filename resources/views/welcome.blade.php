@extends('layouts.app')

@section('title', 'Sports Field Booking System - Sistem Booking Lapangan Olahraga')

@section('content')

{{-- LOAD GOOGLE FONTS & MATERIAL ICONS --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .dark .glass-card {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .mesh-gradient {
        background-color: #335495;
        background-image: 
            radial-gradient(at 0% 0%, hsla(220, 49%, 45%, 1) 0, transparent 50%), 
            radial-gradient(at 50% 0%, hsla(215, 52%, 35%, 1) 0, transparent 50%), 
            radial-gradient(at 100% 0%, hsla(220, 50%, 55%, 1) 0, transparent 50%);
    }
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }
</style>

{{-- SCRIPT UNTUK MENCEGAH FLASHING SAAT REFRESH --}}
<script>
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

<div class="bg-[#faf8ff] dark:bg-[#0f172a] text-[#131b2e] dark:text-[#f8fafc] font-['Inter'] overflow-x-hidden antialiased transition-colors duration-300">

    {{-- HERO SECTION --}}
    <section class="relative min-h-[85vh] flex items-center rounded-3xl overflow-hidden mb-16 bg-[#1a2a5e] dark:bg-[#111827] shadow-xl">
        <div class="absolute inset-0 z-0 opacity-85 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=1200');"></div>
        
        <div class="absolute inset-0 bg-gradient-to-r from-[#1a2a5e]/90 via-[#1a2a5e]/30 to-transparent dark:from-[#0f172a]/95 dark:via-[#0f172a]/40 z-0"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 py-16 w-full grid lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7 text-left">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 text-white font-semibold text-[11px] tracking-[0.25em] mb-6 backdrop-blur-md border border-white/20 uppercase">
                    Selamat Datang di
                </div>
                <h1 class="text-4xl md:text-6xl font-['Plus_Jakarta_Sans'] font-extrabold text-white mb-6 leading-tight text-glow">
                    🏟️ Sports Field <br>
                    <span class="text-[#dbe1ff] dark:text-[#93c5fd] italic font-black">Booking System</span>
                </h1>
                <p class="text-[#eaedff]/90 text-base md:text-lg mb-10 max-w-xl leading-relaxed">
                    Sistem booking lapangan badminton yang mudah, cepat, dan terpercaya. Pesan lapangan favoritmu sekarang!
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/lapangan" class="bg-white text-[#335495] font-bold px-8 py-4 rounded-2xl hover:bg-[#f2f3ff] hover:scale-105 transition-all duration-300 shadow-lg text-center flex items-center justify-center whitespace-nowrap">
                        Lihat Lapangan
                    </a>
                    <a href="/pemesanan/create" class="border-2 border-white/30 text-white font-bold px-8 py-4 rounded-2xl hover:bg-white/10 hover:border-white hover:scale-105 transition-all duration-300 backdrop-blur-sm text-center flex items-center justify-center whitespace-nowrap">
                        Booking Sekarang
                    </a>
                </div>
            </div>
            
            <div class="lg:col-span-5 hidden lg:block">
                <div class="grid gap-6">
                    <div class="glass-card p-8 rounded-[2.5rem] shadow-2xl transform rotate-1 hover:rotate-0 transition-all duration-500 group">
                        <div class="w-14 h-14 rounded-2xl bg-[#335495]/10 dark:bg-blue-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[#335495] dark:text-[#60a5fa] text-3xl">trending_up</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-extrabold text-[#131b2e] dark:text-white mb-0.5">Premium</span>
                            <span class="text-sm text-[#434655] dark:text-slate-300 font-semibold tracking-wide">Fasilitas Standar BWF</span>
                        </div>
                    </div>
                    <div class="glass-card p-8 rounded-[2.5rem] shadow-2xl transform -rotate-2 hover:rotate-0 transition-all duration-500 group translate-x-6">
                        <div class="w-14 h-14 rounded-2xl bg-[#22C55E]/10 dark:bg-green-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-[#22C55E] dark:text-[#4ade80] text-3xl">verified</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-extrabold text-[#131b2e] dark:text-white mb-0.5">Terpercaya</span>
                            <span class="text-sm text-[#434655] dark:text-slate-300 font-semibold tracking-wide">Konfirmasi Instan Otomatis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FITUR / KEUNGGULAN SECTION --}}
    <section class="py-12 mb-16">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-12 gap-4">
                <div class="text-center md:text-left">
                    <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold text-[#131b2e] dark:text-white mb-3">Kenapa Sports Field Booking System?</h2>
                    <p class="text-[#434655] dark:text-slate-400 text-sm md:text-base">Kami menghadirkan teknologi terbaik untuk mempermudah rutinitas olahraga Anda.</p>
                </div>
                <div class="h-1 w-24 bg-[#335495] dark:bg-[#60a5fa] rounded-full hidden md:block"></div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#f2f3ff] dark:bg-slate-800/50 p-8 md:p-10 rounded-[2rem] border border-[#c3c6d7]/30 dark:border-slate-700 hover:border-[#335495]/30 hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-700 flex items-center justify-center mb-6 shadow-sm group-hover:bg-[#335495] dark:group-hover:bg-blue-600 transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl text-[#335495] dark:text-[#60a5fa] group-hover:text-white dark:group-hover:text-white">bolt</span>
                    </div>
                    <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-[#131b2e] dark:text-white mb-3">Booking Cepat</h3>
                    <p class="text-[#434655] dark:text-slate-400 text-sm leading-relaxed">Pesan lapangan dalam hitungan detik tanpa antri panjang.</p>
                </div>
                <div class="bg-[#f2f3ff] dark:bg-slate-800/50 p-8 md:p-10 rounded-[2rem] border border-[#c3c6d7]/30 dark:border-slate-700 hover:border-[#335495]/30 hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-700 flex items-center justify-center mb-6 shadow-sm group-hover:bg-[#335495] dark:group-hover:bg-blue-600 transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl text-[#335495] dark:text-[#60a5fa] group-hover:text-white dark:group-hover:text-white">notifications_active</span>
                    </div>
                    <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-[#131b2e] dark:text-white mb-3">Notifikasi Real-time</h3>
                    <p class="text-[#434655] dark:text-slate-400 text-sm leading-relaxed">Dapatkan notifikasi langsung untuk setiap update pemesananmu.</p>
                </div>
                <div class="bg-[#f2f3ff] dark:bg-slate-800/50 p-8 md:p-10 rounded-[2rem] border border-[#c3c6d7]/30 dark:border-slate-700 hover:border-[#335495]/30 hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-700 flex items-center justify-center mb-6 shadow-sm group-hover:bg-[#335495] dark:group-hover:bg-blue-600 transition-all duration-300">
                        <span class="material-symbols-outlined text-2xl text-[#335495] dark:text-[#60a5fa] group-hover:text-white dark:group-hover:text-white">account_balance_wallet</span>
                    </div>
                    <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-[#131b2e] dark:text-white mb-3">Pembayaran Mudah</h3>
                    <p class="text-[#434655] dark:text-slate-400 text-sm leading-relaxed">Bayar via Transfer, Tunai, atau QRIS sesuai kenyamananmu.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- LAPANGAN TERSEDIA SECTION --}}
    <section class="py-12 bg-[#f2f3ff] dark:bg-slate-800/30 rounded-3xl px-4 md:px-8 mb-16">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold text-[#131b2e] dark:text-white mb-2">Lapangan Tersedia</h2>
                    <p class="text-[#434655] dark:text-slate-400 text-sm hidden sm:block">Pilih lapangan berkualitas dengan standar kenyamanan terbaik.</p>
                </div>
                <a class="group flex items-center gap-2 text-[#335495] dark:text-[#60a5fa] font-bold text-sm hover:gap-3 transition-all" href="/lapangan">
                    Lihat Semua Lapangan
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(App\Models\Lapangan::where('status', 'tersedia')->take(3)->get() as $l)
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-[#c3c6d7]/20 dark:border-slate-700 group flex flex-col h-full hover:-translate-y-1">
                    <div class="relative h-56 overflow-hidden bg-gray-100 dark:bg-slate-700">
                        @if($l->foto)
                            <img src="{{ asset('storage/' . $l->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-[#eaedff] dark:bg-slate-700 flex items-center justify-center text-gray-400">
                                <span class="material-symbols-outlined text-5xl text-[#335495] dark:text-[#60a5fa]">sports_tennis</span>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-[#22C55E] text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            Tersedia
                        </div>
                    </div>
                    
                    <div class="p-6 md:p-8 flex flex-col flex-grow">
                        <h3 class="font-['Plus_Jakarta_Sans'] font-bold text-xl text-[#131b2e] dark:text-white mb-4 group-hover:text-[#335495] dark:group-hover:text-blue-400 transition-colors">
                            {{ $l->nama_lapangan }}
                        </h3>
                        
                        <div class="flex flex-col gap-2.5 mb-6">
                            {{-- Kapasitas --}}
                            <div class="bg-[#faf8ff] dark:bg-slate-900/50 px-4 py-3 rounded-xl flex items-center gap-3 border border-[#c3c6d7]/10 dark:border-slate-700">
                                <span class="material-symbols-outlined text-[#335495] dark:text-[#60a5fa] text-xl">groups</span>
                                <span class="text-xs font-semibold text-[#434655] dark:text-slate-300">Kapasitas: {{ $l->kapasitas }} orang</span>
                            </div>
                            
                            {{-- Harga Sewa (Berada di bawah kapasitas) --}}
                            <div class="bg-[#faf8ff] dark:bg-slate-900/50 px-4 py-3 rounded-xl flex items-center justify-between border border-[#c3c6d7]/10 dark:border-slate-700">
                                <span class="text-xs font-semibold text-[#434655] dark:text-slate-400">Harga Sewa:</span>
                                <div class="flex items-baseline gap-0.5 text-[#335495] dark:text-[#60a5fa] whitespace-nowrap">
                                    <span class="text-xs font-bold">Rp</span>
                                    <span class="text-base sm:text-lg font-black tracking-tight">
                                        {{ number_format($l->harga_per_jam, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-[#434655] dark:text-slate-400 font-medium tracking-wide ml-1">/ jam</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Tombol Booking Mandiri di Bawah Tengah --}}
                        <div class="mt-auto pt-4 border-t border-[#c3c6d7]/20 dark:border-slate-700">
                            <a class="w-full bg-[#335495] dark:bg-blue-600 text-white py-3 rounded-xl font-bold text-xs hover:bg-[#335495]/90 dark:hover:bg-blue-700 transition-all shadow-md active:scale-95 flex items-center justify-center text-center whitespace-nowrap" href="/pemesanan/create">
                                Booking Lapangan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="max-w-7xl mx-auto px-4 mb-16">
        <div class="mesh-gradient rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl group">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="relative z-10 max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-2 mb-6 bg-white/15 px-5 py-1.5 rounded-full text-white font-bold text-[11px] tracking-[0.2em] backdrop-blur-md border border-white/10 uppercase">
                    <span class="material-symbols-outlined text-sm">sports_score</span>
                    Ready to Play?
                </div>
                <h2 class="text-white font-['Plus_Jakarta_Sans'] font-extrabold text-3xl md:text-4xl mb-4 leading-tight">
                    Siap Bertanding di Lapangan Terbaik? 🏸
                </h2>
                <p class="text-white/80 text-sm md:text-base mb-8 leading-relaxed">
                    Daftar sekarang dan nikmati kemudahan booking lapangan badminton hanya dalam beberapa klik saja.
                </p>
                <div class="flex justify-center">
                    <a class="bg-white text-[#335495] px-10 py-4 rounded-xl font-extrabold text-sm shadow-xl hover:bg-[#f2f3ff] hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center whitespace-nowrap" href="/pemesanan/create">
                        Mulai Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FLOATING DARK MODE TOGGLE BUTTON --}}
    <button id="theme-toggle" class="fixed bottom-6 right-6 z-50 bg-white dark:bg-slate-800 text-[#335495] dark:text-[#60a5fa] w-14 h-14 rounded-full shadow-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center active:scale-95 hover:scale-110 transition-all duration-300">
        <span id="theme-toggle-light-icon" class="material-symbols-outlined text-2xl hidden">light_mode</span>
        <span id="theme-toggle-dark-icon" class="material-symbols-outlined text-2xl hidden">dark_mode</span>
    </button>

</div>

{{-- JAVASCRIPT LOGIC TOGGLE --}}
<script>
    const themeToggleBtn = document.getElementById('theme-toggle');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');

    function syncIcons() {
        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
        } else {
            lightIcon.classList.add('hidden');
            darkIcon.classList.remove('hidden');
        }
    }

    syncIcons();

    themeToggleBtn.addEventListener('click', function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        syncIcons();
    });
</script>

@endsection