@extends('admin.layouts.app')

@section('title', 'Kelola Statistik Website')

@section('content')

<style>
    .stat-card {
        border: 1.5px solid #F1F3F5;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        border-color: rgba(200, 92, 46, 0.3);
        box-shadow: 0 8px 25px rgba(200, 92, 46, 0.08);
    }
    .preview-section {
        background: linear-gradient(135deg, #FFF7ED 0%, #FEF3E2 50%, #FDF0E1 100%);
        border: 1.5px solid rgba(200, 92, 46, 0.18);
        border-radius: 22px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(200, 92, 46, 0.06);
    }
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
    }
    .preview-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 18px 12px;
        text-align: center;
        border: 1px solid rgba(200, 92, 46, 0.12);
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .preview-card h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #d97706;
        margin-bottom: 4px;
        line-height: 1.1;
    }
    .preview-card p {
        margin: 0;
        color: #6c5a52;
        font-size: 14px;
        font-weight: 600;
    }
    .option-card {
        border: 1.5px solid #E5E7EB;
        border-radius: 14px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #FAFAFA;
    }
    .option-card:hover {
        border-color: #C85C2E;
        background: #FFF7ED;
    }
    .option-card.selected {
        border-color: #C85C2E;
        background: #FFF7ED;
        box-shadow: 0 2px 8px rgba(200, 92, 46, 0.12);
    }
    .form-switch .form-check-input {
        width: 2.8em;
        height: 1.4em;
        cursor: pointer;
    }
    .form-switch .form-check-input:checked {
        background-color: #C85C2E;
        border-color: #C85C2E;
    }
    .btn-save-main {
        background-color: #C85C2E;
        border-color: #C85C2E;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-save-main:hover {
        background-color: #B34F24;
        border-color: #B34F24;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(200, 92, 46, 0.3);
    }
</style>

<div class="content-box px-2 pb-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pt-2">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Kelola Statistik Website</h3>
            <p class="text-muted small mb-0">
                Ubah angka statistik yang tampil di halaman depan website. Pilih apakah menggunakan data asli sistem atau angka kustom.
            </p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.statistik.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyamakan kembali semua statistik dengan data riil database?')">
                @csrf
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 py-2 text-dark small" style="font-size: 13.5px;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Samakan ke Data Asli DB
                </button>
            </form>
            <button type="button" class="btn btn-save-main text-white rounded-pill px-4 py-2 shadow-sm" style="font-size: 13.5px;" onclick="confirmAndSubmit()">
                <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Live Preview Halaman Utama -->
    <div class="preview-section mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-laptop text-warning fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Live Preview: Tampilan Statistik di Halaman Depan</h6>
            </div>
        </div>

        <div class="preview-grid" id="livePreviewGrid">
            @foreach($statistics as $stat)
                <div class="preview-card" id="prevCard_{{ $stat->id }}" style="{{ !$stat->is_active ? 'opacity: 0.4;' : '' }}">
                    <div class="text-muted small mb-1">
                        <i class="bi {{ $stat->icon ?? 'bi-bar-chart' }} text-warning"></i>
                    </div>
                    <h2 id="prevVal_{{ $stat->id }}">{{ $stat->getFormattedValue() }}</h2>
                    <p id="prevLabel_{{ $stat->id }}">{{ $stat->label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- FORM PENGATURAN STATISTIK -->
    <form action="{{ route('admin.statistik.update-all') }}" method="POST" id="mainStatForm">
        @csrf

        <div class="row g-4">
            @foreach($statistics as $stat)
                @php
                    $realVal = $stat->getRealValue();
                    $hasPlus = $stat->suffix === '+';
                @endphp
                <div class="col-12 col-md-6">
                    <div class="card stat-card p-4 h-100">
                        
                        <!-- Header Kartu -->
                        <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: #C85C2E; font-size: 18px;">
                                    <i class="bi {{ $stat->icon ?? 'bi-bar-chart' }}"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">{{ $stat->label }}</h5>
                                    <small class="text-muted">
                                        Data Asli Sistem: <strong class="text-success">{{ number_format($realVal) }}</strong>
                                    </small>
                                </div>
                            </div>
                            <!-- Switch Tampilkan di Web -->
                            <div class="form-check form-switch m-0" title="Tampilkan / Sembunyikan dari Halaman Depan">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                    name="stats[{{ $stat->id }}][is_active]" 
                                    value="1" 
                                    id="switch_{{ $stat->id }}" 
                                    {{ $stat->is_active ? 'checked' : '' }}
                                    onchange="onStatChange({{ $stat->id }}, {{ $realVal }})">
                            </div>
                        </div>

                        <!-- 1. Pilihan Mode: Otomatis vs Manual -->
                        <label class="form-label fw-bold text-dark small mb-2">Pilih Sumber Angka:</label>
                        <div class="row g-2 mb-3">
                            <!-- Opsi Otomatis -->
                            <div class="col-6">
                                <div class="option-card {{ $stat->source_type === 'auto' ? 'selected' : '' }}" 
                                     id="optAutoCard_{{ $stat->id }}" 
                                     onclick="selectSourceMode({{ $stat->id }}, 'auto', {{ $realVal }})">
                                    <div class="form-check p-0 m-0">
                                        <input class="form-check-input d-none" type="radio" 
                                               name="stats[{{ $stat->id }}][source_type]" 
                                               id="radioAuto_{{ $stat->id }}" 
                                               value="auto" 
                                               {{ $stat->source_type === 'auto' ? 'checked' : '' }}>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                            <i class="bi bi-cpu text-primary me-1"></i> Otomatis
                                        </div>
                                        <small class="text-muted" style="font-size: 11.5px;">
                                            Ikuti Data Asli ({{ number_format($realVal) }})
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi Manual -->
                            <div class="col-6">
                                <div class="option-card {{ $stat->source_type === 'manual' ? 'selected' : '' }}" 
                                     id="optManualCard_{{ $stat->id }}" 
                                     onclick="selectSourceMode({{ $stat->id }}, 'manual', {{ $realVal }})">
                                    <div class="form-check p-0 m-0">
                                        <input class="form-check-input d-none" type="radio" 
                                               name="stats[{{ $stat->id }}][source_type]" 
                                               id="radioManual_{{ $stat->id }}" 
                                               value="manual" 
                                               {{ $stat->source_type === 'manual' ? 'checked' : '' }}>
                                        <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                            <i class="bi bi-pencil-square text-warning me-1"></i> Manual
                                        </div>
                                        <small class="text-muted" style="font-size: 11.5px;">
                                            Ketik Angka Sendiri
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Kotak Input Angka Manual (Hanya Aktif jika Manual) -->
                        <div id="manualInputBox_{{ $stat->id }}" class="mb-3" style="{{ $stat->source_type === 'manual' ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold small text-dark">Masukkan Angka Manual:</label>
                            <input type="number" 
                                   name="stats[{{ $stat->id }}][manual_value]" 
                                   id="inputManual_{{ $stat->id }}" 
                                   class="form-control rounded-3" 
                                   value="{{ $stat->manual_value > 0 ? $stat->manual_value : $realVal }}" 
                                   min="0"
                                   placeholder="Contoh: 500"
                                   oninput="onStatChange({{ $stat->id }}, {{ $realVal }})">
                            <small class="text-muted" style="font-size: 11px;">Angka ini yang akan langsung tampil di website utama.</small>
                        </div>

                        <!-- 3. Tanda Tambahan '+' dan Label Teks -->
                        <div class="row g-2 mb-2">
                            <div class="col-7">
                                <label class="form-label fw-semibold small text-dark">Nama Judul Label:</label>
                                <input type="text" 
                                       name="stats[{{ $stat->id }}][label]" 
                                       id="inputLabel_{{ $stat->id }}" 
                                       class="form-control form-control-sm rounded-3" 
                                       value="{{ $stat->label }}" 
                                       required
                                       oninput="onStatChange({{ $stat->id }}, {{ $realVal }})">
                            </div>
                            <div class="col-5 d-flex align-items-end pb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="stats[{{ $stat->id }}][has_plus]" 
                                           value="1" 
                                           id="checkPlus_{{ $stat->id }}" 
                                           {{ $hasPlus ? 'checked' : '' }}
                                           onchange="onStatChange({{ $stat->id }}, {{ $realVal }})">
                                    <label class="form-check-label small text-dark fw-medium" for="checkPlus_{{ $stat->id }}">
                                        Tanda <strong>'+'</strong> (misal 50+)
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Konfirmasi Simpan di Bawah -->
        <div class="mt-4 p-3 px-4 bg-white rounded-4 border shadow-sm d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14.5px;">
                    <i class="bi bi-shield-check text-success me-1"></i> Terapkan Perubahan Statistik
                </h6>
                <small class="text-muted">Simpan konfigurasi dan langsung perbarui tampilan di halaman utama.</small>
            </div>
            <button type="button" class="btn btn-save-main text-white px-4 py-2 rounded-pill shadow-sm" style="font-size: 13.5px;" onclick="confirmAndSubmit()">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
        </div>

    </form>

</div>

<script>
    function selectSourceMode(id, mode, realVal) {
        document.getElementById('radioAuto_' + id).checked = (mode === 'auto');
        document.getElementById('radioManual_' + id).checked = (mode === 'manual');

        const optAuto = document.getElementById('optAutoCard_' + id);
        const optManual = document.getElementById('optManualCard_' + id);
        const manualBox = document.getElementById('manualInputBox_' + id);

        if (mode === 'auto') {
            optAuto.classList.add('selected');
            optManual.classList.remove('selected');
            manualBox.style.display = 'none';
        } else {
            optAuto.classList.remove('selected');
            optManual.classList.add('selected');
            manualBox.style.display = 'block';
            document.getElementById('inputManual_' + id).focus();
        }

        onStatChange(id, realVal);
    }

    function onStatChange(id, realVal) {
        const isAuto = document.getElementById('radioAuto_' + id).checked;
        const manualInput = document.getElementById('inputManual_' + id);
        const labelInput = document.getElementById('inputLabel_' + id);
        const plusCheck = document.getElementById('checkPlus_' + id);
        const switchActive = document.getElementById('switch_' + id);

        let valueNumber = 0;
        if (isAuto) {
            valueNumber = realVal;
        } else {
            valueNumber = parseInt(manualInput.value) || 0;
        }

        const formattedNumber = valueNumber.toLocaleString('id-ID') + (plusCheck.checked ? '+' : '');
        const labelText = labelInput.value || 'Statistik';

        // Update Live Preview Card
        const prevValEl = document.getElementById('prevVal_' + id);
        const prevLabelEl = document.getElementById('prevLabel_' + id);
        const prevCardEl = document.getElementById('prevCard_' + id);

        if (prevValEl) prevValEl.textContent = formattedNumber;
        if (prevLabelEl) prevLabelEl.textContent = labelText;
        if (prevCardEl) {
            prevCardEl.style.opacity = switchActive.checked ? '1' : '0.35';
        }
    }

    function confirmAndSubmit() {
        if (typeof window.showWarungBaliModal === 'function') {
            window.showWarungBaliModal({
                title: 'Simpan Statistik Website',
                message: 'Apakah Anda yakin ingin menerapkan perubahan data statistik ini ke halaman utama website?',
                confirmText: 'Ya, Simpan',
                variant: 'success',
                onConfirm: function() {
                    document.getElementById('mainStatForm').submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menerapkan perubahan statistik ini ke website?')) {
                document.getElementById('mainStatForm').submit();
            }
        }
    }
</script>

@endsection
