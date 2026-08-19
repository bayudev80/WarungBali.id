<x-mail::message>
# Akun Anda Sudah Diverifikasi

Halo **{{ $user->nama }}**,

Selamat! Akun pemilik warung Anda di WarungBali.id sudah diverifikasi oleh admin dan sekarang bisa digunakan untuk login.

Berikut detail akun Anda:

- **Email:** {{ $user->email }}
- **Password:** {{ $passwordBaru }}

<x-mail::button :url="route('login')">
Masuk Sekarang
</x-mail::button>

Demi keamanan, disarankan untuk segera mengganti password ini setelah berhasil login.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
