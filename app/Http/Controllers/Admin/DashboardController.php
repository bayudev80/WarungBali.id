<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Kategori;
use App\Models\PageVisit;
use App\Models\Review;
use App\Models\User;
use App\Models\Warung;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $jumlahWarung        = Warung::count();
        $jumlahUser          = User::count();
        $jumlahReview        = Review::count();
        $jumlahKategori      = Kategori::count();
        $jumlahFavorit       = Favorit::count();
        $jumlahWarungPending = Warung::where('status', 'pending')->count();

        // Breakdown status warung, dipakai untuk progress bar approved/pending/rejected
        $jumlahWarungApproved = Warung::where('status', 'approved')->count();
        $jumlahWarungRejected = Warung::where('status', 'rejected')->count();

        // Breakdown role pengguna
        $jumlahAdmin   = User::where('role', 'admin')->count();
        $jumlahPemilik = User::where('role', 'pemilik')->count();
        $jumlahUmum    = $jumlahUser - $jumlahAdmin - $jumlahPemilik;

        // Distribusi warung per kategori (untuk chart)
        $kategoriChart = Kategori::withCount('warung')
            ->orderByDesc('warung_count')
            ->get();

        // Pengunjung bulan ini
        $jumlahPengunjungBulanIni = PageVisit::countThisMonth();

        // Aktivitas terbaru
        $warungTerbaru = Warung::with(['kategori', 'kabupaten'])
            ->orderByDesc('id_warung')
            ->take(5)
            ->get();

        $reviewTerbaru = Review::with(['user', 'warung'])
            ->orderByDesc('id_review')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'jumlahWarung'             => $jumlahWarung,
            'jumlahUser'               => $jumlahUser,
            'jumlahReview'             => $jumlahReview,
            'jumlahKategori'           => $jumlahKategori,
            'jumlahFavorit'            => $jumlahFavorit,
            'jumlahWarungPending'      => $jumlahWarungPending,
            'jumlahWarungApproved'     => $jumlahWarungApproved,
            'jumlahWarungRejected'     => $jumlahWarungRejected,
            'jumlahAdmin'              => $jumlahAdmin,
            'jumlahPemilik'            => $jumlahPemilik,
            'jumlahUmum'               => max($jumlahUmum, 0),
            'kategoriChart'            => $kategoriChart,
            'jumlahPengunjungBulanIni' => $jumlahPengunjungBulanIni,
            'warungTerbaru'            => $warungTerbaru,
            'reviewTerbaru'            => $reviewTerbaru,
        ]);
    }
}