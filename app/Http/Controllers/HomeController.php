<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $warung = DB::table('warung')->get();

        return view('home', compact('warung'));
    }
}