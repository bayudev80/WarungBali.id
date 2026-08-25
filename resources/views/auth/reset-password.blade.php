@extends('layouts.auth')

@section('title', 'Reset Password - WarungBali.id')

@section('content')

<style>
    .auth-card {
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(200, 92, 46, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(200, 92, 46, 0.1);
        background: #ffffff;
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
                        Atur kata sandi baru untuk akun Anda.
                    </p>
                </div>

                <!-- Form Reset Password -->
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email"
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                value="{{ old('email', $request->email) }}" required autofocus readonly>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <input type="password" name="password" id="resetPass"
                                class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePass('resetPass', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-shield-check"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="resetPassConf"
                                class="form-control border-start-0 ps-0"
                                placeholder="Ketik ulang password baru" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePass('resetPassConf', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm mb-3">
                        <i class="bi bi-check-circle me-1"></i> Simpan Password Baru
                    </button>
                </form>

                <div class="text-center pt-2 border-top">
                    <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold" style="color:#C85C2E;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Masuk
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function togglePass(inputId, btn) {
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
