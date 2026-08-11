<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin WarungBali.id')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <div class="d-flex align-items-center justify-content-center mb-1">
                <img src="{{ asset('images/logo.png') }}" alt="WarungBali Logo" width="30" height="30" class="rounded-3 me-2" style="object-fit:cover; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                <h3 class="mb-0 lh-1">WarungBali<span>.id</span></h3>
            </div>
            <span>Admin Panel</span>
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
                class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.warung.index') }}"
                class="{{ request()->routeIs('admin.warung.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Warung</span>
            </a>

            <a href="{{ route('admin.user.index') }}"
                class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Pengguna</span>
            </a>

            <a href="{{ route('admin.review.index') }}"
                class="{{ request()->routeIs('admin.review.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Review</span>
            </a>

            <a href="{{ route('admin.favorit.index') }}"
                class="{{ request()->routeIs('admin.favorit.*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>Favorit</span>
            </a>

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

            <div class="profile dropdown">

                <div class="d-flex align-items-center gap-3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-md-block">
                        <span class="d-block fw-bold text-dark mb-0 lh-1" style="font-size: 14px;">{{ Auth::user()->nama }}</span>
                        <span class="text-muted small" style="font-size: 12px;">Administrator</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm text-white" style="width: 42px; height: 42px; font-size: 16px; background-color: #C85C2E;">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2" style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-secondary" href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-globe2"></i> Ke Website Utama
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-25"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger fw-medium">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>

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