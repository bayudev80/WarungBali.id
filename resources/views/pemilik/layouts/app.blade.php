<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pemilik Warung - WarungBali.id')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Pemilik Dashboard CSS (load last to override Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}?v={{ file_exists(public_path('css/pemilik.css')) ? filemtime(public_path('css/pemilik.css')) : time() }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <div class="d-flex align-items-center justify-content-center mb-1">
                <img src="{{ asset('images/logo.png') }}" alt="WarungBali Logo" width="32" height="32" class="rounded-2 me-2" style="object-fit:cover;">
                <h3 class="mb-0 lh-1">WarungBali<span>.id</span></h3>
            </div>
            <span>Pemilik Warung</span>
        </div>

        <nav class="sidebar-menu">

            @if(auth()->user()->warung)

                <a href="{{ route('pemilik.dashboard') }}"
                    class="{{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pemilik.warung.edit') }}"
                    class="{{ request()->routeIs('pemilik.warung.*') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Warung Saya</span>
                </a>

                <a href="{{ route('pemilik.menu.index') }}"
                    class="{{ request()->routeIs('pemilik.menu.*') ? 'active' : '' }}">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Menu Saya</span>
                </a>

            @else

                <a href="{{ route('pemilik.warung.create') }}"
                    class="{{ request()->routeIs('pemilik.warung.create') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i>
                    <span>Daftarkan Warung</span>
                </a>

            @endif

        </nav>

    </aside>


    <!-- Content -->
    <div class="content">

        <!-- Topbar -->
        <header class="topbar">

            <div>
                <h4 class="fw-bold mb-0">
                    @yield('title')
                </h4>
            </div>

            <div class="profile">

                <span>
                    Halo,
                    <strong>{{ Auth::user()->nama }}</strong>
                </span>

                <a href="{{ route('home') }}" class="btn-website">
                    <i class="fa-solid fa-globe"></i>
                    Website
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>

            </div>

        </header>

        <!-- Main Content -->
        <main class="main-content">

            @yield('content')

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>