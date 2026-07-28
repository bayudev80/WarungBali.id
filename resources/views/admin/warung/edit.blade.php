@extends('admin.layouts.app')

@section('title', 'Edit Warung')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Edit Warung</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.warung.update', $warung->id_warung) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Warung</label>
            <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung', $warung->nama_warung) }}" required>
            @error('nama_warung') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $warung->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('id_kategori') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kabupaten</label>
                <select name="id_kabupaten" class="form-select" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($kabupaten as $k)
                        <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten', $warung->id_kabupaten) == $k->id_kabupaten ? 'selected' : '' }}>
                            {{ $k->nama_kabupaten }}
                        </option>
                    @endforeach
                </select>
                @error('id_kabupaten') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $warung->alamat) }}" required>
            @error('alamat') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $warung->deskripsi) }}</textarea>
            @error('deskripsi') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $warung->telepon) }}">
            @error('telepon') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Buka</label>
                <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka', $warung->jam_buka) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jam Tutup</label>
                <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup', $warung->jam_tutup) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Minimal</label>
                <input type="number" name="harga_min" class="form-control" value="{{ old('harga_min', $warung->harga_min) }}" min="0">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Maksimal</label>
                <input type="number" name="harga_max" class="form-control" value="{{ old('harga_max', $warung->harga_max) }}" min="0">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Warung</label><br>

            @if($warung->foto && file_exists(public_path('images/warung/'.$warung->foto)))
                <img src="{{ asset('images/warung/'.$warung->foto) }}" style="width:100px;height:100px;object-fit:cover;border-radius:6px;" class="mb-2">
            @else
                <div class="text-muted small mb-2">Belum ada foto / file tidak ditemukan</div>
            @endif

            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            @error('foto') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="hidden" name="menerima_catering" value="0">
            <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input" value="1" {{ old('menerima_catering', $warung->menerima_catering) ? 'checked' : '' }}>
            <label class="form-check-label" for="menerima_catering">
                Warung ini menerima layanan catering
            </label>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.warung.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>

@endsection
