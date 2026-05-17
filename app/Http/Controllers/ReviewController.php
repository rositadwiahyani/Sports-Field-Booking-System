<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with('jadwal.lapangan', 'review')
            ->where('user_id', Auth::id())
            ->where('status_pemesanan', 'dibayar')
            ->get();

        return view('review.index', compact('pemesanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'rating'       => 'required|integer|min:1|max:5',
            'komentar'     => 'nullable|string|max:500',
        ]);

        $pemesanan = Pemesanan::with('jadwal.lapangan')->findOrFail($request->pemesanan_id);

        if ($pemesanan->status_pemesanan !== 'dibayar') {
            return back()->with('error', 'Hanya pemesanan yang sudah dibayar yang bisa direview.');
        }

        $sudahReview = Review::where('pemesanan_id', $request->pemesanan_id)->exists();
        if ($sudahReview) {
            return back()->with('error', 'Kamu sudah pernah memberikan review untuk pemesanan ini.');
        }

        // Ambil lapangan_id dengan aman
        $lapanganId = $pemesanan->jadwal?->lapangan?->id ?? null;

        if (!$lapanganId) {
            return back()->with('error', 'Data lapangan tidak ditemukan.');
        }

        Review::create([
            'user_id'      => Auth::id(),
            'lapangan_id'  => $lapanganId,
            'pemesanan_id' => $pemesanan->id,
            'rating'       => $request->rating,
            'komentar'     => $request->komentar,
        ]);

        return redirect('/review')->with('success', 'Review berhasil dikirim! Terima kasih.');
    }
}