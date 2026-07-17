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
        $urutan = request('urutan', 'populer');

        // Data warung (sudah difilter search & kategori, + siap diurutkan)
        $query = Warung::with([
            'menu',
            'review.user',
            'favorit',
            'kategori'
        ])
        ->withCount([
            'favorit as favorit_count',
            'review as review_count',
        ])
        ->withAvg('review', 'rating')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('nama_warung', 'like', "%{$search}%")
                   ->orWhere('alamat', 'like', "%{$search}%")
                   ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        })
        ->when($kategoriFilter, function ($q) use ($kategoriFilter) {
            $q->where('id_kategori', $kategoriFilter);
        });

        // Urutan / filter pilihan
        switch ($urutan) {
            case 'disukai':
                $query->orderByDesc('favorit_count');
                break;

            case 'rating':
                $query->orderByDesc('review_avg_rating')
                      ->orderByDesc('review_count');
                break;

            case 'terbaru':
                // Tabel warung tidak punya created_at, jadi id_warung terbesar
                // (auto-increment) dipakai sebagai penanda data paling baru.
                $query->orderByDesc('id_warung');
                break;

            case 'termurah':
                $query->orderBy('harga_min');
                break;

            case 'termahal':
                $query->orderByDesc('harga_max');
                break;

            case 'populer':
            default:
                $urutan = 'populer';
                // Tetap prioritaskan yang benar-benar populer (banyak ulasan/favorit),
                // tapi di antara warung yang levelnya setara, urutannya diacak
                // supaya tampilan berubah-ubah tiap kali halaman dibuka.
                $query->orderByDesc('review_count')
                      ->orderByDesc('favorit_count')
                      ->inRandomOrder();
                break;
        }

        $warungPilihan = $query->get();

        // Semua kategori (untuk bagian Kategori Populer)
        $kategori = Kategori::orderBy('nama_kategori')->get();

        $totalWarung = Warung::count();
        $totalUlasan = 5400;
        $totalKabupaten = 9;

        return view('home', compact(
            'kategori',
            'warungPilihan',
            'totalWarung',
            'totalUlasan',
            'totalKabupaten',
            'urutan'
        ));
    }

}