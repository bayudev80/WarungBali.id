<nav class="navbar navbar-expand-lg sticky-top py-2" style="background:#FFF7EF;">
    <div class="container">

       <!-- Logo -->
<a class="navbar-brand d-flex align-items-center fw-bold" href="/">
    <img src="{{ asset('images/logo.png') }}"
        alt="WarungBali"
        width="34"
        height="34"
        class="rounded-3 me-2"
        style="object-fit:cover;">

    <span style="
        font-family:'Playfair Display',serif;
        font-size:18px;
        color:#2D201C;">

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
                        style="font-size:15px;{{ request()->routeIs('home') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Beranda

                    </a>

                </li>

                <li class="nav-item mx-1">

                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->routeIs('favorit.index') ? 'active' : '' }}"
                        href="{{ route('favorit.index') }}"
                        style="font-size:15px;{{ request()->routeIs('favorit.index') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Favorit Saya

                    </a>

                </li>

                <li class="nav-item mx-1">

                    <a class="nav-link px-3 py-2 rounded-pill {{ request()->routeIs('tentang') ? 'active' : '' }}"
                        href="{{ route('tentang') }}"
                        style="font-size:15px;{{ request()->routeIs('tentang') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Tentang Kami

                    </a>

                </li>

            </ul>

          <!-- Tombol -->
<div class="d-flex align-items-center">

    @guest

        <a href="{{ route('login') }}"
            class="btn btn-sm btn-light border rounded-4 px-3 py-2 me-2"
            style="font-size:14px;">

            Masuk

        </a>

        <a href="{{ route('register') }}"
            class="btn btn-sm rounded-4 text-white px-3 py-2 me-2"
            style="background:#C85C2E;font-size:14px;">

            Daftar

        </a>

        <a href="{{ route('pemilik.warung.create') }}"
            class="btn btn-sm rounded-pill text-white px-3 py-2"
            style="background:#C85C2E;font-size:14px;">

             Daftarkan Warung

        </a>

    @endguest

    @auth

        @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik')

            <a href="{{ route('pemilik.warung.create') }}"
                class="btn btn-sm rounded-pill text-white px-3 py-2 me-3"
                style="background:#C85C2E;font-size:14px;">

                 Daftarkan Warung

            </a>

        @endif

        <!-- Dropdown Avatar -->
        <div class="dropdown">

            <a href="#"
                class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                style="gap:8px;">

                <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                    style="width:34px;height:34px;background:#C85C2E;font-size:15px;">

                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}

                </span>

                <span class="fw-semibold d-none d-lg-inline" style="font-size:14px;color:#2D201C;">

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