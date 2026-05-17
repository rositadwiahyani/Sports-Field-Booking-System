@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">👤 Profil Saya</h1>

    {{-- Info Profil --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full gradient-hero flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-semibold mt-1 inline-block">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        {{-- Form Update Profil --}}
        <form action="/profile/update" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>
            <button type="submit"
                class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Ubah Password --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">🔐 Ubah Password</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/profile/password" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <div class="relative">
                    <input type="password" name="password_lama" id="password_lama"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10">
                    <button type="button" onclick="togglePassword('password_lama', 'eye1')"
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                        <span id="eye1">👁️</span>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_baru" id="password_baru"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10">
                    <button type="button" onclick="togglePassword('password_baru', 'eye2')"
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                        <span id="eye2">👁️</span>
                    </button>
                </div>
                <ul class="text-xs text-gray-400 mt-2 space-y-1 list-disc list-inside">
                    <li>Minimal 8 karakter</li>
                    <li>Mengandung huruf besar (A-Z)</li>
                    <li>Mengandung huruf kecil (a-z)</li>
                    <li>Mengandung angka atau karakter spesial (!@#$%)</li>
                </ul>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_baru_confirmation" id="password_confirm"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10">
                    <button type="button" onclick="togglePassword('password_confirm', 'eye3')"
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                        <span id="eye3">👁️</span>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="gradient-btn text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                Ubah Password
            </button>
        </form>
    </div>

    {{-- Hapus Akun --}}
    <div class="bg-white rounded-xl shadow p-6 mt-6 border border-red-200">
        <h2 class="text-lg font-bold text-red-600 mb-2">⚠️ Hapus Akun</h2>
        <p class="text-gray-500 text-sm mb-4">
            Akun yang dihapus tidak bisa dipulihkan. Semua data pemesanan dan review akan ikut terhapus.
        </p>

        <form action="/profile/delete" method="POST" id="form-hapus-akun">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Masukkan password untuk konfirmasi
                </label>
                <div class="relative">
                    <input type="password" name="password_konfirmasi" id="password_del"
                        class="w-full border border-red-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 pr-10">
                    <button type="button" onclick="togglePassword('password_del', 'eye_del')"
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                        <span id="eye_del">👁️</span>
                    </button>
                </div>
            </div>

            <button type="button"
                onclick="document.getElementById('modal-hapus-akun').classList.remove('hidden')"
                class="bg-red-500 text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                🗑️ Hapus Akun Saya
            </button>
        </form>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS AKUN --}}
<div id="modal-hapus-akun" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="text-center mb-4">
            <span class="text-5xl">⚠️</span>
            <h3 class="text-xl font-bold text-gray-800 mt-3">Hapus Akun?</h3>
            <p class="text-gray-500 text-sm mt-2">
                Tindakan ini <strong>tidak bisa dibatalkan</strong>. Semua data pemesanan dan review akan ikut terhapus permanen.
            </p>
        </div>
        <div class="flex gap-3 justify-center mt-6">
            <button type="button"
                onclick="document.getElementById('modal-hapus-akun').classList.add('hidden')"
                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 transition">
                Batal
            </button>
            <button type="submit" form="form-hapus-akun"
                class="bg-red-500 text-white px-6 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                Ya, Hapus Akun
            </button>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, eyeId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(eyeId);
    if (field.type === 'password') {
        field.type = 'text';
        eye.textContent = '🙈';
    } else {
        field.type = 'password';
        eye.textContent = '👁️';
    }
}
</script>

@endsection
