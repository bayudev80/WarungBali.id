@extends('layouts.app')

@section('content')

<style>
    .panduan-container {
        padding-top: 2.5rem;
        padding-bottom: 4rem;
        background: #faf7f2;
        min-height: calc(100vh - 80px);
    }
    .panduan-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(200, 92, 46, 0.12);
        box-shadow: 0 10px 35px rgba(0,0,0,0.04);
        padding: 2.5rem;
    }
    .step-box {
        background: #fdfaf6;
        border: 1px solid rgba(200, 92, 46, 0.15);
        border-radius: 18px;
        padding: 1.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .step-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(200, 92, 46, 0.08);
    }
    .step-number {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #C85C2E, #d97706);
        color: #ffffff;
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .account-badge-box {
        background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
        border: 1.5px solid #FDBA74;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
    }
    .faq-accordion .accordion-button:not(.collapsed) {
        background-color: #FFF7ED;
        color: #C85C2E;
        box-shadow: none;
    }
    .faq-accordion .accordion-item {
        border-radius: 14px !important;
        border: 1px solid rgba(200, 92, 46, 0.12);
        overflow: hidden;
        margin-bottom: 0.75rem;
    }
</style>

<div class="panduan-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">

                <div class="panduan-card">

                    <!-- Header -->
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold rounded-pill px-3 py-2 mb-2">
                            <i class="bi bi-shop-window me-1"></i> Kemitraan Kuliner Bali
                        </span>
                        <h2 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">Panduan Pendaftaran Warung</h2>
                        <p class="text-secondary small mb-0 mx-auto" style="max-width: 600px;">
                            Pelajari alur pendaftaran usaha kuliner Anda menjadi bagian dari direktori WarungBali.id menggunakan akun yang sedang aktif.
                        </p>
                    </div>

                    <!-- Status Akun Pengguna Saat Ini -->
                    <div class="p-3 p-sm-4 mb-4 rounded-4 shadow-sm border bg-white" style="border-color: rgba(200, 92, 46, 0.2) !important;">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm text-white flex-shrink-0"
                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #C85C2E, #d97706); font-size: 1.2rem;">
                                    {{ strtoupper(substr(trim(auth()->user()->nama ?: 'U'), 0, 1)) }}
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h6 class="fw-bold text-dark mb-0 fs-6">{{ auth()->user()->nama }}</h6>
                                        <span class="badge bg-success text-white rounded-pill px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                            <i class="bi bi-check-circle me-1"></i> Akun Terdaftar
                                        </span>
                                    </div>
                                    <div class="text-secondary small">
                                        <i class="bi bi-envelope me-1"></i> {{ auth()->user()->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-md-end">
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small fw-semibold shadow-sm" style="font-size: 12.5px;">
                                    <i class="bi bi-shield-check text-success me-1"></i> Password Anda Tetap Sama
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Penjelasan Alur 3 Langkah -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-signpost-2 text-primary me-2"></i> 3 Langkah Mudah Mendaftarkan Warung
                        </h5>

                        <div class="d-flex flex-column gap-3">
                            <!-- Langkah 1 -->
                            <div class="step-box d-flex align-items-start gap-3">
                                <div class="step-number">1</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Pengisian Profil & Data Warung</h6>
                                    <p class="text-secondary small mb-0">
                                        Lengkapi informasi warung kuliner Anda seperti nama warung, kategori kuliner, alamat lengkap kabupaten, jam buka-tutup, rentang harga, dan foto warung terbaik Anda.
                                    </p>
                                </div>
                            </div>

                            <!-- Langkah 2 -->
                            <div class="step-box d-flex align-items-start gap-3">
                                <div class="step-number">2</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Peninjauan & Verifikasi oleh Admin</h6>
                                    <p class="text-secondary small mb-0">
                                        Pengajuan Anda akan ditinjau oleh tim administrator WarungBali.id untuk memastikan kelengkapan informasi warung. Anda tidak perlu menunggu pengiriman password baru karena akun Anda sudah aktif.
                                    </p>
                                </div>
                            </div>

                            <!-- Langkah 3 -->
                            <div class="step-box d-flex align-items-start gap-3">
                                <div class="step-number">3</div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Warung Resmi Tayang & Akses Dashboard Pemilik</h6>
                                    <p class="text-secondary small mb-0">
                                        Setelah disetujui, akun Anda otomatis memiliki akses ke <strong>Dashboard Pemilik Warung</strong> untuk mengelola daftar menu, menambahkan cabang warung, dan memantau ulasan dari pengunjung.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Singkat -->
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-question-circle text-warning me-2"></i> Pertanyaan yang Sering Diajukan
                        </h5>

                        <div class="accordion faq-accordion" id="accordionFaqPanduan">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed fw-semibold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Apakah password login saya akan berubah setelah mendaftarkan warung?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFaqPanduan">
                                    <div class="accordion-body small text-secondary">
                                        <strong>Tidak.</strong> Karena Anda sudah mendaftar dan login sebagai pengguna, Anda tetap menggunakan email dan password yang Anda gunakan saat ini. Fitur password yang dikirim via email oleh admin hanya berlaku bagi pendaftar baru yang mendaftar melalui form registrasi pemilik di luar sesi login.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed fw-semibold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Apakah data ulasan dan favorit saya sebelumnya akan hilang?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaqPanduan">
                                    <div class="accordion-body small text-secondary">
                                        <strong>Tidak hilang sama sekali.</strong> Akun Anda tetap mempertahankan semua riwayat ulasan, penilaian, serta warung favorit yang pernah Anda simpan di WarungBali.id.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-semibold w-100 w-sm-auto">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>

                        <a href="{{ route('pemilik.warung.create') }}" class="btn btn-sm rounded-pill text-white px-4 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm w-100 w-sm-auto"
                           style="background: linear-gradient(135deg, #C85C2E, #d97706); font-size:14.5px; font-weight:600; border:none;">
                            <span>Lanjutkan Pendaftaran Warung</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection
