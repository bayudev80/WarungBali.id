<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin WarungBali.id')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                WarungBali.id Admin
            </a>

            <div class="ms-auto d-flex align-items-center">

                <span class="text-white me-3">
                    Halo, {{ Auth::user()->nama }}
                </span>

                <a href="{{ route('home') }}" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-globe"></i> Website
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>

    <!-- Content -->
    <main class="container py-4">

        @yield('content')

    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>