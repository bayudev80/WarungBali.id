<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warung;
use App\Models\Kabupaten;
use App\Models\Kategori;

class WarungController extends Controller
{
    public function index()
    {
        $warung = Warung::with(['kategori', 'kabupaten'])
            ->latest()
            ->paginate(10);

        return view('admin.warung.index', compact('warung'));
    }
}