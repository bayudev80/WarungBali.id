<nav class="navbar navbar-expand-lg sticky-top py-2" style="background:#FFF7EF;">
    <div class="container">

       <!-- Logo -->
<a class="navbar-brand d-flex align-items-center fw-bold gap-2" href="/">
    <img src="{{ asset('images/logo.png') }}"
        alt="WarungBali"
        width="42"
        height="42"
        class="me-1"
        style="object-fit:contain;">

    <span style="
        font-family:'Playfair Display',serif;
        font-size:22px;
        color:#2D201C;
        letter-spacing: -0.5px;">
        Warungbali<span style="color:#C85C2E;">.id</span>
    </span>
</a>


        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <!-- Menu Tengah -->
            <ul class="navbar-nav mx-auto">

                <li class="nav-item mx-1">

                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}"
                        style="font-size:13px;{{ request()->routeIs('home') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Beranda

                    </a>

                </li>

                <!-- Dropdown Kategori -->
                <li class="nav-item dropdown mx-1">
                    <a class="nav-link dropdown-toggle px-3 py-2 rounded-pill {{ request()->routeIs('kategori.show') ? 'active' : '' }}" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="font-size:13px;{{ request()->routeIs('kategori.show') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">
                        Kategori
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm mt-2 p-2" style="font-size:13.5px; border-radius: 14px; min-width: 200px;">
                        @if(isset($navbarKategori) && $navbarKategori->count() > 0)
                            @foreach($navbarKategori as $kat)
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 mb-1 kategori-ajax-link {{ request()->is('kategori/'.$kat->slug) ? 'bg-light text-primary fw-medium' : '' }}" 
                                        href="{{ route('kategori.show', $kat->slug) }}">
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

                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->routeIs('favorit.index') ? 'active' : '' }}"
                        href="{{ route('favorit.index') }}"
                        style="font-size:13px;{{ request()->routeIs('favorit.index') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Favorit Saya

                    </a>

                </li>

                <li class="nav-item mx-1">

                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->routeIs('tentang') ? 'active' : '' }}"
                        href="{{ route('tentang') }}"
                        style="font-size:13px;{{ request()->routeIs('tentang') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Tentang Kami

                    </a>

                </li>

            </ul>

          <!-- Tombol -->
<div class="d-flex align-items-center gap-3">

    @guest
        <!-- Group Masuk/Daftar -->
        <div class="d-flex align-items-center gap-3 border-end pe-3" style="border-color: rgba(45, 32, 28, 0.15) !important;">
            <a href="{{ route('login') }}" 
                class="text-decoration-none fw-bold auth-link" 
                style="font-size:14px; color:#2D201C; transition: 0.2s;">
                Masuk
            </a>
            
            <a href="{{ route('register') }}" 
                class="btn btn-sm rounded-pill px-3 py-2 fw-semibold outline-auth-btn" 
                style="font-size:13.5px; color:#C85C2E; border: 1.5px solid #C85C2E; background: transparent; transition: 0.3s;">
                Daftar
            </a>
        </div>

        <!-- Tombol Utama Daftarkan Warung -->
        <a href="{{ route('pemilik.warung.create') }}"
            class="btn btn-sm rounded-pill text-white px-3 py-2 d-flex align-items-center gap-2 shadow-sm cta-warung-btn"
            style="background: linear-gradient(135deg, #C85C2E, #d97706); font-size:13.5px; font-weight:600; border:none; transition: 0.3s;">
            <i class="bi bi-shop"></i> Daftarkan Warung
        </a>
    @endguest

    @auth

        @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik')
            <a href="{{ route('pemilik.warung.create') }}"
                class="btn btn-sm rounded-pill text-white px-3 py-2 me-2 d-flex align-items-center gap-2 shadow-sm cta-warung-btn"
                style="background: linear-gradient(135deg, #C85C2E, #d97706); font-size:13.5px; font-weight:600; border:none; transition: 0.3s;">
                <i class="bi bi-shop"></i> Daftarkan Warung
            </a>
        @endif

        <!-- Dropdown Avatar -->
        <div class="dropdown">

            <a href="#"
                class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="gap:8px;background:#F3ECE4;border:1px solid #E3D5C4;border-radius:999px;padding:4px 12px 4px 4px;">

                <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                    style="width:32px;height:32px;background:#C85C2E;font-size:14px;">

                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}

                </span>

                <span class="fw-bold d-none d-lg-inline" style="font-size:14px;color:#2D201C;">

                    {{ Auth::user()->nama }}

                </span>

            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2"
                style="min-width:220px;border-radius:14px;padding:8px;">

                <li class="px-3 py-2">

                    <div class="fw-semibold" style="font-size:14px;color:#2D201C;">

                        {{ Auth::user()->nama }}

                    </div>

                    <div class="text-muted" style="font-size:12px;">

                        {{ ucfirst(Auth::user()->role) }}

                    </div>

                </li>

                <li><hr class="dropdown-divider my-1"></li>

                @if(Auth::user()->role === 'admin')

                    <li>

                        <a class="dropdown-item rounded-3 py-2" href="{{ route('admin.dashboard') }}" style="font-size:14px;">

                            Dashboard Admin

                        </a>

                    </li>

                @elseif(Auth::user()->role === 'pemilik')

                    <li>

                        <a class="dropdown-item rounded-3 py-2" href="{{ route('pemilik.dashboard') }}" style="font-size:14px;">

                            Dashboard Pemilik

                        </a>

                    </li>

                @endif

                <li><hr class="dropdown-divider my-1"></li>

                <li>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">

                        @csrf

                        <button type="submit"
                            class="dropdown-item rounded-3 py-2 text-danger"
                            style="font-size:14px;">

                            Logout

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

<style>
    .auth-link:hover {
        color: #C85C2E !important;
        transform: translateY(-1px);
    }
    .outline-auth-btn:hover {
        background: #C85C2E !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(200, 92, 46, 0.2);
    }
    .cta-warung-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(200, 92, 46, 0.3) !important;
    }
</style>