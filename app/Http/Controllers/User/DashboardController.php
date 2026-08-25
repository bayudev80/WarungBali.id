<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\UserPasswordBaru;
use App\Models\Favorit;
use App\Models\Review;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Pengguna lengkap dengan data profil,
     * warung favorit, ulasan saya, dan ringkasan aktivitas.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Ambil daftar warung favorit pengguna
        $warungFavorit = Warung::with(['kategori', 'kabupaten', 'review', 'favorit', 'menu'])
            ->whereHas('favorit', function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })
            ->latest('id_warung')
            ->get();

        // Ambil riwayat ulasan yang ditulis oleh pengguna
        $myReviews = Review::with(['warung.kategori', 'warung.kabupaten'])
            ->where('id_user', $user->id_user)
            ->latest('id_review')
            ->get();

        // Warung yang dimiliki / didaftarkan pengguna jika ada
        $myWarung = Warung::where('id_user', $user->id_user)
            ->whereNull('id_warung_induk')
            ->first();

        $totalFavorit = $warungFavorit->count();
        $totalReview = $myReviews->count();

        // Tab aktif yang dipilih (default: ringkasan)
        $activeTab = $request->query('tab', 'ringkasan');

        return view('user.dashboard', compact(
            'user',
            'warungFavorit',
            'myReviews',
            'myWarung',
            'totalFavorit',
            'totalReview',
            'activeTab'
        ));
    }

    /**
     * Memperbarui informasi profil pengguna (Nama, Email, dan Foto Profil).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id_user . ',id_user',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
            'foto.image'     => 'Berkas foto harus berupa gambar.',
            'foto.mimes'     => 'Format foto harus JPEG, PNG, JPG, atau WebP.',
            'foto.max'       => 'Ukuran foto maksimal adalah 3 MB.',
        ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
        ];

        // Jika email diubah, reset status verifikasi email
        if ($user->email !== $request->email) {
            $data['email_verified_at'] = null;
        }

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            $this->hapusFotoLama($user->foto);

            $file = $request->file('foto');
            $filename = 'avatar_' . $user->id_user . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $targetDir = public_path('images/avatars');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            $file->move($targetDir, $filename);
            $data['foto'] = $filename;
        }

        $user->update($data);

        return redirect()->route('user.dashboard', ['tab' => 'profil'])
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Hapus foto profil pengguna dan kembali ke avatar inisial.
     */
    public function removeFoto(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->foto) {
            $this->hapusFotoLama($user->foto);
            $user->update(['foto' => null]);
        }

        return redirect()->route('user.dashboard', ['tab' => 'profil'])
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Ubah password mandiri pengguna.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $rules = [
            'password' => 'required|min:6|confirmed',
        ];

        // Jika user bukan login murni Google (punya password), cek current password
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
            return redirect()->route('user.dashboard', ['tab' => 'keamanan'])
                ->withErrors(['current_password' => 'Password saat ini yang Anda masukkan salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.dashboard', ['tab' => 'keamanan'])
            ->with('success', 'Password Anda berhasil diperbarui dengan aman.');
    }

    /**
     * Pengguna meminta password baru dikirimkan langsung ke email mereka.
     */
    public function requestPasswordEmail(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Buat password acak yang aman
        $passwordBaru = Str::password(10, symbols: false);

        $user->update([
            'password' => Hash::make($passwordBaru),
        ]);

        try {
            Mail::to($user->email)->send(new UserPasswordBaru($user, $passwordBaru, 'user_request'));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim password baru ke email pengguna: ' . $e->getMessage());

            return redirect()->route('user.dashboard', ['tab' => 'keamanan'])
                ->with('success', 'Password baru Anda berhasil di-generate: "' . $passwordBaru . '" (Catatan: Pengiriman email gagal, periksa konfigurasi mail server).');
        }

        return redirect()->route('user.dashboard', ['tab' => 'keamanan'])
            ->with('success', 'Password baru berhasil dibuat dan telah dikirimkan ke email ' . $user->email . '. Silakan periksa kotak masuk atau spam email Anda.');
    }

    /**
     * Hapus ulasan yang pernah dibuat pengguna untuk warung.
     */
    public function deleteReview($id): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $review = Review::where('id_user', $user->id_user)
            ->where('id_review', $id)
            ->firstOrFail();

        $review->delete();

        return redirect()->route('user.dashboard', ['tab' => 'ulasan'])
            ->with('success', 'Ulasan Anda berhasil dihapus.');
    }

    /**
     * Helper hapus file foto profil lama.
     */
    private function hapusFotoLama(?string $foto): void
    {
        if ($foto && !filter_var($foto, FILTER_VALIDATE_URL)) {
            $path = public_path('images/avatars/' . $foto);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}
