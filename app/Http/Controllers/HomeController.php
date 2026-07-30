<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\Kategori;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Bangun query warung yang sudah difilter (search/kategori) dan diurutkan.
     * Dipakai bareng oleh index() (load halaman awal) dan searchAjax()
     * (pencarian tanpa reload), supaya logikanya tidak dobel/ketinggalan sinkron.
     */
    private function buildWarungQuery(Request $request)
    {
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');
        $urutan = $request->input('urutan', 'populer');

        $query = Warung::with([
            'menu',
            'review.user',
            'favorit',
            'kategori'
        ])
        ->where('status', 'approved')
        ->withCount([
            'favorit as favorit_count',
            'review as review_count',
        ])
        ->withAvg('review', 'rating')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('nama_warung', 'like', "%{$search}%")
                   ->orWhere('alamat', 'like', "%{$search}%")
                   ->orWhere('deskripsi', 'like', "%{$search}%")
                   ->orWhereHas('kategori', function ($kq) use ($search) {
                        $kq->where('nama_kategori', 'like', "%{$search}%");
                   });
            });
        })
        ->when($kategoriFilter, function ($q) use ($kategoriFilter) {
            $q->where('id_kategori', $kategoriFilter);
        });

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

        return [$query, $urutan];
    }

    public function index(Request $request)
    {
        [$query, $urutan] = $this->buildWarungQuery($request);

        $warungPilihan = $query->get();

        // Semua kategori (untuk bagian Kategori Populer)
        $kategori = Kategori::orderBy('nama_kategori')->get();

        $totalWarung = Warung::where('status', 'approved')->count();
        $totalUlasan = Review::count();
        $totalKabupaten = \App\Models\Kabupaten::count();
        $totalPengunjungBulanIni = \App\Models\PageVisit::countThisMonth();

        return view('home', compact(
            'kategori',
            'warungPilihan',
            'totalWarung',
            'totalUlasan',
            'totalKabupaten',
            'totalPengunjungBulanIni',
            'urutan'
        ));
    }

    /**
     * Endpoint AJAX: dipanggil dari JS saat user mencari/mengurutkan/memfilter
     * kategori, supaya hasilnya update tanpa reload halaman.
     * Mengembalikan HTML hasil render partial (bukan JSON data mentah),
     * supaya markup kartu warung & slider tetap sama persis dengan
     * halaman utama (tidak perlu duplikasi logic render di JS).
     */
    public function searchAjax(Request $request)
    {
        [$query, $urutan] = $this->buildWarungQuery($request);

        $warungPilihan = $query->get();

        $html = view('partials.warung-results', compact('warungPilihan', 'urutan'))->render();

        return response()->json([
            'html'  => $html,
            'total' => $warungPilihan->count(),
        ]);
    }

    public function tentang()
    {
        $tim = [
            ['nama' => 'Bayu Putra', 'peran' => 'Pendiri & CEO'],
            ['nama' => 'Deana',      'peran' => 'Kepala Konten'],
            ['nama' => 'Arma',       'peran' => 'Teknologi'],
            ['nama' => 'Yogi',       'peran' => 'Kemitraan'],
        ];

        return view('tentang', compact('tim'));
    }

}