@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="col-md-6 col-lg-4">

        <div class="card border-0 shadow-lg rounded-4">

            <div class="card-body p-5">

                <div class="text-center mb-4">

                    <h2 class="fw-bold" style="color:#C85C2E;">
                        WarungBali.id
                    </h2>

                    <p class="text-secondary">
                        Buat akun baru
                    </p>

                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" placeholder="Nama lengkap" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Masukkan email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Ulangi password" required>
                    </div>

                    <button class="btn w-100 text-white py-2" style="background:#C85C2E;border-radius:12px;">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Daftar
                    </button>

                </form>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1 my-0 text-muted opacity-25">
                    <span class="px-3 text-muted small fw-medium">atau daftar dengan</span>
                    <hr class="flex-grow-1 my-0 text-muted opacity-25">
                </div>

                <a href="{{ route('auth.google.redirect') }}" 
                   class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-3 bg-white text-dark shadow-sm border"
                   style="border-radius:12px; font-weight:600; font-size:14px; transition:all 0.2s ease;">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Daftar dengan Google</span>
                </a>

                <div class="text-center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color:#C85C2E;">
                        Masuk
                    </a>
                </div>

                <div class="text-center mt-2">
                    <small class="text-muted">
                        Punya warung dan mau didaftarkan?
                        <a href="{{ route('pemilik.register') }}" class="text-decoration-none fw-semibold" style="color:#C85C2E;">
                            Daftar sebagai Pemilik Warung
                        </a>
                    </small>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
