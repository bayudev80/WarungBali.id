<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorit;

class FavoritController extends Controller
{
    public function index()
    {
        $favorit = Favorit::with(['user', 'warung'])
            ->latest('id_favorit')
            ->get();

        return view('admin.favorit.index', compact('favorit'));
    }

    public function destroy($id)
    {
        $favorit = Favorit::findOrFail($id);
        $favorit->delete();

        return redirect()->route('admin.favorit.index')
            ->with('success', 'Favorit berhasil dihapus.');
    }
}
