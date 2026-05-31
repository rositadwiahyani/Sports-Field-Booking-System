<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sports Field Booking System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-nav {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }
        .gradient-btn {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }
        .gradient-hero {
            background: linear-gradient(135deg, #0f1f5c 0%, #1e3a8a 50%, #3b82f6 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- NAVBAR --}}
    <nav class="gradient-nav text-white px-6 py-4 flex items-center justify-between shadow-lg relative z-50">
        <a href="/" class="text-xl md:text-xl font-bold tracking-wide flex items-center gap-2">
            <span>Sports Field Booking System</span>
        </a>

        @php
            $unreadNotif = 0;
            if (Auth::check()) {
                $unreadNotif = Auth::user()->notifikasi()->where('is_read', false)->count();
            }
        @endphp

        {{-- Hamburger Menu Button (Mobile) --}}
        <button id="mobile-menu-btn" class="md:hidden block text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex gap-6 text-sm font-medium items-center">
            @auth
                <a href="{{ Auth::user()->isAdmin() ? '/admin/dashboard' : '/' }}" class="hover:text-blue-200 transition">Beranda</a>
                @if(Auth::user()->isAdmin())
                    <a href="/lapangan" class="hover:text-blue-200 transition">Lapangan</a>
                    <a href="/admin/dashboard" class="hover:text-blue-200 transition">Dashboard</a>
                    <a href="/notifikasi" class="hover:text-blue-200 transition relative flex items-center">
                        Notifikasi
                        @if($unreadNotif > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                {{ $unreadNotif }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="/lapangan" class="hover:text-blue-200 transition">Lapangan</a>
                    <a href="/pemesanan" class="hover:text-blue-200 transition">Pemesanan</a>
                    <a href="/review" class="hover:text-blue-200 transition">Review</a>
                    <a href="/notifikasi" class="hover:text-blue-200 transition relative flex items-center">
                        Notifikasi
                        @if($unreadNotif > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                {{ $unreadNotif }}
                            </span>
                        @endif
                    </a>
                    <a href="/profile" class="hover:text-blue-200 transition">Profil</a>
                @endif
                <form action="/logout" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="bg-white text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold hover:bg-blue-50 transition">Logout</button>
                </form>
            @else
                <a href="/login" class="hover:text-blue-200 transition">Login</a>
                <a href="/register" class="bg-white text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold hover:bg-blue-50 transition">Daftar</a>
            @endauth
        </div>

        {{-- Mobile Dropdown Menu --}}
        <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-blue-800 shadow-md md:hidden flex flex-col items-center py-4 gap-4">
            @auth
                <a href="{{ Auth::user()->isAdmin() ? '/admin/dashboard' : '/' }}" class="hover:text-blue-200 transition">Beranda</a>
                @if(Auth::user()->isAdmin())
                    <a href="/lapangan" class="hover:text-blue-200 transition">Lapangan</a>
                    <a href="/admin/dashboard" class="hover:text-blue-200 transition">Dashboard</a>
                    <a href="/notifikasi" class="hover:text-blue-200 transition flex items-center gap-1">
                        Notifikasi
                        @if($unreadNotif > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full border border-white">
                                {{ $unreadNotif }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="/lapangan" class="hover:text-blue-200 transition">Lapangan</a>
                    <a href="/pemesanan" class="hover:text-blue-200 transition">Pemesanan</a>
                    <a href="/review" class="hover:text-blue-200 transition">Review</a>
                    <a href="/notifikasi" class="hover:text-blue-200 transition flex items-center gap-1">
                        Notifikasi
                        @if($unreadNotif > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full border border-white">
                                {{ $unreadNotif }}
                            </span>
                        @endif
                    </a>
                    <a href="/profile" class="hover:text-blue-200 transition">Profil</a>
                @endif
                <form action="/logout" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">Logout</button>
                </form>
            @else
                <a href="/login" class="hover:text-blue-200 transition">Login</a>
                <a href="/register" class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">Daftar</a>
            @endauth
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="max-w-6xl mx-auto px-6 py-8 pb-20">

        @if(session('success'))
            <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    {{-- FOOTER --}}
    <footer class="fixed bottom-0 left-0 w-full text-center text-gray-400 text-sm py-4 border-t border-gray-200 bg-white/90 backdrop-blur-sm z-40">
        © 2026 <span class="text-blue-600 font-semibold">Sports Field Booking System</span> — Sistem Booking Lapangan Olahraga
    </footer>

    <script>
        // Toggle Mobile Menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>

