@extends('pemilik.layouts.app')

@section('title', 'Edit Warung Saya')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Edit Warung Saya</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($warung->status === 'rejected')
        <div class="alert alert-danger no-auto-dismiss rounded-4 p-3 mb-4 border-0 shadow-sm" style="background:#FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="fw-bold mb-1 text-danger"><i class="bi bi-exclamation-circle-fill me-1"></i> Catatan Penolakan dari Admin:</div>
            <div class="p-2.5 px-3 bg-white rounded-3 border border-danger-subtle text-danger fw-semibold small mb-2">
                <i class="bi bi-chat-left-quote-fill me-1"></i> "{{ $warung->alasan_penolakan ?: 'Data atau foto warung belum memenuhi syarat kelengkapan.' }}"
            </div>
            <small class="text-muted d-block">Silakan perbaiki data warung sesuai catatan di atas. Setelah Anda menyimpan perubahan, warung akan otomatis diajukan kembali ke admin untuk verifikasi ulang.</small>
        </div>
    @elseif($warung->status === 'approved')
        <div class="alert alert-info small no-auto-dismiss rounded-3 p-3 mb-4">
            <i class="bi bi-info-circle me-1"></i>
            Warung Anda saat ini sudah disetujui & tayang. Jika Anda mengubah informasi warung, data akan ditinjau ulang oleh admin sebelum diperbarui di website.
        </div>
    @endif

    <form action="{{ route('pemilik.warung.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Warung</label>
            <input type="text" name="nama_warung" class="form-control" value="{{ old('nama_warung', $warung->nama_warung) }}" required>
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
                                {{ old('id_kategori', $warung->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }} {{ $k->status === 'pending' ? '(Sedang Diajukan)' : '' }}
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
                        <option value="{{ $k->id_kabupaten }}" {{ old('id_kabupaten', $warung->id_kabupaten) == $k->id_kabupaten ? 'selected' : '' }}>
                            {{ $k->nama_kabupaten }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $warung->alamat) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $warung->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $warung->telepon) }}">
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

        <div class="mb-3 form-check">
            <input type="hidden" name="menerima_catering" value="0">
            <input type="checkbox" name="menerima_catering" id="menerima_catering" class="form-check-input" value="1" {{ old('menerima_catering', $warung->menerima_catering) ? 'checked' : '' }}>
            <label class="form-check-label" for="menerima_catering" id="label_layanan_checkbox">
                {{ $warung->layanan_label }}
            </label>
        </div>

        <div class="mb-4">
            <label class="form-label">Foto Warung</label><br>

            @if($warung->foto && file_exists(public_path('images/warung/'.$warung->foto)))
                <img src="{{ asset('images/warung/'.$warung->foto) }}" style="width:100px;height:100px;object-fit:cover;border-radius:6px;" class="mb-2">
            @else
                <div class="text-muted small mb-2">Belum ada foto</div>
            @endif

            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
        </div>

        <div class="p-3 bg-light rounded-4 border mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-white shadow-sm text-success" style="width: 42px; height: 42px; font-size: 22px;">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 13.5px;">Butuh Bantuan Perubahan Data Warung oleh Admin?</h6>
                    <small class="text-muted">Jika membutuhkan bantuan verifikasi cepat atau penyesuaian khusus, hubungi Admin WarungBali.id.</small>
                </div>
            </div>
            <a href="https://wa.me/6282146789679?text=Halo%20Admin%20WarungBali.id,%20saya%20pemilik%20warung%20membutuhkan%20bantuan%20data%20warung" target="_blank" rel="noopener" class="btn btn-success btn-sm rounded-pill px-3 py-2 text-nowrap fw-semibold shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-whatsapp"></i> Hubungi Admin (0821-4678-9679)
            </a>
        </div>

        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">Kembali</a>

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
