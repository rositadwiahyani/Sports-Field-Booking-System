<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9\W]/',
            'password_baru_confirmation' => 'required',
        ], [
            'password_baru.required'   => 'Password baru wajib diisi.',
            'password_baru.min'        => 'Password minimal 8 karakter.',
            'password_baru.confirmed'  => 'Konfirmasi password tidak cocok.',
            'password_baru.regex'      => 'Password harus mengandung huruf besar, huruf kecil, dan angka atau karakter spesial.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diupdate!');
    }

    // ================= DELETE ACCOUNT =================
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password_konfirmasi' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_konfirmasi, $user->password)) {
            return back()->with('error', 'Password tidak sesuai!');
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}