@extends('admin.layouts.app')

@section('title', 'Edit Menu')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-1">Edit Menu</h3>
    <p class="text-muted mb-4">Warung: {{ $warung->nama_warung }}</p>

    <form action="{{ route('admin.warung.menu.update', [$warung->id_warung, $menu->id_menu]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
            @error('nama_menu') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            @error('deskripsi') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" min="0" required>
            @error('harga') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Menu</label><br>

            @if($menu->foto_menu)
                <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" style="width:100px;height:100px;object-fit:cover;border-radius:6px;" class="mb-2">
            @endif

            <input type="file" name="foto_menu" class="form-control" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            @error('foto_menu') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.warung.menu.index', $warung->id_warung) }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>

@endsection
