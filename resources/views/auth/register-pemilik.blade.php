@extends('layouts.auth')

@section('title', 'Daftarkan Warung')

@section('content')

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="col-md-8 col-lg-6">

        <div class="card border-0 shadow-lg rounded-4">

            <div class="card-body p-5">

                <div class="text-center mb-4">

                    <h2 class="fw-bold" style="color:#C85C2E;">
                        WarungBali.id
                    </h2>

                    <p class="text-secondary mb-0">
                        Daftarkan Warung Anda
                    </p>

                    <small class="text-muted">
                        Isi data akun dan data warung Anda sekaligus. Warung &amp; akun akan ditinjau oleh admin sebelum bisa digunakan.
                    </small>

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

                <form method="POST" action="{{ route('pemilik.register') }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="fw-bold mb-3" style="color:#C85C2E;">Data Akun</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pemilik</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', request('nama')) }}" placeholder="Nama lengkap Anda" required autofocus>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', request('email')) }}" placeholder="Masukkan email aktif" required>
                        <small class="text-muted">Password login akan dikirim ke email ini setelah akun diverifikasi admin.</small>
                    </div>

                    <hr class="mb-4">

                    <h5 class="fw-bold mb-3" style="color:#C85C2E;">Data Warung</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Warung</label>
                        <input type="text" name="nama_warung" class="form-control @error('nama_warung') is-invalid @enderror"
                            value="{{ old('nama_warung') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="id_kategori" id="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori }}" {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kabupaten</label>
                            <select name="id_kabupaten" class="form-select @error('id_kabupaten') is-invalid @enderror" required>
                                <option value="">-- Pilih Kabupaten --</option>
                                @foreach($kabupaten as $k)
                                    <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten') == $k->id_kabupaten ? 'selected' : '' }}>
                                        {{ $k->nama_kabupaten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat</label>
                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga Minimal</label>
                            <input type="number" name="harga_min" class="form-control" value="{{ old('harga_min', 0) }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga Maksimal</label>
                            <input type="number" name="harga_max" class="form-control" value="{{ old('harga_max', 0) }}" min="0">
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="menerima_catering" value="0">
                        <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input" value="1" {{ old('menerima_catering') ? 'checked' : '' }}>
                        <label class="form-check-label" for="menerima_catering">
                            Menerima Layanan Khusus
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Warung</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>

                    <button class="btn w-100 text-white py-2" style="background:#C85C2E;border-radius:12px;">
                        <i class="bi bi-send me-2"></i>
                        Kirim Pendaftaran
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
                        Cuma mau menjelajah &amp; kasih ulasan warung?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#C85C2E;">
                            Daftar sebagai pengguna biasa
                        </a>
                    </small>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
