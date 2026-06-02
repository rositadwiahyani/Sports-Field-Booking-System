<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sports Field Booking System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

<nav class="gradient-nav text-white px-6 py-4 flex items-center justify-between shadow-lg relative z-50">

    <!-- LOGO -->
    <a href="/" class="text-xl md:text-xl font-bold tracking-wide flex items-center gap-2 hover:opacity-80 cursor-pointer">
        <span>Sports Field Booking System</span>
    </a>

    @php
        $unreadNotif = 0;
        if (Auth::check()) {
            $unreadNotif = Auth::user()->notifikasi()->where('is_read', false)->count();
        }
    @endphp

    <!-- 🔔 + ☰ MOBILE -->
    <div class="flex items-center gap-4 md:hidden">

        <!-- NOTIF -->
        <a href="/notifikasi" class="relative">
            <i class="fas fa-bell text-white text-lg"></i>
            @if($unreadNotif > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full">
                    {{ $unreadNotif }}
                </span>
            @endif
        </a>

        <!-- HAMBURGER -->
        <button id="mobile-menu-btn" class="text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

    </div>

    <!-- DESKTOP -->
    <div class="hidden md:flex gap-6 text-sm font-medium items-center">
        @auth
            @if(Auth::user()->isAdmin())

                <!-- 🔔 NOTIF -->
                <a href="/notifikasi" class="relative flex items-center hover:text-blue-200">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadNotif > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full">
                            {{ $unreadNotif }}
                        </span>
                    @endif
                </a>

            @else
                <a href="/lapangan" class="hover:text-blue-200 transition">Lapangan</a>
                <a href="/pemesanan" class="hover:text-blue-200 transition">Pemesanan</a>
                <a href="/review" class="hover:text-blue-200 transition">Review</a>

                <a href="/profile" class="hover:text-blue-200 transition">Profil</a>

                <a href="/notifikasi" class="relative flex items-center hover:text-blue-200">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadNotif > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full">
                            {{ $unreadNotif }}
                        </span>
                    @endif
                </a>
            @endif

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="bg-white text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold hover:bg-blue-50">
                    Logout
                </button>
            </form>
        @else
            <a href="/login" class="hover:text-blue-200">Login</a>
            <a href="/register" class="bg-white text-blue-700 px-3 py-1 rounded-lg text-xs font-semibold">Daftar</a>
        @endauth
    </div>

    <!-- MOBILE MENU -->
    <div id="mobile-menu" class="hidden absolute top-full left-0 w-full bg-blue-800 md:hidden flex flex-col items-center py-4 gap-4">
        @auth
            @if(Auth::user()->isAdmin())
                <!-- kosong, admin ga perlu menu -->
            @else
                <a href="/lapangan">Lapangan</a>
                <a href="/pemesanan">Pemesanan</a>
                <a href="/review">Review</a>
                <a href="/profile">Profil</a>
            @endif

            <form action="/logout" method="POST">
                @csrf
                <button class="bg-white text-blue-700 px-4 py-2 rounded-lg text-sm">
                    Logout
                </button>
            </form>
        @else
            <a href="/login">Login</a>
            <a href="/register">Daftar</a>
        @endauth
    </div>

</nav>

<main class="max-w-6xl mx-auto px-6 py-8 pb-20">
    @yield('content')
</main>

<script>
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>

</body>
</html>