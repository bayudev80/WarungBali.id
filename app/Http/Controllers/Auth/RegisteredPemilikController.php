<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredPemilikController extends Controller
{
    /**
     * Tampilkan form daftar akun pemilik warung (tahap 1).
     */
    public function create(): View
    {
        return view('auth.register-pemilik');
    }

    /**
     * Simpan akun pemilik warung, lalu arahkan ke form Tambah Warung (tahap 2).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pemilik',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('pemilik.warung.create')
            ->with('success', 'Akun berhasil dibuat! Sekarang lengkapi data warung Anda.');
    }
}
