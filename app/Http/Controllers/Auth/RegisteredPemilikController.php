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
     * Simpan akun + warung sekaligus, keduanya berstatus pending. Tidak ada
     * Auth::login() di sini -- akun baru bisa dipakai login setelah admin
     * memverifikasinya lewat menu "Kelola Akun Pemilik" (lihat
     * Admin\PemilikAkunController), yang saat itu baru men-generate dan
     * mengirim password lewat email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Data akun
            'nama'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],

            // Data warung
            'nama_warung'  => 'required|max:150',
            'id_kategori'  => 'required|exists:kategori,id_kategori',
            'id_kabupaten' => 'required|exists:kabupaten,id_kabupaten',
            'alamat'       => 'required',
            'deskripsi'    => 'nullable|string',
            'telepon'      => 'nullable|max:20',
            'jam_buka'     => 'nullable',
            'jam_tutup'    => 'nullable',
            'harga_min'    => 'nullable|integer|min:0',
            'harga_max'    => 'nullable|integer|min:0',
            'foto'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
            'menerima_catering' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            // Password diisi acak & TIDAK diberitahukan ke siapa pun -- akun
            // ini memang belum bisa dipakai login sampai diverifikasi admin
            // dan diberi password asli lewat email.
            $user = User::create([
                'nama'        => $request->nama,
                'email'       => $request->email,
                'password'    => Hash::make(Str::random(40)),
                'role'        => 'pemilik',
                'status_akun' => 'pending',
            ]);

            $data = $request->only([
                'nama_warung', 'id_kategori', 'id_kabupaten', 'alamat',
                'deskripsi', 'telepon', 'jam_buka', 'jam_tutup',
                'harga_min', 'harga_max',
            ]);
            $data['id_user'] = $user->id_user;
            $data['status']  = 'pending';
            $data['menerima_catering'] = $request->boolean('menerima_catering');

            if ($request->hasFile('foto')) {
                $filename = time().'_'.Str::random(12).'.'.$request->file('foto')->getClientOriginalExtension();
                $request->file('foto')->move(public_path('images/warung'), $filename);
                $data['foto'] = $filename;
            }

            Warung::create($data);
        });

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil dikirim! Akun dan warung Anda akan ditinjau oleh admin. Anda akan menerima email berisi password login setelah akun diverifikasi.');
    }
}
