<?php

namespace App\Http\Controllers;

use App\Models\Warung;

class HomeController extends Controller
{
    public function index()
    {
        $warung = Warung::with('menu')->get();

        return view('home', compact('warung'));
    }
}