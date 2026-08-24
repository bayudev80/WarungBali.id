@extends('layouts.auth')

@section('title', 'Daftar Akun WarungBali.id')

@section('content')

<style>
    .auth-card {
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(200, 92, 46, 0.08), 0 5px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(200, 92, 46, 0.1);
        background: #ffffff;
    }
    .role-tabs {
        background: #F8F5F2;
        padding: 5px;
        border-radius: 16px;
        display: flex;
        gap: 5px;
    }
    .role-tab-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 14px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 12px;
        color: #71717A;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .role-tab-btn.active {
        background: #ffffff;
        color: #C85C2E;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }
    .role-tab-btn:hover:not(.active) {
        color: #27272A;
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
                        Buat akun baru untuk mulai menjelajah
                    </p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 py-2 px-3 mb-4 small border-0 shadow-sm">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Pilihan Daftar Sebagai: Pengguna vs Pemilik Warung -->
                <div class="role-tabs mb-4">
                    <button type="button" class="role-tab-btn active" id="tabBtnUser" onclick="switchRole('user')">
                        <i class="bi bi-person-fill fs-5"></i>
                        <span>Pengguna</span>
                    </button>
                    <button type="button" class="role-tab-btn" id="tabBtnPemilik" onclick="switchRole('pemilik')">
                        <i class="bi bi-shop fs-5"></i>
                        <span>Pemilik Warung</span>
                    </button>
                </div>

                <!-- ============================================ -->
                <!-- PENGGUNA TAB CONTENT                         -->
                <!-- ============================================ -->
                <div id="paneUser">

                    <!-- Google Register / Login Button (HANYA untuk Pengguna) -->
                    <a href="{{ route('auth.google.redirect') }}" 
                       class="btn btn-google w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-3 shadow-sm">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Daftar dengan Google</span>
                    </a>

                    <div class="d-flex align-items-center my-3">
                        <hr class="flex-grow-1 my-0 text-muted opacity-25">
                        <span class="px-3 text-muted small fw-medium">atau daftar dengan email</span>
                        <hr class="flex-grow-1 my-0 text-muted opacity-25">
                    </div>

                    <!-- User Registration Form -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nama"
                                    class="form-control border-start-0 ps-0 @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" placeholder="Nama lengkap Anda" required autofocus>
                            </div>
                            @error('nama')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email"
                                    class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="nama@email.com" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" id="userPass"
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePassword('userPass', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-dark">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="userPassConfirm"
                                    class="form-control border-start-0 ps-0"
                                    placeholder="Ulangi password" required>
                                <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePassword('userPassConfirm', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm mb-3">
                            <i class="bi bi-person-plus-fill me-1"></i> Daftar sebagai Pengguna
                        </button>
                    </form>

                </div>

                <!-- ============================================ -->
                <!-- PEMILIK WARUNG TAB CONTENT                   -->
                <!-- ============================================ -->
                <div id="panePemilik" style="display: none;">

                    <div class="alert rounded-3 py-2 px-3 mb-3 small" style="background:#FFF7ED; border: 1px solid #FFEDD5; color:#9A3412;">
                        <i class="bi bi-shield-lock-fill me-1 text-warning"></i>
                        <strong>Pendaftaran Pemilik Warung:</strong> Daftarkan akun Anda terlebih dahulu. Setelah admin menyetujui, password login akan dikirimkan ke email Anda untuk masuk dan melengkapi data warung.
                    </div>

                    <form method="POST" action="{{ route('pemilik.register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Nama Pemilik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="nama" class="form-control border-start-0 ps-0 @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" placeholder="Nama lengkap Anda" required>
                            </div>
                            @error('nama')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-dark">Email Pemilik</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="email@anda.com" required>
                            </div>
                            <small class="text-muted" style="font-size: 11.5px;">Password login resmi akan dikirim ke alamat email ini setelah akun diverifikasi admin.</small>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 text-white shadow-sm mb-3">
                            <i class="bi bi-send-fill me-1"></i> Daftar sebagai Pemilik Warung
                        </button>
                    </form>

                </div>

                <div class="text-center pt-2 border-top">
                    <span class="text-muted small">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none small ms-1" style="color:#C85C2E;">
                        Masuk di sini
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    function switchRole(role) {
        const paneUser = document.getElementById('paneUser');
        const panePemilik = document.getElementById('panePemilik');
        const tabBtnUser = document.getElementById('tabBtnUser');
        const tabBtnPemilik = document.getElementById('tabBtnPemilik');

        if (role === 'user') {
            paneUser.style.display = 'block';
            panePemilik.style.display = 'none';
            tabBtnUser.classList.add('active');
            tabBtnPemilik.classList.remove('active');
        } else {
            paneUser.style.display = 'none';
            panePemilik.style.display = 'block';
            tabBtnUser.classList.remove('active');
            tabBtnPemilik.classList.add('active');
        }
    }

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

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('role') === 'pemilik' || window.location.hash === '#pemilik') {
            switchRole('pemilik');
        }
    });
</script>

@endsection
