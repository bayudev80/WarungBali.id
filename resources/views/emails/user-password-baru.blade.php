<x-mail::message>
# Password Baru Akun WarungBali.id

Halo **{{ $user->nama }}**,

@if($tipe === 'user_request')
Anda telah meminta pembuatan password baru untuk akun Anda di **WarungBali.id**.
@else
Admin **WarungBali.id** telah mereset password akun Anda dan menerbitkan password login baru.
@endif

Berikut adalah informasi akun Anda:

- **Nama Pengguna:** {{ $user->nama }}
- **Email Terdaftar:** {{ $user->email }}
- **Password Baru:** `{{ $passwordBaru }}`

<x-mail::button :url="route('login')">
Masuk ke Akun Saya
</x-mail::button>

> **Tips Keamanan:** Setelah berhasil masuk menggunakan password di atas, Anda sangat disarankan untuk segera mengganti password dengan kombinasi rahasia Anda sendiri melalui menu **Keamanan & Password** di Dashboard Pengguna.

Jika Anda tidak merasa meminta perubahan ini atau mengalami kendala, silakan hubungi tim administrator WarungBali.id.

Salam hangat,<br>
**Tim WarungBali.id**
</x-mail::message>
