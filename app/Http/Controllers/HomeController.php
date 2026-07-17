<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\Kategori;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $search = request('search');
        $kategoriFilter = request('kategori');

        // Data warung
        $warung = Warung::with([
            'menu',
            'review.user',
            'favorit',
            'kategori'
        ])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_warung', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        })
        ->when($kategoriFilter, function ($query) use ($kategoriFilter) {
            $query->where('id_kategori', $kategoriFilter);
        })
        ->inRandomOrder()
        ->get();

        // Semua kategori (untuk bagian Kategori Populer)
        $kategori = Kategori::orderBy('nama_kategori')->get();


        // Warung Populer (acak)
        $warungPilihan = Warung::with([
            'menu',
            'review.user',
            'favorit',
            'kategori'
        ])
        ->inRandomOrder()
        ->take(6)
        ->get();

        $totalWarung = Warung::count();
        $totalUlasan = 5400;
        $totalKabupaten = 9;

        return view('home', compact(
            'warung',
            'kategori',
            'warungPilihan',
            'totalWarung',
            'totalUlasan',
            'totalKabupaten'
        ));
    }
    
}