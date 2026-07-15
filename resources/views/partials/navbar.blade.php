<nav class="navbar navbar-expand-lg fixed-top py-3" style="background:#FFF7EF;">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
            <div class="rounded-3 d-flex justify-content-center align-items-center me-3"
                style="width:48px;height:48px;background:#C85C2E;">

                <span class="text-white fw-bold fs-3">
                    W
                </span>

            </div>

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

                    <a class="nav-link active px-4 py-2 rounded-pill"
                        href="/"
                        style="background:#FCE8CC;color:#C85C2E;font-weight:600;">

                        Beranda

                    </a>

                </li>

                <li class="nav-item mx-2">

                    <a class="nav-link"
                        href="#warung">

                        Jelajahi

                    </a>

                </li>

                <li class="nav-item mx-2">

                    <a class="nav-link"
                        href="#footer">

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