<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    /**
     * Halaman "Favorit Saya": daftar warung yang sudah disukai
     * oleh user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        $warungFavorit = \App\Models\Warung::with([
                'menu',
                'review.user',
                'favorit',
                'kategori',
                'kabupaten'
            ])
            ->whereHas('favorit', function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->get();

        return view('favorit.index', compact('warungFavorit'));
    }

    public function toggle($id)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Harus login'
        ], 401);
    }

    if (!\App\Models\Warung::where('id_warung', $id)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Warung tidak ditemukan'
        ], 404);
    }

    $favorit = Favorit::where('id_user', $user->id_user)
        ->where('id_warung', $id)
        ->first();

    if ($favorit) {

        $favorit->delete();

        return response()->json([
            'success' => true,
            'favorit' => false
        ]);
    }

    Favorit::create([
        'id_user' => $user->id_user,
        'id_warung' => $id,
    ]);

    return response()->json([
        'success' => true,
        'favorit' => true
    ]);
}
}