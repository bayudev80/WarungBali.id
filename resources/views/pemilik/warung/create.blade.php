@extends('pemilik.layouts.app')

@section('title', 'Daftarkan Warung Saya')

@section('content')

<div class="content-box">

    <div class="rounded-4 p-3 mb-4 d-flex align-items-center gap-3 shadow-sm border" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-left: 5px solid #10B981 !important; border-color: #A7F3D0;">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px; font-size: 20px;">
            <i class="bi bi-shop"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1 text-success-emphasis">Pendaftaran Warung Kuliner — {{ auth()->user()->nama }}</h6>
            <p class="mb-0 small text-success-emphasis" style="line-height: 1.5;">
                Silakan lengkapi data profil warung Anda di bawah ini. Setelah dikirim, data warung akan ditinjau oleh tim admin WarungBali.id sebelum resmi ditayangkan di direktori publik.
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
                <label class="form-label d-flex justify-content-between align-items-center">
                    <span>Kategori</span>
                    <span class="text-muted small" style="font-size: 11.5px;">Tidak ada di daftar? Pilih <i>Ajukan Kategori Baru</i></span>
                </label>
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
                    <option value="lainnya" {{ old('id_kategori') == 'lainnya' || old('kategori_baru') ? 'selected' : '' }} style="color: #C85C2E; font-weight: 600;">
                        ➕ + Ajukan Kategori Baru Sendiri...
                    </option>
                </select>

                <!-- Input Kategori Baru (Muncul jika pilih 'lainnya') -->
                <div id="container_kategori_baru" class="mt-2.5 p-3 rounded-3 border {{ old('id_kategori') == 'lainnya' || old('kategori_baru') ? '' : 'd-none' }}" style="background: #FFFDF9; border-color: #FED7AA !important;">
                    <label class="form-label small fw-bold text-dark mb-1">
                        <i class="bi bi-pencil-square text-warning me-1"></i> Tulis Nama Kategori Baru yang Diinginkan <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="kategori_baru" 
                           id="kategori_baru" 
                           class="form-control form-control-sm rounded-2" 
                           placeholder="Misal: Warung Sate Plecing Khas Bali" 
                           value="{{ old('kategori_baru') }}"
                           maxlength="100">
                    <div class="small text-muted mt-1.5" style="font-size: 11.5px; line-height: 1.4;">
                        <i class="bi bi-info-circle text-primary me-1"></i> Kategori baru ini akan ditinjau dan diverifikasi oleh Admin bersamaan dengan verifikasi warung Anda.
                    </div>
                </div>
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
    const containerKategoriBaru = document.getElementById('container_kategori_baru');
    const inputKategoriBaru = document.getElementById('kategori_baru');

    function updateKategoriState() {
        if (!kategoriSelect) return;
        const val = kategoriSelect.value;
        const isLainnya = (val === 'lainnya');

        if (containerKategoriBaru) {
            if (isLainnya) {
                containerKategoriBaru.classList.remove('d-none');
                if (inputKategoriBaru) {
                    inputKategoriBaru.required = true;
                }
            } else {
                containerKategoriBaru.classList.add('d-none');
                if (inputKategoriBaru) {
                    inputKategoriBaru.required = false;
                }
            }
        }

        if (labelLayanan) {
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            if (selectedOption && selectedOption.value && !isLainnya) {
                const label = selectedOption.getAttribute('data-layanan');
                labelLayanan.textContent = label || 'Menerima Layanan Khusus';
            } else {
                labelLayanan.textContent = 'Menerima Layanan Khusus';
            }
        }
    }

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', updateKategoriState);
        updateKategoriState();
    }
});
</script>
@endpush
