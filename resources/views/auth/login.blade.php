@extends('layouts.auth')

@section('title', 'Masuk ke WarungBali.id')

@section('content')

<style>
    .auth-card {
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(200, 92, 46, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(200, 92, 46, 0.1);
        background: #ffffff;
    }
    .btn-google {
        border-radius: 14px;
        font-weight: 600;
        font-size: 14.5px;
        transition: all 0.2s ease;
        border: 1.5px solid #E4E4E7;
        background: #ffffff;
        color: #27272A;
    }
    .btn-google:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        color: #0F172A;
        transform: translateY(-1px);
    }
    .btn-primary-custom {
        background: #C85C2E;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        font-size: 15px;
        padding: 11px;
        transition: all 0.2s ease;
    }
    .btn-primary-custom:hover {
        background: #B34F24;
        box-shadow: 0 6px 18px rgba(200, 92, 46, 0.3);
        transform: translateY(-1px);
    }
    .form-control:focus {
        border-color: #C85C2E;
        box-shadow: 0 0 0 0.25rem rgba(200, 92, 46, 0.15);
    }
</style>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="col-12 col-md-8 col-lg-5">

        <div class="card auth-card overflow-hidden">

            <div class="card-body p-4 p-sm-5">

                <!-- Logo & Heading -->
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <img src="{{ asset('images/logo.png') }}"
                            alt="WarungBali"
                            width="72"
                            class="mb-2 rounded-3">
                        <h3 class="fw-bold mb-1" style="color:#C85C2E; letter-spacing: -0.5px;">
                            WarungBali<span class="text-dark">.id</span>
                        </h3>
                    </a>
                    <p class="text-secondary small mb-0">
                        Silakan masuk ke akun Anda
                    </p>
                </div>

                <!-- Session Alert -->
                @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 py-2 px-3 mb-4 small border-0 shadow-sm" style="background:#ECFDF5; color:#065F46;">
                        <i class="bi bi-check-circle-fill fs-6"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 py-2 px-3 mb-4 small border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Masukkan email Anda" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" id="loginPassword"
                                class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                placeholder="Masukkan password" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePassword('loginPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold" style="color:#C85C2E;">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </form>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1 my-0 text-muted opacity-25">
                    <span class="px-3 text-muted small fw-medium">atau masuk dengan</span>
                    <hr class="flex-grow-1 my-0 text-muted opacity-25">
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google.redirect') }}" 
                   class="btn btn-google w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-4 shadow-sm">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>

                <div class="text-center pt-1 border-top">
                    <span class="text-muted small">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none small ms-1" style="color:#C85C2E;">
                        Daftar Sekarang
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

@endsection