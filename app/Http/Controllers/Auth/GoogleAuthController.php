<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman autentikasi Google OAuth.
     */
    public function redirect(): RedirectResponse
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Layanan login Google belum dikonfigurasi dengan Client ID & Secret di file .env.']);
        }
    }

    /**
     * Menangani callback autentikasi dari Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal melakukan login dengan Google: ' . $e->getMessage()]);
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal mendapatkan data email dari akun Google Anda.']);
        }

        // Cari pengguna berdasarkan google_id atau alamat email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Jika akun pemilik masih berstatus pending, tahan login
            if ($user->status_akun === 'pending') {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda masih menunggu verifikasi admin. Anda akan menerima email setelah akun diverifikasi.']);
            }

            // Tautkan google_id jika sebelumnya belum tersimpan
            $updateData = [];
            if (empty($user->google_id)) {
                $updateData['google_id'] = $googleUser->getId();
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            Auth::login($user, remember: true);
        } else {
            // Buat pengguna baru jika belum terdaftar
            $user = User::create([
                'nama'        => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                'email'       => $googleUser->getEmail(),
                'google_id'   => $googleUser->getId(),
                'password'    => Hash::make(Str::random(32)),
                'role'        => 'user',
                'status_akun' => 'verified',
                'foto'        => $googleUser->getAvatar(),
            ]);

            Auth::login($user, remember: true);
        }

        request()->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'pemilik') {
            return redirect()->intended(route('pemilik.dashboard'));
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang, ' . $user->nama . '!');
    }
}
