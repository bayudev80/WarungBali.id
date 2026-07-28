<nav class="navbar navbar-expand-lg fixed-top py-3" style="background:#FFF7EF;">
    <div class="container">

       <!-- Logo -->
<a class="navbar-brand d-flex align-items-center fw-bold" href="/">
    <img src="{{ asset('images/logo.png') }}"
        alt="WarungBali"
        width="48"
        height="48"
        class="rounded-3 me-3"
        style="object-fit:cover;">

    <span style="
        font-family:'Playfair Display',serif;
        font-size:22px;
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

                <li class="nav-item mx-2">

                    <a class="nav-link px-4 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}"
                        style="{{ request()->routeIs('home') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Beranda

                    </a>

                </li>

                <li class="nav-item mx-2">

                    <a class="nav-link"
                        href="{{ route('home') }}#warung">

                        Jelajahi

                    </a>

                </li>

                <li class="nav-item mx-2">

                    <a class="nav-link px-4 py-2 rounded-pill {{ request()->routeIs('tentang') ? 'active' : '' }}"
                        href="{{ route('tentang') }}"
                        style="{{ request()->routeIs('tentang') ? 'background:#FCE8CC;color:#C85C2E;font-weight:600;' : '' }}">

                        Tentang Kami

                    </a>

                </li>

            </ul>

          <!-- Tombol -->
<div class="d-flex align-items-center">

    @guest

        <a href="{{ route('login') }}"
            class="btn btn-light border rounded-4 px-4 me-3">

            Masuk

        </a>

        <a href="{{ route('register') }}"
            class="btn rounded-4 text-white px-4"
            style="background:#C85C2E;">

            Daftar

        </a>

    @endguest

    @auth

        @if(Auth::user()->role === 'admin')

            <a href="{{ route('admin.dashboard') }}"
                class="btn btn-light border rounded-4 px-4 me-3">

                Dashboard Admin

            </a>

        @elseif(Auth::user()->role === 'pemilik')

            <a href="{{ route('pemilik.dashboard') }}"
                class="btn btn-light border rounded-4 px-4 me-3">

                Dashboard Pemilik

            </a>

        @endif

        <span class="me-3 fw-semibold">

            👋 {{ Auth::user()->nama }}

        </span>

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit"
                class="btn btn-danger rounded-4">

                Logout

            </button>

        </form>

    @endauth

</div>

        </div>

    </div>
</nav>

<div style="height:90px;"></div>