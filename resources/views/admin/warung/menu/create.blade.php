@extends('admin.layouts.app')

@section('title', 'Tambah Menu')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-1">Tambah Menu</h3>
    <p class="text-muted mb-4">Warung: {{ $warung->nama_warung }}</p>

    <form action="{{ route('admin.warung.menu.store', $warung->id_warung) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
            @error('nama_menu') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', 0) }}" min="0" required>
            @error('harga') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Menu</label>
            <input type="file" name="foto_menu" class="form-control" accept="image/*">
            @error('foto_menu') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.warung.menu.index', $warung->id_warung) }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>

@endsection
