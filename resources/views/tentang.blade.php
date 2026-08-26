@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="tentang-hero">

    <div class="tentang-hero-overlay"></div>

    <div class="container text-center">

        <h1>Tentang Warungbali.id</h1>

        <p>Memberdayakan UMKM dan warisan budaya Bali melalui teknologi</p>

    </div>

</section>

<div class="container py-5">

    <!-- MISI KAMI -->
    <div class="row align-items-center g-4 mb-5">

        <div class="col-lg-7 misi-text">

            <h2 class="tentang-section-title">Misi Kami</h2>

            <p>
                Warungbali.id lahir dari kecintaan terhadap warung lokal Bali.
                Kami percaya setiap warung menyimpan cerita dan warisan
                keluarga, baik itu kuliner, sembako, oleh-oleh, maupun
                kebutuhan sehari-hari lainnya.
            </p>

            <p>
                Platform kami menghubungkan wisatawan dan masyarakat lokal dengan
                warung-warung autentik di seluruh Pulau Dewata, sekaligus membantu
                pemilik warung menjangkau lebih banyak pelanggan secara digital.
            </p>

        </div>

        <div class="col-lg-5">

            <div class="misi-image">
                <img src="{{ asset('images/filosofilogowb-id.jpeg') }}" alt="Logo Warungbali.id">
            </div>

        </div>

    </div>

    <!-- CARA KERJA -->
    <div class="mb-5">

        <h2 class="tentang-section-title">Cara Kerja</h2>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="cara-kerja-card">

                    <div class="cara-kerja-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h5>Untuk Pengunjung</h5>

                    <ul>
                        <li>Cari warung berdasarkan lokasi atau kategori</li>
                        <li>Lihat menu lengkap beserta harga</li>
                        <li>Baca ulasan dari pengguna lain</li>
                        <li>Berikan rating dan ulasan setelah kunjungan</li>
                    </ul>

                </div>

            </div>

            <div class="col-md-6">

                <div class="cara-kerja-card">

                    <div class="cara-kerja-icon">
                        <i class="bi bi-shop"></i>
                    </div>

                    <h5>Untuk Pemilik Warung</h5>

                    <ul>
                        <li>Daftarkan warung secara gratis</li>
                        <li>Kelola informasi, foto, dan menu</li>
                        <li>Pantau ulasan dari pelanggan</li>
                        <li>Jangkau wisatawan lokal &amp; mancanegara</li>
                    </ul>

                </div>

            </div>

        </div>

    </div>

    <!-- CTA -->
    <div class="tentang-cta" style="background: linear-gradient(rgba(30,20,15,.65), rgba(30,20,15,.65)), url('{{ asset('images/hero10.png') }}') center/cover no-repeat; border-radius: 24px; padding: 60px 40px; text-align: center; color: #fff;">

        <h4 style="color: #fff; font-weight: 800; font-size: 2rem; margin-bottom: 12px;">Siap Mendaftarkan Warung Anda?</h4>

        <p style="color: rgba(255,255,255,0.9); font-size: 1.05rem; margin-bottom: 24px;">Gratis selamanya untuk warung lokal Bali. Bergabunglah sekarang!</p>

        @guest
            <a href="{{ route('register', ['role' => 'pemilik']) }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow">
                <i class="bi bi-plus-circle me-1"></i> Daftarkan Warung Saya
            </a>
        @endguest

        @auth
            @if (Auth::user()->role === 'pemilik')
                <a href="{{ route('pemilik.dashboard') }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow">
                    <i class="bi bi-shop me-1"></i> Kelola Warung Saya
                </a>
            @else
                <a href="{{ route('pemilik.warung.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 fw-bold shadow">
                    <i class="bi bi-plus-circle me-1"></i> Daftarkan Warung Saya
                </a>
            @endif
        @endauth

    </div>

</div>

@endsection