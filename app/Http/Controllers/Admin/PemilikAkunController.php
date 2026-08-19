<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AkunPemilikDiverifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PemilikAkunController extends Controller
{
    /**
     * Daftar akun pemilik warung yang mendaftar lewat form "Daftar sebagai
     * Pemilik Warung" dan masih menunggu verifikasi (status_akun pending).
     * Ini terpisah dari menu "Data Warung" -- verifikasi akun di sini TIDAK
     * mempengaruhi status approve/reject warungnya, dan sebaliknya.
     */
    public function index(): View
    {
        $akun = User::where('role', 'pemilik')
            ->where('status_akun', 'pending')
            ->with('warung')
            ->latest('id_user')
            ->paginate(10);

        return view('admin.pemilik-akun.index', compact('akun'));
    }

    /**
     * Verifikasi akun: generate password acak, simpan (di-hash), kirim
     * password itu ke email pemilik, lalu tandai akun sebagai verified
     * supaya bisa dipakai login.
     */
    public function verifikasi($id): RedirectResponse
    {
        $user = User::where('role', 'pemilik')
            ->where('status_akun', 'pending')
            ->findOrFail($id);

        $passwordBaru = Str::password(10, symbols: false);

        $user->update([
            'password'    => Hash::make($passwordBaru),
            'status_akun' => 'verified',
        ]);

        Mail::to($user->email)->send(new AkunPemilikDiverifikasi($user, $passwordBaru));

        return redirect()->route('admin.pemilik-akun.index')
            ->with('success', 'Akun "'.$user->nama.'" berhasil diverifikasi. Password login sudah dikirim ke '.$user->email.'.');
    }
}
