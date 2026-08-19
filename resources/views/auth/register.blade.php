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

                <hr>

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
