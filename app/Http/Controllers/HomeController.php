<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\Kabupaten;
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
        $kabupatenFilter = $request->input('kabupaten');
        $urutan = $request->input('urutan', 'populer');

        $query = Warung::with([
            'menu',
            'review.user',
            'favorit',
            'kategori',
            'kabupaten',
            'cabang',
            'indukWarung.menu',
        ])
        ->where('status', 'approved')
        ->withCount([
            'favorit as favorit_count',
            'review as review_count',
        ])
        ->withAvg('review', 'rating')
        ->when($search, function ($q) use ($search) {
            // Bersihkan kata hubung agar pencarian lebih fokus ke inti kata
            $stopWords = ['di', 'ke', 'dari', 'yang', 'dan', 'atau', 'pada', 'daerah', 'wilayah', 'kabupaten', 'kota'];
            $keywords = array_filter(explode(' ', strtolower(trim($search))), function($word) use ($stopWords) {
                return !in_array($word, $stopWords) && strlen($word) > 1;
            });

            // Jika kosong setelah dibersihkan, gunakan pencarian aslinya
            if (empty($keywords)) {
                $keywords = [$search];
            }

            // Setiap kata kunci harus cocok (AND) di salah satu kolom (OR)
            foreach ($keywords as $word) {
                $q->where(function ($qq) use ($word) {
                    $qq->where('nama_warung', 'like', "%{$word}%")
                       ->orWhere('alamat', 'like', "%{$word}%")
                       ->orWhere('deskripsi', 'like', "%{$word}%")
                       ->orWhereHas('kategori', function ($kq) use ($word) {
                            $kq->where('nama_kategori', 'like', "%{$word}%");
                       })
                       ->orWhereHas('kabupaten', function ($kabq) use ($word) {
                            $kabq->where('nama_kabupaten', 'like', "%{$word}%");
                       });
                });
            }
        })
        ->when($kategoriFilter, function ($q) use ($kategoriFilter) {
            $q->where('id_kategori', $kategoriFilter);
        })
        // Filter kabupaten pakai relasi id_kabupaten yang sudah ada di tabel
        // warung, bukan tebak-tebakan lewat LIKE ke kolom alamat bebas teks.
        // Jadi hasilnya presisi walau format penulisan alamat beda-beda.
        ->when($kabupatenFilter, function ($q) use ($kabupatenFilter) {
            $q->where('id_kabupaten', $kabupatenFilter);
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

    /**
     * Ikon per kategori & label dropdown urutan dipakai bareng oleh index()
     * dan searchAjax(), supaya partial hasil (warung-results) selalu dapat
     * variabel yang sama persis baik saat load halaman penuh maupun AJAX.
     */
    private function iconMap(): array
    {
        return [
            'Warung Makan' => 'bi-shop-window',
            'Warung Minuman' => 'bi-cup-straw',
            'Warung Sembako' => 'bi-basket2-fill',
            'Oleh-Oleh Bali' => 'bi-gift-fill',
            'Warung Buah & Sayur' => 'bi-flower1',
            'Warung Herbal' => 'bi-flower2',
            'Warung Pulsa & PPOB' => 'bi-phone-fill',
            'Warung ATK & Fotokopi' => 'bi-printer-fill',
        ];
    }

    private function urutanOptions(): array
    {
        return [
            'populer'  => ['label' => 'Terpopuler',       'icon' => 'bi-fire'],
            'disukai'  => ['label' => 'Banyak Disukai',   'icon' => 'bi-heart-fill'],
            'rating'   => ['label' => 'Rating Tertinggi', 'icon' => 'bi-star-fill'],
            'terbaru'  => ['label' => 'Terbaru',          'icon' => 'bi-lightning-charge-fill'],
            'termurah' => ['label' => 'Harga Termurah',   'icon' => 'bi-cash-coin'],
            'termahal' => ['label' => 'Harga Termahal',   'icon' => 'bi-gem'],
        ];
    }

    /**
     * Data hasil (warungPilihan, sedangFilter, kabupatenAktif) dipakai bareng
     * oleh index() (render halaman penuh) dan searchAjax() (render partial
     * saja), supaya kedua mode selalu menampilkan hasil yang identik.
     */
    private function buildHasil(Request $request): array
    {
        [$query, $urutan] = $this->buildWarungQuery($request);

        // Mode "hasil pencarian/filter" (search dan/atau kabupaten dipilih)
        // ditampilkan sebagai grid rapi dengan pagination, beda dari mode
        // "jelajahi" default di homepage yang dikelompokkan per kategori
        // dalam slider horizontal. Kalau semuanya di-load sekaligus tanpa
        // pagination, kabupaten yang warungnya banyak bikin halaman berat.
        $sedangFilter = (bool) ($request->filled('search') || $request->filled('kabupaten'));

        $warungPilihan = $sedangFilter
            ? $query->paginate(12)->withQueryString()
            : $query->get();

        $kabupatenList = \App\Models\Kabupaten::orderBy('nama_kabupaten')->get();
        $kabupatenAktif = $kabupatenList->firstWhere('id_kabupaten', (int) $request->input('kabupaten'));

        return compact('warungPilihan', 'sedangFilter', 'kabupatenAktif', 'urutan', 'kabupatenList');
    }

    public function index(Request $request)
    {
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $this->searchAjax($request);
        }
        $hasil = $this->buildHasil($request);

        // Semua kategori (untuk bagian Kategori Populer)
        $kategori = Kategori::orderBy('nama_kategori')->get();

        $totalWarung = Warung::where('status', 'approved')->count();
        $totalUlasan = Review::count();
        $totalKabupaten = \App\Models\Kabupaten::count();
        $totalPengunjungBulanIni = \App\Models\PageVisit::countThisMonth();

        return view('home', array_merge($hasil, compact(
            'kategori',
            'totalWarung',
            'totalUlasan',
            'totalKabupaten',
            'totalPengunjungBulanIni'
        ), [
            'urutanOptions' => $this->urutanOptions(),
            'icons' => $this->iconMap(),
        ]));
    }

    /**
     * Halaman khusus per kategori, diakses lewat URL yang enak dibaca,
     * misalnya /kategori/warung-makan (bukan lewat query string ?kategori=1).
     * Isinya sama persis dengan halaman utama, cuma sudah otomatis
     * terfilter ke kategori tersebut.
     */
    public function kategori(Request $request, string $slug)
    {
        $kategori = Kategori::get()
            ->first(fn ($k) => $k->slug === $slug);

        abort_unless($kategori, 404);

        $request->merge(['kategori' => $kategori->id_kategori]);

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return $this->searchAjax($request);
        }

        return $this->index($request);
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
        $hasil = $this->buildHasil($request);

        $html = view('partials.warung-results', array_merge($hasil, [
            'urutanOptions' => $this->urutanOptions(),
            'icons' => $this->iconMap(),
        ]))->render();

        return response()->json([
            'html'  => $html,
            'total' => $hasil['warungPilihan']->count(),
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