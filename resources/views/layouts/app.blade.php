<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sports Field Booking System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Script inisialisasi dark mode biar gak kedip putih saat load --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .heading-font { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#faf8ff] dark:bg-[#0f172a] text-[#131b2e] dark:text-[#f8fafc] min-h-screen antialiased selection:bg-[#335495]/20 flex flex-col transition-colors duration-300">

    {{-- MODERN FLOATING NAVBAR --}}
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-[95%] z-50 bg-[#faf8ff]/80 dark:bg-[#1e293b]/80 backdrop-blur-xl border border-[#c3c6d7]/30 dark:border-white/10 rounded-2xl shadow-lg shadow-[#335495]/5">
        <div class="w-full px-6 py-3.5 flex items-center justify-between">
            
            <a href="/" class="heading-font flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-[#335495] rounded-xl flex items-center justify-center shadow-md shadow-[#335495]/20">
                    <span class="material-symbols-outlined text-white text-[20px]">sports_tennis</span>
                </div>
                <span class="text-lg font-extrabold text-[#335495] dark:text-white tracking-tight">
                    Sports Field Booking
                </span>
            </a>

            @php
                $unreadNotif = 0;
                if (Auth::check()) {
                    $unreadNotif = Auth::user()->notifikasi()->where('is_read', false)->count();
                }
            @endphp

            <div class="flex items-center gap-4">
                {{-- Desktop Menu --}}
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-[#434655] dark:text-slate-300">
                    @auth
                        @if(!Auth::user()->isAdmin())
                            <a href="/lapangan" class="hover:text-[#335495] dark:hover:text-[#60a5fa] transition-colors">Lapangan</a>
                            <a href="/pemesanan" class="hover:text-[#335495] dark:hover:text-[#60a5fa] transition-colors">Pemesanan</a>
                            <a href="/review" class="hover:text-[#335495] dark:hover:text-[#60a5fa] transition-colors">Review</a>
                            <a href="/profile" class="hover:text-[#335495] dark:hover:text-[#60a5fa] transition-colors">Profil</a>
                        @endif
                    @endauth
                </nav>

                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="hidden md:block border border-red-200 dark:border-red-900 text-red-600 dark:text-red-400 px-4 py-2 rounded-xl text-xs font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                        Logout
                    </button>
                </form>

                <button id="mobile-menu-btn" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-slate-700 text-[#434655] dark:text-white">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT - DIBUAT FULL WIDTH --}}
    <main class="flex-grow w-full px-6 pt-32 pb-12">
        @yield('content')
    </main>

    {{-- FOOTER - DARK MODE SUPPORT --}}
    <footer class="w-full bg-[#131b2e] dark:bg-[#0f172a] text-white/80 mt-auto border-t border-white/10 dark:border-white/5 transition-colors duration-300">
    
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-3 text-white font-['Plus_Jakarta_Sans'] font-extrabold text-base">
                    <span>🏟️</span> Sports Field
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-400 leading-relaxed">
                    Sistem booking lapangan olahraga terlengkap, cepat, dan terpercaya. Solusi terbaik untuk rutinitas olahraga Anda.
                </p>
            </div>

            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 font-['Plus_Jakarta_Sans']">Menu</h4>
                <ul class="space-y-2 text-xs text-slate-300 dark:text-slate-400">
                    <li><a href="/" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="/lapangan" class="hover:text-white transition-colors">Lihat Lapangan</a></li>
                    <li><a href="/pemesanan/create" class="hover:text-white transition-colors">Booking Sekarang</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 font-['Plus_Jakarta_Sans']">Layanan</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li>Badminton Premium</li>
                    <li>Fasilitas Standar BWF</li>
                    <li>Konfirmasi Otomatis</li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 font-['Plus_Jakarta_Sans']">Hubungi Kami</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">mail</span> info@sportsfield.com
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">call</span> +62 812-3456-7890
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">location_on</span> Semarang, Central Java
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-white/10 dark:border-white/5 mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} Sports Field Booking System. All rights reserved.
            </p>
            <div class="flex gap-4 text-xs text-slate-500">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        // Tambahkan script menu mobile jika dibutuhkan
    </script>
</body>
</html>