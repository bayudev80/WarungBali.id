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

                <!-- Dropdown Kategori -->
                <li class="nav-item dropdown mx-1">
                    <a class="nav-link dropdown-toggle px-3 py-2 rounded-pill nav-custom-link {{ request()->routeIs('kategori.show') ? 'active' : '' }}" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        Kategori
                    </a>
                    <ul class="dropdown-menu border-0 shadow mt-2 p-2" style="font-size:13.5px; border-radius: 14px; min-width: 200px;">
                        @if(isset($navbarKategori) && $navbarKategori->count() > 0)
                            @foreach($navbarKategori as $kat)
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 mb-1 kategori-ajax-link {{ request()->is('kategori/'.$kat->slug) ? 'bg-light text-primary fw-medium' : '' }}" 
                                        href="{{ route('kategori.show', array_merge(['slug' => $kat->slug], array_filter(['search' => request('search'), 'kabupaten' => request('kabupaten'), 'urutan' => request('urutan')]))) }}"
                                        data-kategori-id="{{ $kat->id_kategori }}"
                                        data-kategori-slug="{{ $kat->slug }}">
                                        {{ $kat->nama_kategori }}
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <a class="dropdown-item rounded-3 py-2 fw-bold text-center mt-1" 
                                style="color:#C85C2E;"
                                href="{{ route('home') }}#kategori-section">
                                <i class="bi bi-grid-fill me-1"></i> Lihat Semua
                            </a>
                        </li>
                    </ul>
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
                    @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik')
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
                                <img src="{{ $navFotoUrl }}" alt="{{ $authUser->nama }}" class="rounded-circle shadow-sm me-2" style="width:34px;height:34px;object-fit:cover;border:1.5px solid rgba(200,92,46,0.2);" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold shadow-sm me-2"
                                    style="width:34px;height:34px;background:linear-gradient(135deg, #C85C2E, #d97706);font-size:14px;display:none;">
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

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                            style="min-width:230px;border-radius:16px;padding:8px;">

                            <li class="px-3 py-2">
                                <div class="fw-semibold text-truncate" style="font-size:14px;color:#2D201C;">
                                    {{ Auth::user()->nama }}
                                </div>
                                <div class="text-muted small" style="font-size:12px;">
                                    {{ ucfirst(Auth::user()->role) }} • {{ Auth::user()->email }}
                                </div>
                            </li>

                            <li><hr class="dropdown-divider my-1"></li>

                            @if(Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}" style="font-size:13.5px;">
                                        <i class="bi bi-speedometer2 text-danger"></i> Dashboard Admin
                                    </a>
                                </li>
                            @elseif(Auth::user()->role === 'pemilik')
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('pemilik.dashboard') }}" style="font-size:13.5px;">
                                        <i class="bi bi-shop text-warning"></i> Dashboard Pemilik
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('user.dashboard') }}" style="font-size:13.5px;">
                                    <i class="bi bi-person-circle text-primary"></i> Dashboard Pengguna
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('user.dashboard', ['tab' => 'keamanan']) }}" style="font-size:13.5px;">
                                    <i class="bi bi-shield-lock text-success"></i> Keamanan & Password
                                </a>
                            </li>

                            <li><hr class="dropdown-divider my-1"></li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item rounded-3 py-2 text-danger d-flex align-items-center gap-2"
                                        style="font-size:13.5px;">
                                        <i class="bi bi-box-arrow-right"></i> Logout
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