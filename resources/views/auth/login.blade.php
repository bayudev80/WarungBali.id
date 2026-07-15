@extends('layouts.auth')

@section('content')

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="col-md-6 col-lg-4">

        <div class="card border-0 shadow-lg rounded-4">

            <div class="card-body p-5">

                <div class="text-center mb-4">

                    <img src="{{ asset('images/logo.png') }}"
                        alt="WarungBali"
                        width="80"
                        class="mb-3">

                    <h2 class="fw-bold" style="color:#C85C2E;">
                        WarungBali.id
                    </h2>

                    <p class="text-secondary">
                        Selamat datang kembali 👋
                    </p>

                </div>

                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email"
                                required
                                autofocus>

                        </div>

                        @error('email')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                required>

                        </div>

                        @error('password')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">

                            <input class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember">

                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>

                        </div>

                        @if(Route::has('password.request'))

                        <a href="{{ route('password.request') }}"
                            class="text-decoration-none"
                            style="color:#C85C2E;">

                            Lupa Password?

                        </a>

                        @endif

                    </div>

                    <button
                        class="btn w-100 text-white py-2"
                        style="background:#C85C2E;border-radius:12px;">

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Masuk

                    </button>

                </form>

                <hr>

                <div class="text-center">

                    Belum punya akun?

                    <a href="{{ route('register') }}"
                        class="fw-bold text-decoration-none"
                        style="color:#C85C2E;">

                        Daftar Sekarang

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection