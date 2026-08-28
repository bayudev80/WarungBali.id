<nav class="navbar navbar-expand-lg py-2 main-navbar {{ request()->routeIs('home') || request()->routeIs('tentang') ? 'navbar-transparent' : 'navbar-scrolled' }}" id="mainNavbar">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold gap-2" href="/">
            <img src="{{ asset('images/logo.png') }}"
                alt="WarungBali"
                width="40"
                height="40"
                class="me-1"
                style="object-fit:contain;">

            <span class="navbar-brand-text">
                Warungbali<span class="navbar-brand-dot">.id</span>
            </span>
        </a>

        <button class="navbar-toggler shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <!-- Menu Tengah -->
            <ul class="navbar-nav mx-auto my-2 my-lg-0">

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 py-2 rounded-pill nav-custom-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 py-2 rounded-pill nav-custom-link {{ request()->routeIs('favorit.index') ? 'active' : '' }}"
                        href="{{ route('favorit.index') }}">
                        Favorit Saya
                    </a>
                </li>

                <li class="nav-item mx-1">
                    <a class="nav-link px-3 py-2 rounded-pill nav-custom-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                        href="{{ route('tentang') }}">
                        Tentang Kami
                    </a>
                </li>

            </ul>

            <!-- Tombol Kanan -->
            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">

                @guest
                    <!-- Group Masuk/Daftar -->
                    <div class="d-flex align-items-center gap-3 border-end pe-3 auth-divider">
                        <a href="{{ route('login') }}" 
                            class="text-decoration-none auth-link-text">
                            Masuk
                        </a>
                        
                        <a href="{{ route('register') }}" 
                            class="btn btn-sm rounded-pill px-3 py-2 auth-btn-register">
                            Daftar
                        </a>
                    </div>

                    <!-- Tombol Utama Daftarkan Warung -->
                    <a href="{{ route('register', ['role' => 'pemilik']) }}"
                        class="btn btn-sm rounded-pill text-white px-3 py-2 d-flex align-items-center gap-2 shadow-sm cta-warung-btn"
                        style="background: linear-gradient(135deg, #C85C2E, #d97706); font-size:13.5px; font-weight:600; border:none;">
                        <i class="bi bi-shop"></i> Daftarkan Warung
                    </a>
                @endguest

                @auth
                    @if(Auth::user()->role === 'pemilik')
                        <a href="{{ route('pemilik.dashboard') }}"
                            class="btn btn-sm rounded-pill text-white px-3 py-2 me-2 d-flex align-items-center gap-2 shadow-sm cta-warung-btn"
                            style="background: linear-gradient(135deg, #d97706, #b45309); font-size:13.5px; font-weight:600; border:none;">
                            <i class="bi bi-speedometer2"></i> Kelola Warung
                        </a>
                    @elseif(Auth::user()->role !== 'admin')
                        <a href="{{ route('pemilik.warung.panduan') }}"
                            class="btn btn-sm rounded-pill text-white px-3 py-2 me-2 d-flex align-items-center gap-2 shadow-sm cta-warung-btn"
                            style="background: linear-gradient(135deg, #C85C2E, #d97706); font-size:13.5px; font-weight:600; border:none;">
                            <i class="bi bi-shop"></i> Daftarkan Warung
                        </a>
                    @endif

                    <!-- Dropdown Avatar -->
                    <div class="dropdown">
                        <a href="#"
                            class="d-flex align-items-center text-decoration-none dropdown-toggle user-avatar-btn"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            @php
                                $authUser = Auth::user();
                                $hasNavFoto = false;
                                $navFotoUrl = '';
                                if (!empty($authUser->foto)) {
                                    if (filter_var($authUser->foto, FILTER_VALIDATE_URL)) {
                                        $hasNavFoto = true;
                                        $navFotoUrl = $authUser->foto;
                                    } elseif (file_exists(public_path('images/avatars/' . $authUser->foto))) {
                                        $hasNavFoto = true;
                                        $navFotoUrl = asset('images/avatars/' . $authUser->foto);
                                    }
                                }
                                $navInitial = strtoupper(substr(trim($authUser->nama ?: 'U'), 0, 1));
                            @endphp

                            @if($hasNavFoto)
                                <img src="{{ $navFotoUrl }}" alt="{{ $authUser->nama }}" class="rounded-circle shadow-sm me-2" style="width:34px;height:34px;object-fit:cover;border:1.5px solid rgba(200,92,46,0.2);" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');">
                                <span class="d-none align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm me-2"
                                    style="width:34px;height:34px;background:linear-gradient(135deg, #C85C2E, #d97706);font-size:14px;">
                                    {{ $navInitial }}
                                </span>
                            @else
                                <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm me-2"
                                    style="width:34px;height:34px;background:linear-gradient(135deg, #C85C2E, #d97706);font-size:14px;">
                                    {{ $navInitial }}
                                </span>
                            @endif

                            <span class="fw-bold d-none d-lg-inline user-avatar-name">
                                {{ Auth::user()->nama }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-2"
                            style="min-width: 260px; box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;">

                            {{-- User Info Header --}}
                            <li class="px-3 py-2.5 rounded-3 mb-2" style="background: #FFFDF9; border: 1px solid #FED7AA;">
                                <div class="d-flex align-items-center gap-2 mb-1.5">
                                    @if($hasNavFoto)
                                        <img src="{{ $navFotoUrl }}" alt="{{ $authUser->nama }}" class="rounded-circle shadow-xs" style="width:36px;height:36px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-xs" style="width:36px;height:36px;background:linear-gradient(135deg, #C85C2E, #d97706);font-size:14px;">
                                            {{ $navInitial }}
                                        </div>
                                    @endif
                                    <div class="text-truncate" style="max-width: 170px;">
                                        <div class="fw-bold text-dark text-truncate" style="font-size:13.5px; letter-spacing: -0.2px;">{{ $authUser->nama }}</div>
                                        <div class="text-muted small text-truncate" style="font-size:11.5px;">{{ $authUser->email }}</div>
                                    </div>
                                </div>
                                <div class="pt-1.5 border-top border-light-subtle d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        @if($authUser->role === 'admin')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                                                <i class="bi bi-shield-check me-1"></i> Administrator
                                            </span>
                                        @elseif($authUser->role === 'pemilik')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                                                <i class="bi bi-shop me-1"></i> Pemilik Warung
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 10px; font-weight: 600;">
                                                <i class="bi bi-person me-1"></i> Pengunjung / Pengguna
                                            </span>
                                        @endif
                                        <span class="text-success small" style="font-size: 10.5px; font-weight: 600;"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                    </div>
                                    @if($authUser->role === 'pemilik' && !empty($authUser->warung?->nama_warung))
                                        <div class="text-dark small fw-semibold text-truncate" style="font-size: 11px;">
                                            <i class="bi bi-geo-alt text-danger me-1"></i>{{ $authUser->warung->nama_warung }}
                                        </div>
                                    @endif
                                </div>
                            </li>

                            {{-- Menu Items --}}
                            @if(Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold" 
                                       href="{{ route('admin.dashboard') }}" style="font-size: 13.5px; transition: all 0.2s;">
                                        <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FEF2F2; color: #DC2626;">
                                            <i class="bi bi-speedometer2"></i>
                                        </span>
                                        <span>Dashboard Admin</span>
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'pemilik')
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold" 
                                       href="{{ route('pemilik.dashboard') }}" style="font-size: 13.5px; transition: all 0.2s;">
                                        <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FEF3C7; color: #D97706;">
                                            <i class="bi bi-speedometer2"></i>
                                        </span>
                                        <span>Dashboard Pemilik</span>
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold" 
                                   href="{{ route('user.dashboard') }}" style="font-size: 13.5px; transition: all 0.2s;">
                                    <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #EFF6FF; color: #2563EB;">
                                        <i class="bi bi-person-gear"></i>
                                    </span>
                                    <span>Kelola Profil & Aktivitas</span>
                                </a>
                            </li>

                            @if(Auth::user()->role === 'pemilik')
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-dark fw-semibold" 
                                       href="{{ route('pemilik.password.edit') }}" style="font-size: 13.5px; transition: all 0.2s;">
                                        <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FFF7ED; color: #C85C2E;">
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                        <span>Keamanan Akun Pemilik</span>
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider my-1.5 opacity-25"></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 py-2 px-2.5 d-flex align-items-center gap-2.5 text-danger fw-bold" style="font-size: 13.5px; transition: all 0.2s;">
                                        <span class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #FFF1F2; color: #E11D48;">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </span>
                                        <span>Keluar / Logout</span>
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </div>
                @endauth

            </div>

        </div>

    </div>
</nav>

<script>
    (function() {
        function initNavbarScroll() {
            const navbar = document.getElementById('mainNavbar');
            if (!navbar) return;

            const heroElement = document.querySelector('.hero, .tentang-hero');

            if (heroElement) {
                document.body.classList.add('has-hero');

                const handleScroll = () => {
                    if (window.scrollY > 40) {
                        if (navbar.classList.contains('navbar-transparent')) {
                            navbar.classList.remove('navbar-transparent');
                            navbar.classList.add('navbar-scrolled');
                        }
                    } else {
                        if (navbar.classList.contains('navbar-scrolled')) {
                            navbar.classList.remove('navbar-scrolled');
                            navbar.classList.add('navbar-transparent');
                        }
                    }
                };

                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll();
            } else {
                document.body.classList.remove('has-hero');
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-scrolled');
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNavbarScroll);
        } else {
            initNavbarScroll();
        }
    })();
</script>