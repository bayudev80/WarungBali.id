<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisteredPemilikController extends Controller
{
    /**
     * Form pendaftaran pemilik warung: data akun + data warung digabung
     * jadi satu form. Sengaja TIDAK butuh login untuk mengisinya (dan
     * memang tidak bisa -- akunnya belum ada), beda dengan form "Tambah
     * Warung" di dashboard pemilik yang mengasumsikan sudah login.
     */
    public function create(): View
    {
        $kategori  = Kategori::orderBy('nama_kategori')->get();
        $kabupaten = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('auth.register-pemilik', compact('kategori', 'kabupaten'));
    }

    /**
     * Simpan pendaftaran akun pemilik warung berstatus pending.
     * Setelah akun diverifikasi oleh admin, password login akan dikirimkan ke email.
     * Setelah pemilik login dengan password tersebut, barulah pemilik diarahkan
     * untuk mengisi formulir pendaftaran data warungnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
        ]);

        User::create([
            'nama'        => $request->nama,
            'email'       => $request->email,
            'password'    => Hash::make(Str::random(40)),
            'role'        => 'pemilik',
            'status_akun' => 'pending',
        ]);

        return redirect()->route('login')
            ->with('status', 'Pendaftaran akun pemilik warung berhasil dikirim! Akun Anda sedang ditinjau oleh admin. Password login resmi akan dikirimkan ke email Anda (' . $request->email . ') setelah akun diverifikasi.');
    }
}
