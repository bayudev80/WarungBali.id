@extends('pemilik.layouts.app')

@section('title', 'Daftarkan Warung Saya')

@section('content')

<div class="content-box">

    <div class="alert alert-success d-flex align-items-center gap-3 rounded-4 p-3 mb-4 shadow-sm border-0" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-left: 4px solid #10B981 !important;">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 20px;">
            <i class="bi bi-patch-check-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1 text-success-emphasis">Selamat Datang, {{ auth()->user()->nama }}!</h6>
            <p class="mb-0 small text-success-emphasis">
                Akun pemilik warung Anda telah diverifikasi oleh admin. Silakan lengkapi formulir pendaftaran data warung Anda di bawah ini agar dapat segera ditinjau dan ditayangkan di website WarungBali.id.
            </p>
        </div>
    </div>

    <h3 class="fw-bold mb-1">Daftarkan Warung Anda</h3>
    <p class="text-muted mb-4">Isi data warung Anda di bawah ini dengan lengkap dan benar.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilik.warung.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Warung</label>
            <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
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
            </div>

            <div class="col-md-6 mb-3">
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
            <label class="form-check-label" for="menerima_catering" id="label_layanan_checkbox">
                Menerima Layanan Khusus
            </label>
        </div>

        <div class="mb-4">
            <label class="form-label">Foto Warung</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-success">
            <i class="bi bi-send"></i>
            Ajukan Warung
        </button>

    </form>

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
