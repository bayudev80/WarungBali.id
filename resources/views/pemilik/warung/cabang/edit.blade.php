@extends('pemilik.layouts.app')

@section('title', 'Edit Cabang')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-1">Edit Cabang</h3>
    <p class="text-muted mb-4">
        Cabang dari <strong>{{ $warung->nama_warung }}</strong>, kategori {{ $warung->kategori->nama_kategori ?? '-' }}.
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

    @if($cabang->status === 'approved')
        <div class="alert alert-info small">
            <i class="bi bi-info-circle"></i>
            Cabang ini sudah tayang. Kalau data diubah, cabang akan ditinjau ulang oleh admin sebelum tayang lagi.
        </div>
    @endif

    <form action="{{ route('pemilik.warung.cabang.update', $cabang->id_warung) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Cabang</label>
            <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung', $cabang->nama_warung) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kabupaten</label>
            <select name="id_kabupaten" class="form-select" required>
                <option value="">-- Pilih Kabupaten --</option>
                @foreach($kabupaten as $k)
                    <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten', $cabang->id_kabupaten) == $k->id_kabupaten ? 'selected' : '' }}>
                        {{ $k->nama_kabupaten }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $cabang->alamat) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $cabang->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $cabang->telepon) }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Buka</label>
                <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka', $cabang->jam_buka) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Tutup</label>
                <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup', $cabang->jam_tutup) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Minimal</label>
                <input type="number" name="harga_min" class="form-control" value="{{ old('harga_min', $cabang->harga_min) }}" min="0">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Maksimal</label>
                <input type="number" name="harga_max" class="form-control" value="{{ old('harga_max', $cabang->harga_max) }}" min="0">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="menerima_catering" value="0">
            <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input" value="1" {{ old('menerima_catering', $cabang->menerima_catering) ? 'checked' : '' }}>
            <label class="form-check-label" for="menerima_catering">
                Cabang ini {{ strtolower($cabang->layanan_label) }}
            </label>
        </div>

        @if($cabang->foto)
            <div class="mb-3">
                <label class="form-label d-block">Foto Saat Ini</label>
                <img src="{{ asset('images/warung/' . $cabang->foto) }}" alt="{{ $cabang->nama_warung }}" style="max-width:200px;" class="rounded border">
            </div>
        @endif

        <div class="mb-4">
            <label class="form-label">Ganti Foto Cabang</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-warning">
            <i class="bi bi-check2"></i>
            Simpan Perubahan
        </button>

        <a href="{{ route('pemilik.dashboard') }}" class="btn btn-outline-secondary">Batal</a>

    </form>

</div>

@endsection
