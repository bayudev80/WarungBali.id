<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - WarungBali.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#FFF7EF;">

    <div class="d-flex align-items-center justify-content-center" style="min-height:100vh;">

        <div class="text-center px-4" style="max-width:480px;">

            <div class="fw-bold mb-3" style="font-size:72px; color:#C85C2E;">403</div>

            <h4 class="fw-bold mb-2">Akses Ditolak</h4>

            <p class="text-muted mb-4">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}
                Kemungkinan akun Anda saat ini bukan tipe akun yang sesuai untuk halaman tersebut.
            </p>

            <div class="d-flex gap-2 justify-content-center flex-wrap">

                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Kembali ke Beranda
                </a>

                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn text-white rounded-pill px-4" style="background:#C85C2E;">
                            Logout &amp; Ganti Akun
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn text-white rounded-pill px-4" style="background:#C85C2E;">
                        Login
                    </a>
                @endauth

            </div>

        </div>

    </div>

</body>
</html>
