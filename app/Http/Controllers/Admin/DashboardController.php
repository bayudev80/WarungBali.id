<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\User;
use App\Models\Warung;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'jumlahWarung'   => Warung::count(),
            'jumlahUser'     => User::count(),
            'jumlahReview'   => Review::count(),
            'jumlahKategori' => Kategori::count(),
            'jumlahFavorit'  => Favorit::count(),
        ]);
    }
}