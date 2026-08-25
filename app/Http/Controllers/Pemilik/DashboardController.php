<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Mail\UserPasswordBaru;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // Admin tidak punya dashboard pemilik.
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $warung = auth()->user()->warung()->with(['kategori', 'kabupaten', 'menu', 'semuaCabang'])->first();

        // Belum pernah isi data warung -> arahkan ke form tambah warung
        if (!$warung) {
            return redirect()->route('pemilik.warung.create');
        }

        return view('pemilik.dashboard', compact('warung'));
    }

    /**
     * Halaman manajemen Keamanan & Password Akun Pemilik Warung.
     */
    public function password()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();

        return view('pemilik.password', compact('user'));
    }

    /**
     * Ubah password mandiri pemilik warung.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'password' => 'required|min:6|confirmed',
        ];

        if (!empty($user->password)) {
            $rules['current_password'] = 'required';
        }

        $request->validate($rules, [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal harus 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!empty($user->password) && !Hash::check($request->current_password, $user->password)) {
            return redirect()->route('pemilik.password.edit')
                ->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pemilik.password.edit')
            ->with('success', 'Password akun pemilik warung Anda berhasil diperbarui dengan aman.');
    }

    /**
     * Pemilik warung meminta password baru dikirimkan langsung ke email mereka.
     */
    public function requestPasswordEmail(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $passwordBaru = Str::password(10, symbols: false);

        $user->update([
            'password' => Hash::make($passwordBaru),
        ]);

        try {
            Mail::to($user->email)->send(new UserPasswordBaru($user, $passwordBaru, 'user_request'));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim password baru ke email pemilik warung: ' . $e->getMessage());

            return redirect()->route('pemilik.password.edit')
                ->with('success', 'Password baru Anda berhasil dibuat: "' . $passwordBaru . '" (Catatan: Pengiriman email gagal, periksa pengaturan mail server).');
        }

        return redirect()->route('pemilik.password.edit')
            ->with('success', 'Password baru berhasil dibuat dan telah dikirimkan ke email Anda (' . $user->email . '). Silakan periksa kotak masuk atau spam email Anda.');
    }
}
