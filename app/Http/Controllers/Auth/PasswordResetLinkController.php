<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Akun yang masih 'pending' belum boleh reset password sendiri --
        // itu jalan pintas yang bisa dipakai untuk melewati verifikasi
        // admin. Tampilkan pesan yang jelas alih-alih diam-diam mengirim
        // link reset.
        $pendingUser = User::where('email', $request->email)
            ->where('status_akun', 'pending')
            ->first();

        if ($pendingUser) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun ini masih menunggu verifikasi admin dan belum bisa reset password. Anda akan menerima email berisi password login setelah akun diverifikasi.']);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
