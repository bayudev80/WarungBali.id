<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Warung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan baru, atau update ulasan lama kalau user yang sama
     * sudah pernah kasih ulasan untuk warung ini sebelumnya.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();

        // Route ini sudah digerbang middleware 'auth', jadi kondisi ini
        // seharusnya tidak pernah kena. Tetap dijaga untuk keamanan ekstra
        // (misal dipanggil langsung via API/testing).
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu untuk memberi ulasan.',
            ], 401);
        }

        $warung = Warung::findOrFail($id);

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $review = Review::updateOrCreate(
            [
                'id_user'   => $user->id_user,
                'id_warung' => $warung->id_warung,
            ],
            [
                'rating'     => $request->rating,
                'komentar'   => $request->komentar,
                'created_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'review'  => [
                'id_review' => $review->id_review,
                'id_user'   => $user->id_user,
                'nama'      => $user->nama,
                'rating'    => $review->rating,
                'komentar'  => $review->komentar,
                'tanggal'   => $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d M Y') : now()->format('d M Y'),
            ],
            'average_rating' => round($warung->review()->avg('rating'), 1),
            'total_review'   => $warung->review()->count(),
        ]);
    }
}
