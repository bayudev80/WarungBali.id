@extends('admin.layouts.app')

@section('title', 'Tambah Warung')

@section('content')

    <style>
        .modern-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background-color: #ffffff;
        }
        .modern-card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f3f5;
            padding: 1.25rem 1.5rem;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .modern-card-body {
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Tambah Warung</h4>
            <p class="text-muted small mb-0 mt-1">Masukkan detail warung baru yang ingin ditambahkan.</p>
        </div>
        <a href="{{ route('admin.warung.index') }}" class="btn btn-light text-secondary border rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card modern-card">
        <div class="modern-card-body">
            <form action="{{ route('admin.warung.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nama Warung</label>
                    <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung') }}" placeholder="Contoh: Warung Nasi Bali..." required>
                    @error('nama_warung') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                @php
                                    $tempWarung = new \App\Models\Warung(['id_kategori' => $k->id_kategori]);
                                    $tempWarung->kategori = $k;
                                @endphp
                                <option value="{{ $k->id_kategori }}" 
                                        data-layanan="{{ $tempWarung->layanan_label }}" 
                                        {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kabupaten</label>
                        <select name="id_kabupaten" class="form-select" required>
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach($kabupaten as $k)
                                <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten') == $k->id_kabupaten ? 'selected' : '' }}>
                                    {{ $k->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kabupaten') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Alamat Lengkap</label>
                    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Jalan, Banjar, atau detail lokasi lainnya..." required>
                    @error('alamat') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan secara singkat tentang warung ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890">
                    @error('telepon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Jam Buka</label>
                        <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jam Tutup</label>
                        <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup') }}">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Harga Minimal (Rp)</label>
                        <input type="number" name="harga_min" class="form-control" value="{{ old('harga_min', 0) }}" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Maksimal (Rp)</label>
                        <input type="number" name="harga_max" class="form-control" value="{{ old('harga_max', 0) }}" min="0">
                    </div>
                </div>

                <div class="mb-4 form-check bg-light p-3 rounded border">
                    <input type="hidden" name="menerima_catering" value="0">
                    <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input ms-1" value="1" {{ old('menerima_catering') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2 text-dark" for="menerima_catering" id="label_layanan_checkbox">
                        Menerima Layanan Khusus
                    </label>
                </div>

                <div class="mb-5">
                    <label class="form-label">Foto Warung</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <div class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG, WEBP. Maksimal ukuran 5MB.</div>
                    @error('foto') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <hr class="mb-4">

                <div class="d-flex justify-content-end gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="bi bi-save me-1"></i> Simpan Data Warung
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('id_kategori');
    const labelLayanan = document.getElementById('label_layanan_checkbox');

    function updateCheckboxLabel() {
        if (!kategoriSelect || !labelLayanan) return;
        const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const label = selectedOption.getAttribute('data-layanan');
            labelLayanan.textContent = label;
        } else {
            labelLayanan.textContent = 'Menerima Layanan Khusus';
        }
    }

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', updateCheckboxLabel);
        updateCheckboxLabel();
    }
});
</script>
@endpush
