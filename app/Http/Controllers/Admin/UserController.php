<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserPasswordBaru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('role', '!=', 'admin')->orderBy('id_user', 'ASC')->get();
        $deletionLogs = \App\Models\UserDeletionLog::orderBy('created_at', 'DESC')->get();

        return view('admin.user.index', compact('user', 'deletionLogs'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'role'        => 'required|in:user,pemilik',
            'status_akun' => 'nullable|in:pending,verified',
        ]);

        User::create([
            'nama'        => $request->nama,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'status_akun' => $request->status_akun ?? 'verified',
        ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'        => 'required|max:100',
            'email'       => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'password'    => 'nullable|min:6',
            'role'        => 'required|in:user,pemilik',
            'status_akun' => 'nullable|in:pending,verified',
        ]);

        $data = [
            'nama'        => $request->nama,
            'email'       => $request->email,
            'role'        => $request->role,
            'status_akun' => $request->status_akun ?? $user->status_akun ?? 'verified',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengguna berhasil diubah.');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Tidak bisa menghapus akun yang sedang login.');
        }

        $alasanKategori = $request->input('alasan_kategori', 'Melanggar Ketentuan Layanan');
        $alasanDetail = $request->input('alasan_detail');

        // Catat riwayat audit log penghapusan pengguna
        \App\Models\UserDeletionLog::create([
            'id_user'         => $user->id_user,
            'nama'            => $user->nama,
            'email'           => $user->email,
            'role'            => $user->role,
            'alasan_kategori' => $alasanKategori,
            'alasan_detail'   => $alasanDetail,
            'deleted_by_name' => auth()->user()->nama ?? 'Admin',
            'created_at'      => now(),
        ]);

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'Pengguna "' . $user->nama . '" berhasil dihapus. Alasan penghapusan telah tercatat.');
    }

    /**
     * Reset password pengguna dan kirimkan password baru melalui email.
     */
    public function sendPassword($id)
    {
        $user = User::findOrFail($id);

        $passwordBaru = Str::password(10, symbols: false);

        $user->update([
            'password' => Hash::make($passwordBaru),
        ]);

        try {
            Mail::to($user->email)->send(new UserPasswordBaru($user, $passwordBaru, 'admin_reset'));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email reset password oleh admin: ' . $e->getMessage());

            return redirect()->route('admin.user.index')
                ->with('success', 'Password pengguna "' . $user->nama . '" berhasil direset: "' . $passwordBaru . '" (Gagal kirim email: periksa konfigurasi mail di .env).');
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'Password baru berhasil dibuat dan dikirimkan ke email ' . $user->email . '.');
    }
}

