<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    public function toggle($id)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Harus login'
        ], 401);
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