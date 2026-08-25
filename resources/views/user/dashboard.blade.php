@extends('layouts.app')

@section('content')

<style>
    :root {
        --wb-primary: #C85C2E;
        --wb-primary-hover: #B34F24;
        --wb-primary-soft: rgba(200, 92, 46, 0.08);
        --wb-cream: #FFF7EF;
        --wb-card-border: rgba(200, 92, 46, 0.12);
    }

    .user-dash-container {
        padding-top: 2rem;
        padding-bottom: 4rem;
        background: #faf7f2;
        min-height: calc(100vh - 80px);
    }

    /* Hero Profile Banner */
    .user-hero-card {
        background: linear-gradient(135deg, #2D201C 0%, #442E25 50%, #C85C2E 100%);
        border-radius: 24px;
        color: #ffffff;
        padding: 2.2rem 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(45, 32, 28, 0.15);
    }

    .user-hero-card::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .avatar-img-main {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }

    .avatar-initial-main {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #C85C2E, #f59e0b);
        color: white;
        font-size: 2.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255, 255, 255, 0.85);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }

    /* Stats Cards */
    .stat-card-dash {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--wb-card-border);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .stat-card-dash:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(200, 92, 46, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    /* Navigation Tabs */
    .nav-dash-pills {
        background: #ffffff;
        border-radius: 20px;
        padding: 0.5rem;
        border: 1px solid var(--wb-card-border);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .nav-dash-pills .nav-link {
        border-radius: 14px;
        color: #52525b;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.75rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s ease;
    }

    .nav-dash-pills .nav-link:hover {
        color: var(--wb-primary);
        background: var(--wb-primary-soft);
    }

    .nav-dash-pills .nav-link.active {
        background: var(--wb-primary);
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(200, 92, 46, 0.35);
    }

    /* Content Cards */
    .dash-main-card {
        background: #ffffff;
        border-radius: 22px;
        border: 1px solid var(--wb-card-border);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        padding: 2rem;
    }

    .section-title {
        font-weight: 700;
        color: #2D201C;
        font-size: 1.3rem;
        margin-bottom: 0.3rem;
        letter-spacing: -0.3px;
    }

    .btn-wb-primary {
        background: var(--wb-primary);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.65rem 1.4rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-wb-primary:hover {
        background: var(--wb-primary-hover);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(200, 92, 46, 0.25);
    }

    .btn-wb-outline {
        border: 1.5px solid var(--wb-primary);
        color: var(--wb-primary);
        background: transparent;
        border-radius: 12px;
        padding: 0.65rem 1.4rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-wb-outline:hover {
        background: var(--wb-primary-soft);
        color: var(--wb-primary);
    }

    .form-control:focus {
        border-color: var(--wb-primary);
        box-shadow: 0 0 0 0.25rem rgba(200, 92, 46, 0.15);
    }

    /* Review Item Box */
    .review-dash-item {
        background: #fdfdfd;
        border-radius: 16px;
        border: 1px solid #f1f1f4;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }

    .review-dash-item:hover {
        background: #ffffff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        border-color: var(--wb-card-border);
    }

    .security-notice-box {
        background: linear-gradient(135deg, #FFF7ED, #FFEDD5);
        border: 1.5px dashed #FDBA74;
        border-radius: 18px;
        padding: 1.5rem;
    }
</style>

<div class="user-dash-container">
    <div class="container">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert" style="background:#ECFDF5; color:#065F46;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4 p-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div class="fw-medium">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-3" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Terjadi kesalahan pengisian form:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $hasUserFoto = false;
            $userFotoUrl = '';
            if (!empty($user->foto)) {
                if (filter_var($user->foto, FILTER_VALIDATE_URL)) {
                    $hasUserFoto = true;
                    $userFotoUrl = $user->foto;
                } elseif (file_exists(public_path('images/avatars/' . $user->foto))) {
                    $hasUserFoto = true;
                    $userFotoUrl = asset('images/avatars/' . $user->foto);
                }
            }
            $initialDepan = strtoupper(substr(trim($user->nama ?: 'U'), 0, 1));
        @endphp

        <!-- Hero Profile Banner -->
        <div class="user-hero-card mb-4">
            <div class="row align-items-center gy-3">
                <div class="col-auto">
                    <div class="avatar-wrapper">
                        @if($hasUserFoto)
                            <img src="{{ $userFotoUrl }}" alt="{{ $user->nama }}" class="avatar-img-main" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="avatar-initial-main" style="display:none;">
                                {{ $initialDepan }}
                            </div>
                        @else
                            <div class="avatar-initial-main">
                                {{ $initialDepan }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h2 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px;">{{ $user->nama }}</h2>
                        @if(!empty($user->google_id))
                            <span class="badge bg-white text-dark rounded-pill px-2 py-1 small fw-semibold" style="font-size: 11px;">
                                <i class="bi bi-google text-danger me-1"></i> Akun Google
                            </span>
                        @endif
                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 rounded-pill px-3 py-1 small fw-semibold">
                            <i class="bi bi-patch-check-fill me-1"></i> Pecinta Kuliner Bali
                        </span>
                    </div>
                    <p class="mb-2 text-white-50 small">
                        <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                    </p>
                    <div class="d-flex flex-wrap gap-3 text-white-50 small mt-2">
                        <span><i class="bi bi-heart-fill text-danger me-1"></i> <strong>{{ $totalFavorit }}</strong> Warung Favorit</span>
                        <span>•</span>
                        <span><i class="bi bi-chat-quote-fill text-warning me-1"></i> <strong>{{ $totalReview }}</strong> Ulasan Ditulis</span>
                        <span>•</span>
                        <span><i class="bi bi-shield-check text-success me-1"></i> Status: <strong class="text-white">Aktif</strong></span>
                    </div>
                </div>
                <div class="col-lg-auto text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('home') }}#warung" class="btn btn-light rounded-pill px-3 py-2 fw-semibold shadow-sm text-dark d-inline-flex align-items-center gap-2" style="font-size: 13.5px;">
                        <i class="bi bi-compass"></i> Jelajah Warung Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-dash d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-heart-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark mb-0">{{ $totalFavorit }}</div>
                        <div class="text-muted small">Warung Favorit</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-dash d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark mb-0">{{ $totalReview }}</div>
                        <div class="text-muted small">Ulasan Diberikan</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-dash d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark mb-0">Aman</div>
                        <div class="text-muted small">Keamanan Akun</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-dash d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark mb-0">{{ $myWarung ? 'Mitra' : 'User' }}</div>
                        <div class="text-muted small">Status Peran</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Navigation Sidebar (Pills) -->
            <div class="col-lg-3">
                <div class="nav-dash-pills sticky-top" style="top: 90px; z-index: 10;">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link {{ $activeTab === 'ringkasan' ? 'active' : '' }}" 
                                id="v-pills-ringkasan-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-ringkasan" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'ringkasan' ? 'true' : 'false' }}">
                            <i class="bi bi-grid-1x2-fill"></i> Ringkasan
                        </button>
                        <button class="nav-link {{ $activeTab === 'profil' ? 'active' : '' }}" 
                                id="v-pills-profil-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-profil" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'profil' ? 'true' : 'false' }}">
                            <i class="bi bi-person-circle"></i> Kelola Profil
                        </button>
                        <button class="nav-link {{ $activeTab === 'keamanan' ? 'active' : '' }}" 
                                id="v-pills-keamanan-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-keamanan" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'keamanan' ? 'true' : 'false' }}">
                            <i class="bi bi-shield-lock"></i> Keamanan & Password
                        </button>
                        <button class="nav-link {{ $activeTab === 'favorit' ? 'active' : '' }}" 
                                id="v-pills-favorit-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-favorit" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'favorit' ? 'true' : 'false' }}">
                            <i class="bi bi-heart"></i> Warung Favorit
                            <span class="badge bg-white text-dark ms-auto rounded-pill px-2">{{ $totalFavorit }}</span>
                        </button>
                        <button class="nav-link {{ $activeTab === 'ulasan' ? 'active' : '' }}" 
                                id="v-pills-ulasan-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-ulasan" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'ulasan' ? 'true' : 'false' }}">
                            <i class="bi bi-chat-left-quote"></i> Ulasan Saya
                            <span class="badge bg-white text-dark ms-auto rounded-pill px-2">{{ $totalReview }}</span>
                        </button>
                        <button class="nav-link {{ $activeTab === 'mitra' ? 'active' : '' }}" 
                                id="v-pills-mitra-tab" 
                                data-bs-toggle="pill" 
                                data-bs-target="#v-pills-mitra" 
                                type="button" role="tab" aria-selected="{{ $activeTab === 'mitra' ? 'true' : 'false' }}">
                            <i class="bi bi-shop-window"></i> Kemitraan Warung
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">

                    <!-- ================= TAB 1: RINGKASAN ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'ringkasan' ? 'show active' : '' }}" id="v-pills-ringkasan" role="tabpanel">
                        <div class="dash-main-card mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <div>
                                    <h4 class="section-title">Om Swastyastu, {{ $user->nama }}!</h4>
                                    <p class="text-muted small mb-0">Selamat datang di pusat kendali akun pengguna WarungBali.id Anda.</p>
                                </div>
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-2">
                                    <i class="bi bi-calendar3 me-1"></i> {{ date('d M Y') }}
                                </span>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light h-100 border">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h6 class="fw-bold mb-0 text-dark">
                                                <i class="bi bi-heart-fill text-danger me-2"></i> Favorit Terbaru
                                            </h6>
                                            <button class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold" style="color:var(--wb-primary);" onclick="switchTab('v-pills-favorit-tab')">
                                                Lihat Semua
                                            </button>
                                        </div>

                                        @if($warungFavorit->count() > 0)
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($warungFavorit->take(3) as $fav)
                                                    <a href="{{ route('home') }}#warung-{{ $fav->id_warung }}" class="d-flex align-items-center gap-3 p-2 bg-white rounded-3 text-decoration-none text-dark border-0 shadow-sm">
                                                        <div class="bg-light rounded-3 overflow-hidden" style="width:48px;height:48px;flex-shrink:0;">
                                                            @if($fav->foto)
                                                                <img src="{{ asset('images/warung/' . $fav->foto) }}" alt="{{ $fav->nama_warung }}" style="width:100%;height:100%;object-fit:cover;">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-shop"></i></div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <div class="fw-bold small text-truncate">{{ $fav->nama_warung }}</div>
                                                            <div class="text-muted" style="font-size: 11px;">
                                                                <i class="bi bi-geo-alt"></i> {{ $fav->kabupaten->nama_kabupaten ?? 'Bali' }}
                                                            </div>
                                                        </div>
                                                        <div class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">
                                                            <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($fav->review->avg('rating') ?? 0, 1) }}
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bi bi-heart text-muted fs-3 d-block mb-2"></i>
                                                <p class="text-muted small mb-2">Belum ada warung favorit yang disimpan.</p>
                                                <a href="{{ route('home') }}#warung" class="btn btn-sm btn-wb-outline">Cari Warung</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light h-100 border">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <h6 class="fw-bold mb-0 text-dark">
                                                <i class="bi bi-chat-quote-fill text-warning me-2"></i> Ulasan Terbaru
                                            </h6>
                                            <button class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold" style="color:var(--wb-primary);" onclick="switchTab('v-pills-ulasan-tab')">
                                                Lihat Semua
                                            </button>
                                        </div>

                                        @if($myReviews->count() > 0)
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($myReviews->take(3) as $rev)
                                                    <div class="p-2 bg-white rounded-3 shadow-sm">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span class="fw-bold small text-dark">{{ $rev->warung->nama_warung ?? 'Warung Bali' }}</span>
                                                            <span class="text-warning small">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                                                @endfor
                                                            </span>
                                                        </div>
                                                        <p class="text-muted small mb-0 text-truncate" style="font-size:12px;">
                                                            "{{ $rev->komentar ?: 'Tidak ada komentar tertulis.' }}"
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bi bi-chat-dots text-muted fs-3 d-block mb-2"></i>
                                                <p class="text-muted small mb-2">Belum ada ulasan yang Anda tulis.</p>
                                                <a href="{{ route('home') }}#warung" class="btn btn-sm btn-wb-outline">Beri Ulasan Warung</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Fast Action Banner -->
                            <div class="p-4 rounded-4 text-white d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" 
                                 style="background: linear-gradient(135deg, #C85C2E, #d97706);">
                                <div>
                                    <h5 class="fw-bold mb-1"><i class="bi bi-shield-check me-2"></i> Pengaturan Akun & Keamanan</h5>
                                    <p class="mb-0 small text-white-50">Perbarui profil Anda atau atur password baru dengan aman kapan saja.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-light rounded-pill px-3 py-2 fw-semibold text-dark btn-sm" onclick="switchTab('v-pills-profil-tab')">
                                        Edit Profil
                                    </button>
                                    <button class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold btn-sm" onclick="switchTab('v-pills-keamanan-tab')">
                                        Ganti Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2: KELOLA PROFIL ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'profil' ? 'show active' : '' }}" id="v-pills-profil" role="tabpanel">
                        <div class="dash-main-card">
                            <div class="mb-4 pb-2 border-bottom">
                                <h4 class="section-title">Kelola Profil Saya</h4>
                                <p class="text-muted small mb-0">Perbarui data pribadi dan foto identitas akun Anda.</p>
                            </div>

                            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Foto Profil Section -->
                                <div class="mb-4 p-3 bg-light rounded-4 border">
                                    <label class="form-label fw-bold text-dark small mb-3">Foto Profil / Avatar</label>
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="avatar-wrapper">
                                            @if($hasUserFoto)
                                                <img id="avatarPreview" src="{{ $userFotoUrl }}" alt="{{ $user->nama }}" class="avatar-img-main" onerror="this.style.display='none'; document.getElementById('avatarPlaceholder').classList.remove('d-none');">
                                                <div id="avatarPlaceholder" class="avatar-initial-main d-none">
                                                    {{ $initialDepan }}
                                                </div>
                                            @else
                                                <div id="avatarPlaceholder" class="avatar-initial-main">
                                                    {{ $initialDepan }}
                                                </div>
                                                <img id="avatarPreview" src="" alt="Preview" class="avatar-img-main d-none">
                                            @endif
                                        </div>

                                        <div class="flex-grow-1">
                                            <input type="file" name="foto" id="fotoInput" class="form-control form-control-sm mb-2" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewAvatar(this)">
                                            <div class="text-muted small" style="font-size:12px;">
                                                Format: JPG, PNG, WebP. Maksimal 3MB.
                                            </div>
                                        </div>

                                        @if($hasUserFoto)
                                            <div>
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="confirmHapusFoto()">
                                                    <i class="bi bi-trash me-1"></i> Hapus Foto
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required placeholder="Nama lengkap Anda">
                                        </div>
                                        @error('nama')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-dark">Alamat Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required placeholder="Alamat email Anda">
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-wb-primary px-4">
                                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Profil
                                    </button>
                                </div>
                            </form>

                            <!-- Form tersembunyi untuk hapus foto -->
                            <form id="formHapusFoto" action="{{ route('user.profile.remove-foto') }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <!-- ================= TAB 3: KEAMANAN & PASSWORD ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'keamanan' ? 'show active' : '' }}" id="v-pills-keamanan" role="tabpanel">
                        <div class="dash-main-card mb-4">
                            <div class="mb-4 pb-2 border-bottom">
                                <h4 class="section-title">Keamanan & Ubah Password</h4>
                                <p class="text-muted small mb-0">Kelola kata sandi akun Anda atau minta password baru yang dikirimkan via email.</p>
                            </div>

                            <!-- Opsi 1: Ubah Password Mandiri -->
                            <div class="mb-5">
                                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-key-fill text-primary me-2"></i> Ganti Password Secara Mandiri</h6>
                                
                                <form action="{{ route('user.password.update') }}" method="POST">
                                    @csrf
                                    
                                    @if(!empty($user->password))
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold small text-dark">Password Saat Ini <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i class="bi bi-lock"></i></span>
                                                <input type="password" name="current_password" id="curPass" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password lama" required>
                                                <button class="btn btn-outline-secondary bg-white text-muted" type="button" onclick="togglePassField('curPass', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-dark">Password Baru <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i class="bi bi-shield-lock"></i></span>
                                                <input type="password" name="password" id="newPass" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                                                <button class="btn btn-outline-secondary bg-white text-muted" type="button" onclick="togglePassField('newPass', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-dark">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-muted"><i class="bi bi-shield-check"></i></span>
                                                <input type="password" name="password_confirmation" id="confPass" class="form-control" placeholder="Ketik ulang password baru" required>
                                                <button class="btn btn-outline-secondary bg-white text-muted" type="button" onclick="togglePassField('confPass', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-wb-primary">
                                        <i class="bi bi-shield-check me-1"></i> Perbarui Password
                                    </button>
                                </form>
                            </div>

                            <hr class="my-4">

                            <!-- Opsi 2: Minta Password Baru via Email -->
                            <div class="security-notice-box">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">
                                            <i class="bi bi-envelope-check-fill text-warning me-2"></i> Lupa Password Lama atau Ingin Password Baru via Email?
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            Sistem WarungBali.id akan men-generate password baru yang aman dan langsung mengirimkannya ke alamat email Anda (<strong>{{ $user->email }}</strong>).
                                        </p>
                                    </div>
                                    <div>
                                        <form action="{{ route('user.password.request-email') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat password baru dan mengirimkannya ke email {{ $user->email }}? Password lama tidak akan berlaku lagi.');">
                                            @csrf
                                            <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2 text-nowrap">
                                                <i class="bi bi-send-fill"></i> Kirim Password ke Email
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 4: WARUNG FAVORIT ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'favorit' ? 'show active' : '' }}" id="v-pills-favorit" role="tabpanel">
                        <div class="dash-main-card">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <div>
                                    <h4 class="section-title">Warung Favorit Saya</h4>
                                    <p class="text-muted small mb-0">Daftar warung dan destinasi kuliner Bali yang telah Anda simpan.</p>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">
                                    <i class="bi bi-heart-fill me-1"></i> {{ $totalFavorit }} Warung
                                </span>
                            </div>

                            @if($warungFavorit->isEmpty())
                                <div class="alert alert-warning text-center rounded-4 p-5">
                                    <div class="fs-1 mb-3 text-muted"><i class="bi bi-heart"></i></div>
                                    <h5 class="fw-bold">Belum Ada Warung Favorit</h5>
                                    <p class="text-secondary mb-3">
                                        Klik tanda hati pada kartu warung yang Anda sukai untuk menyimpannya di daftar favorit.
                                    </p>
                                    <a href="{{ route('home') }}#warung" class="btn btn-wb-primary">
                                        <i class="bi bi-compass me-1"></i> Jelajahi Warung Bali
                                    </a>
                                </div>
                            @else
                                <div class="row g-4">
                                    @foreach($warungFavorit as $item)
                                        <div class="col-md-6">
                                            @include('partials.warung-card', ['item' => $item])
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ================= TAB 5: ULASAN SAYA ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'ulasan' ? 'show active' : '' }}" id="v-pills-ulasan" role="tabpanel">
                        <div class="dash-main-card">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <div>
                                    <h4 class="section-title">Ulasan & Penilaian Saya</h4>
                                    <p class="text-muted small mb-0">Semua ulasan kuliner yang pernah Anda tulis untuk warung-warung di WarungBali.id.</p>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-50 rounded-pill px-3 py-2">
                                    <i class="bi bi-chat-quote-fill me-1"></i> {{ $totalReview }} Ulasan
                                </span>
                            </div>

                            @if($myReviews->isEmpty())
                                <div class="text-center py-5">
                                    <i class="bi bi-chat-square-text text-muted opacity-50 fs-1 d-block mb-3"></i>
                                    <h5 class="fw-bold text-dark">Belum Pernah Memberi Ulasan</h5>
                                    <p class="text-muted small mb-3">Bagikan pengalaman kuliner Anda kepada pencinta kuliner Bali lainnya!</p>
                                    <a href="{{ route('home') }}#warung" class="btn btn-wb-primary">
                                        <i class="bi bi-search me-1"></i> Cari Warung untuk Diulas
                                    </a>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3">
                                    @foreach($myReviews as $rev)
                                        <div class="review-dash-item">
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                <div>
                                                    <h6 class="fw-bold mb-1 text-dark">
                                                        <i class="bi bi-shop text-danger me-1"></i> {{ $rev->warung->nama_warung ?? 'Warung Kuliner Bali' }}
                                                    </h6>
                                                    <div class="text-muted small" style="font-size: 11.5px;">
                                                        <i class="bi bi-geo-alt"></i> {{ $rev->warung->kabupaten->nama_kabupaten ?? 'Bali' }} • 
                                                        <i class="bi bi-calendar3"></i> {{ $rev->created_at ? \Carbon\Carbon::parse($rev->created_at)->format('d M Y, H:i') : '-' }}
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <form action="{{ route('user.review.delete', $rev->id_review) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width:28px;height:28px;" title="Hapus Ulasan">
                                                            <i class="bi bi-trash" style="font-size:12px;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="p-3 bg-white rounded-3 border small text-secondary">
                                                "{{ $rev->komentar ?: 'Tidak ada komentar tertulis.' }}"
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- ================= TAB 6: KEMITRAAN WARUNG ================= -->
                    <div class="tab-pane fade {{ $activeTab === 'mitra' ? 'show active' : '' }}" id="v-pills-mitra" role="tabpanel">
                        <div class="dash-main-card">
                            <div class="mb-4 pb-2 border-bottom">
                                <h4 class="section-title">Kemitraan & Pendaftaran Warung</h4>
                                <p class="text-muted small mb-0">Punya usaha kuliner di Bali? Daftarkan warung Anda agar dapat dijangkau oleh ribuan pengunjung.</p>
                            </div>

                            @if($myWarung)
                                <div class="p-4 rounded-4 bg-light border mb-3">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <h5 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-shop me-2" style="color:var(--wb-primary);"></i> {{ $myWarung->nama_warung }}
                                        </h5>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">
                                            Status: {{ ucfirst($myWarung->status ?? 'Aktif') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Warung Anda sudah terdaftar di sistem WarungBali.id. Anda dapat mengelola menu, cabang, dan informasi warung secara lengkap melalui Dashboard Pemilik.
                                    </p>
                                    <a href="{{ route('pemilik.dashboard') }}" class="btn btn-wb-primary">
                                        <i class="bi bi-speedometer2 me-1"></i> Buka Dashboard Pemilik Warung
                                    </a>
                                </div>
                            @else
                                <div class="row align-items-center g-4 p-4 rounded-4" style="background: linear-gradient(135deg, #FFF7EF, #FFEAD4); border: 1.5px solid rgba(200, 92, 46, 0.2);">
                                    <div class="col-md-8">
                                        <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold rounded-pill px-3 py-1 mb-2">
                                            <i class="bi bi-stars me-1"></i> Mitra Kuliner Bali
                                        </span>
                                        <h4 class="fw-bold text-dark mb-2">Promosikan Warung Anda di WarungBali.id</h4>
                                        <p class="text-secondary small mb-3">
                                            Dapatkan kemudahan menampilkan daftar menu lezat, lokasi cabang, jam buka, dan menerima ulasan langsung dari pencinta kuliner tanpa biaya pendaftaran!
                                        </p>
                                        <a href="{{ route('pemilik.warung.panduan') }}" class="btn btn-wb-primary px-4 py-2">
                                            <i class="bi bi-info-circle me-1"></i> Panduan & Pendaftaran Warung
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <i class="bi bi-shop text-danger" style="font-size: 5rem; opacity: 0.85;"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.classList.remove('d-none');
                if (placeholder) {
                    placeholder.style.display = 'none';
                    placeholder.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassField(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function switchTab(tabId) {
        const triggerEl = document.getElementById(tabId);
        if (triggerEl) {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
            window.scrollTo({ top: 150, behavior: 'smooth' });
        }
    }

    function confirmHapusFoto() {
        if (typeof window.showWarungBaliModal === 'function') {
            window.showWarungBaliModal({
                title: 'Konfirmasi Hapus Foto',
                message: 'Apakah Anda yakin ingin menghapus foto profil Anda dan kembali menggunakan inisial nama?',
                icon: 'bi bi-trash-fill',
                variant: 'danger',
                confirmText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                onConfirm: function () {
                    document.getElementById('formHapusFoto').submit();
                }
            });
        } else {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil Anda?')) {
                document.getElementById('formHapusFoto').submit();
            }
        }
    }
</script>

@endsection
