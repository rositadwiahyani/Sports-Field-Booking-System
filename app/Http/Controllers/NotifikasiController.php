<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::with('pemesanan')
            ->where('user_id', Auth::id() ?? 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markRead($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->is_read = true;
        $notif->save();

        return redirect('/notifikasi');
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id() ?? 1)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect('/notifikasi')->with('success', 'Semua notifikasi sudah dibaca.');
    }
}