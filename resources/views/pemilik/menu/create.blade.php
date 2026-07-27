@extends('pemilik.layouts.app')

@section('title', 'Tambah ' . $warung->label_menu)

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Tambah {{ $warung->label_menu }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilik.menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama {{ $warung->label_menu }}</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', 0) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto {{ $warung->label_menu }}</label>
            <input type="file" name="foto_menu" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('pemilik.menu.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>

@endsection
