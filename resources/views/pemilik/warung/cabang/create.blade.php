@extends('pemilik.layouts.app')

@section('title', 'Tambah Cabang Baru')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-1">Tambah Cabang Baru</h3>
    <p class="text-muted mb-4">
        Cabang ini otomatis terdaftar sebagai cabang dari <strong>{{ $warung->nama_warung }}</strong>
        dengan kategori yang sama ({{ $warung->kategori->nama_kategori ?? '-' }}).
        {{ $warung->label_menu }} yang tampil akan mengikuti {{ strtolower($warung->label_menu) }} warung utama, jadi tidak perlu diisi ulang.
        Cabang akan tayang setelah disetujui admin.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilik.warung.cabang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Cabang</label>
            <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung') }}" placeholder="Contoh: {{ $warung->nama_warung }} Cabang Denpasar" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kabupaten</label>
            <select name="id_kabupaten" class="form-select" required>
                <option value="">-- Pilih Kabupaten --</option>
                @foreach($kabupaten as $k)
                    <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten') == $k->id_kabupaten ? 'selected' : '' }}>
                        {{ $k->nama_kabupaten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Buka</label>
                <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Tutup</label>
                <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Minimal</label>
                <input type="number" name="harga_min" class="form-control" value="{{ old('harga_min', 0) }}" min="0">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Maksimal</label>
                <input type="number" name="harga_max" class="form-control" value="{{ old('harga_max', 0) }}" min="0">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="menerima_catering" value="0">
            <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input" value="1" {{ old('menerima_catering') ? 'checked' : '' }}>
            <label class="form-check-label" for="menerima_catering">
                Cabang ini {{ strtolower($warung->layanan_label) }}
            </label>
        </div>

        <div class="mb-4">
            <label class="form-label">Foto Cabang</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-success">
            <i class="bi bi-send"></i>
            Ajukan Cabang
        </button>

        <a href="{{ route('pemilik.dashboard') }}" class="btn btn-outline-secondary">Batal</a>

    </form>

</div>

@endsection
