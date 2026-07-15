<?php

namespace App\Http\Controllers;

use App\Models\Warung;

class HomeController extends Controller
{
 public function index()
{
    $search = request('search');

    $warung = Warung::with([
    'menu',
    'review.user',
    'favorit'
])

        ->when($search, function($query) use ($search){

            $query->where('nama_warung','like',"%{$search}%")
                  ->orWhere('alamat','like',"%{$search}%")
                  ->orWhere('deskripsi','like',"%{$search}%");

        })

        ->get();

    return view('home', compact('warung'));
}
}