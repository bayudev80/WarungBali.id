@extends('pemilik.layouts.app')

@section('title', 'Edit ' . $warung->label_menu)

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Edit {{ $warung->label_menu }}</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilik.menu.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama {{ $warung->label_menu }}</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto {{ $warung->label_menu }}</label><br>

            @if($menu->foto_menu && file_exists(public_path('images/menu/'.$menu->foto_menu)))
                <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" style="width:100px;height:100px;object-fit:cover;border-radius:6px;" class="mb-2">
            @else
                <div class="text-muted small mb-2">Belum ada foto</div>
            @endif

            <input type="file" name="foto_menu" class="form-control" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('pemilik.menu.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>

@endsection
