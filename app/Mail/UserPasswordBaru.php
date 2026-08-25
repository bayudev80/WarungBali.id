<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserPasswordBaru extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $passwordBaru,
        public string $tipe = 'admin_reset'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->tipe === 'user_request'
            ? 'Password Baru Akun Anda - WarungBali.id'
            : 'Informasi Password Baru dari Admin - WarungBali.id';

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user-password-baru',
        );
    }
}
