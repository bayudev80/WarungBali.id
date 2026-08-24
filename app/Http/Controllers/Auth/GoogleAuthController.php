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
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google Client ID atau Client Secret belum diisi di file .env. Silakan isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET terlebih dahulu.']);
        }

        try {
            $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');

            return Socialite::driver('google')
                ->redirectUrl($redirectUrl)
                ->with(['prompt' => 'select_account'])
                ->redirect();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal menghubungkan ke layanan Google: ' . $e->getMessage()]);
        }
    }

    /**
     * Menangani callback autentikasi dari Google.
     */
    public function callback(): RedirectResponse
    {
        $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');

        try {
            // Coba ambil user dengan verifikasi state session
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUrl)
                ->user();
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Fallback stateless jika state session terputus/mismatch antar domain
            try {
                $googleUser = Socialite::driver('google')
                    ->redirectUrl($redirectUrl)
                    ->stateless()
                    ->user();
            } catch (\Throwable $subException) {
                \Illuminate\Support\Facades\Log::error('Google Auth Stateless Fallback Error: ' . $subException->getMessage());
                return redirect()->route('login')
                    ->withErrors(['email' => 'Gagal melakukan login dengan Google: ' . $subException->getMessage()]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Callback Error: ' . $e->getMessage());
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
            if ($user->status_akun === 'pending' && $user->role === 'pemilik') {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Akun pemilik warung Anda masih menunggu verifikasi admin. Anda akan menerima email berisi password login setelah akun diverifikasi.']);
            }

            // Tautkan google_id dan verifikasi email jika sebelumnya belum tersimpan
            $updateData = [];
            if (empty($user->google_id)) {
                $updateData['google_id'] = $googleUser->getId();
            }
            if (empty($user->email_verified_at)) {
                $updateData['email_verified_at'] = now();
            }
            if (!empty($updateData)) {
                $user->update($updateData);
            }

            Auth::login($user);
        } else {
            // Pengguna baru dari Google langsung aktif (verified) & langsung login
            $user = User::create([
                'nama'              => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'password'          => Hash::make(Str::random(32)),
                'role'              => 'user',
                'status_akun'       => 'verified',
                'email_verified_at' => now(),
                'foto'              => $googleUser->getAvatar() ? substr($googleUser->getAvatar(), 0, 255) : null,
            ]);

            Auth::login($user);
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
