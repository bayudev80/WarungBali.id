<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $warung = auth()->user()->warung()->with(['kategori', 'kabupaten', 'menu'])->first();

        // Belum pernah isi data warung -> arahkan ke form tambah warung
        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        return view('pemilik.dashboard', compact('warung'));
    }
}
