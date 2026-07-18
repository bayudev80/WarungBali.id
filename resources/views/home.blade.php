@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="hero">

    <div class="hero-slider">

        <div class="hero-slide active"
            style="background-image:url('{{ asset('images/hero1.jpeg') }}')">
        </div>

        <div class="hero-slide"
            style="background-image:url('{{ asset('images/hero2.jpeg') }}')">
        </div>

        <div class="hero-slide"
            style="background-image:url('{{ asset('images/hero3.jpeg') }}')">
        </div>

        <div class="hero-slide"
            style="background-image:url('{{ asset('images/hero4.jpeg') }}')">
        </div>

        <div class="hero-slide"
            style="background-image:url('{{ asset('images/hero.jpeg') }}')">
        </div>

    </div>

    <div class="hero-overlay"></div>

    <div class="container position-relative">

    <div class="hero-content text-center">

      <span class="hero-badge">
        🌴 Temukan Berbagai Jenis Warung di Bali |
          Warung Makan • Minuman • Sembako • Oleh-Oleh • Herbal • ATK • Pulsa
      </span>

      <h1>
        Jelajahi Warung Lokal
      </h1>

      <h2>
        Pulau Dewata
      </h2>

      <p>
        Temukan cita rasa autentik Bali melalui warung-warung lokal terbaik.
        Jelajahi kuliner khas, lokasi favorit, dan pengalaman makan yang
        menghadirkan kehangatan budaya Pulau Dewata.
      </p>

      <form class="hero-search" method="GET" action="{{ route('home') }}">

        <input type="text" name="search" value="{{ request('search') }}"
          placeholder="Cari warung, kategori, atau lokasi...">

        <button>
          Cari
        </button>

      </form>


    </div>

  </div>

</section>

 <section class="statistik">
    <div class="container">
        <div class="statistik-wrapper">

        <div class="stat-item">
            <h2>{{ $totalWarung }}+</h2>
            <p>Warung Terdaftar</p>
        </div>

        <div class="stat-item">
            <h2>{{ $totalUlasan }}+</h2>
            <p>Ulasan Pengguna</p>
        </div>

        <div class="stat-item">
            <h2>{{ $totalKabupaten }}</h2>
            <p>Kabupaten/Kota</p>
        </div>

        <div class="stat-item">
            <h2>32.000+</h2>
            <p>Pengunjung/Bulan</p>
        </div>
</section>
<!-- KATEGORI -->
<section class="py-5 bg-light">

  <div class="container">

    <h2 class="fw-bold text-center mb-5">
      Kategori Populer
    </h2>

    <div class="row g-4 text-center">

    @php
        $icons = [
            'Warung Makan' => '🍛',
            'Warung Minuman' => '🥤',
            'Warung Sembako' => '🛒',
            'Oleh-Oleh Bali' => '🎁',
            'Warung Buah & Sayur' => '🥬',
            'Warung Herbal' => '🌿',
            'Warung Pulsa & PPOB' => '📱',
            'Warung ATK & Fotokopi' => '📚',
        ];
    @endphp

    <div class="col-lg-3 col-md-4 col-6">

        <a href="{{ route('home', array_filter(['search' => request('search'), 'urutan' => request('urutan')])) }}" class="text-decoration-none text-dark">

            <div class="card kategori-card border-0 shadow-sm p-3 h-100 {{ !request('kategori') ? 'border border-warning border-2' : '' }}">

                <div style="font-size:40px;">
                    🏪
                </div>

                <h6 class="mt-2">
                    Semua
                </h6>

            </div>

        </a>

    </div>

    @foreach($kategori as $item)

        <div class="col-lg-3 col-md-4 col-6">

            <a href="{{ route('home', array_filter(['search' => request('search'), 'urutan' => request('urutan'), 'kategori' => $item->id_kategori])) }}" class="text-decoration-none text-dark">

                <div class="card kategori-card border-0 shadow-sm p-3 h-100 {{ request('kategori') == $item->id_kategori ? 'border border-warning border-2' : '' }}">

                    <div style="font-size:40px;">
                        {{ $icons[$item->nama_kategori] ?? '🏪' }}
                    </div>

                    <h6 class="mt-2">
                        {{ $item->nama_kategori }}
                    </h6>

                </div>

            </a>

        </div>

    @endforeach

</div>

  </div>

</section>



<!-- WARUNG -->
<section class="py-5" id="warung">

  <div class="container">

    <h2 class="fw-bold mb-5">

      @if(request('search'))
      Hasil Pencarian
      @else
      Warung Populer
      @endif
    </h2>

    @php
        $urutanOptions = [
            'populer'  => '🔥 Terpopuler',
            'disukai'  => '❤️ Banyak Disukai',
            'rating'   => '⭐ Rating Tertinggi',
            'terbaru'  => '🆕 Terbaru',
            'termurah' => '💸 Harga Termurah',
            'termahal' => '💰 Harga Termahal',
        ];
    @endphp

    <div class="mb-4">

        <div class="warung-dropdown">

            <button type="button" class="btn btn-outline-secondary px-3 py-2 rounded-pill"
                onclick="toggleDropdown('urutanDropdownMenu')">
                 {{ $urutanOptions[$urutan] ?? $urutanOptions['populer'] }} ▾
            </button>

            <ul id="urutanDropdownMenu" class="warung-dropdown-menu shadow border-0">

                @foreach($urutanOptions as $key => $label)

                    <li>
                        <a class="warung-dropdown-item {{ $urutan === $key ? 'active' : '' }}"
                           href="{{ route('home', array_filter([
                                'search'   => request('search'),
                                'kategori' => request('kategori'),
                                'urutan'   => $key,
                           ])) }}">
                            {{ $label }}
                        </a>
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

    @if(request('search'))

    </div>
</section>

    <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">

      <h5 class="mb-1">
        🔍 Hasil pencarian untuk:
        <strong>"{{ request('search') }}"</strong>

      </h5>

      <small class="text-secondary">
        Ditemukan <strong>{{ $warungPilihan->count() }}</strong> warung
      </small>

    </div>

    @endif
    @php
        $tampilkanPerKategori = !request('kategori');

        $groupedWarung = $tampilkanPerKategori
            ? $warungPilihan->groupBy(fn($w) => $w->kategori->nama_kategori ?? 'Lainnya')
            : collect(['__single__' => $warungPilihan]);
    @endphp

    @forelse($groupedWarung as $namaGrup => $items)

        @if($tampilkanPerKategori)

            <h3 class="fw-bold mt-5 mb-4">
                {{ $icons[$namaGrup] ?? '🏪' }} {{ $namaGrup }}
            </h3>

        @endif

        @php $sliderId = 'slider-'.$loop->index; @endphp

        <div class="warung-slider-wrapper position-relative mb-3">

            <button type="button" class="warung-slider-btn warung-slider-prev"
                onclick="geserWarung('{{ $sliderId }}', -1)" aria-label="Sebelumnya">
                &#8249;
            </button>

            <div id="{{ $sliderId }}" class="warung-slider-track">

                @foreach($items as $item)

                    @include('partials.warung-card', ['item' => $item])

                @endforeach

            </div>

            <button type="button" class="warung-slider-btn warung-slider-next"
                onclick="geserWarung('{{ $sliderId }}', 1)" aria-label="Berikutnya">
                &#8250;
            </button>

        </div>

    @empty

        <div class="alert alert-warning text-center rounded-4">
            Belum ada warung untuk ditampilkan.
        </div>

    @endforelse

  </div>
  <!-- /container -->

</section>

<style>
    /* ===== Slider warung per kategori ===== */
    .warung-slider-wrapper {
        position: relative;
        overflow: visible;
        width: fit-content;
        max-width: 100%;
    }
    .warung-slider-track {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 1rem;
        max-width: 100%;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 4px 46px .5rem;
        scrollbar-width: none;
    }
    .warung-slider-track::-webkit-scrollbar {
        display: none;
    }
    /* PENTING: hanya target kartu warung (.warung-card-item), JANGAN pakai
       selector "> *" karena modal detail warung ikut jadi sibling di sini
       dan bakal ke-maksa lebar 300px kalau kena selector universal. */
    .warung-slider-track > .warung-card-item {
        flex: 0 0 auto;
        width: 300px;
        scroll-snap-align: start;
    }
    .warung-slider-btn {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        z-index: 50;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
        font-size: 22px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        pointer-events: auto;
    }
    .warung-slider-prev { left: 0; }
    .warung-slider-next { right: 0; }
    .warung-slider-btn:hover { background: #ffc107; color: #fff; }
    .warung-slider-btn.is-hidden { display: none; }

    @media (max-width: 768px) {
        .warung-slider-track { padding-left: 40px; padding-right: 40px; }
        .warung-slider-track > .warung-card-item { width: 240px; }
    }

    /* ===== Dropdown Urutkan (mandiri, tidak bergantung Bootstrap JS) ===== */
    .warung-dropdown {
        position: relative;
        display: inline-block;
    }
    .warung-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 6px;
        min-width: 220px;
        background: #fff;
        border-radius: .5rem;
        padding: 6px;
        list-style: none;
        z-index: 100;
    }
    .warung-dropdown-menu.is-open {
        display: block;
    }
    .warung-dropdown-item {
        display: block;
        padding: 8px 12px;
        border-radius: .375rem;
        color: #212529;
        text-decoration: none;
        font-size: .9rem;
    }
    .warung-dropdown-item:hover {
        background: #fff8ec;
    }
    .warung-dropdown-item.active {
        background: #ffc107;
        color: #fff;
        font-weight: 600;
    }
</style>

<script>
    // --- Slider warung ---
    function geserWarung(id, arah) {
        const track = document.getElementById(id);
        if (!track) return;
        const jarak = track.querySelector(':scope > .warung-card-item')?.offsetWidth || 300;
        track.scrollBy({ left: arah * (jarak + 16), behavior: 'smooth' });
    }

  // ==========================
// AUTO SLIDER WARUNG
// ==========================

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".warung-slider-track").forEach(track => {

        const wrapper = track.closest(".warung-slider-wrapper");

        let timer;

        function autoSlide() {

            const card = track.querySelector(".warung-card");

            if (!card) return;

            const gap = 24;

            const cardWidth = card.offsetWidth + gap;

            // jika sudah mentok kanan
            if (track.scrollLeft >= track.scrollWidth - track.clientWidth - 10) {

                track.scrollTo({
                    left: 0,
                    behavior: "smooth"
                });

            } else {

                track.scrollBy({
                    left: cardWidth,
                    behavior: "smooth"
                });

            }

        }

        timer = setInterval(autoSlide, 4000);

        wrapper.addEventListener("mouseenter", () => {

            clearInterval(timer);

        });

        wrapper.addEventListener("mouseleave", () => {

            timer = setInterval(autoSlide, 4000);

        });

    });

});

    function updateSliderButtons(track) {
        const wrapper = track.closest('.warung-slider-wrapper');
        if (!wrapper) return;
        const prevBtn = wrapper.querySelector('.warung-slider-prev');
        const nextBtn = wrapper.querySelector('.warung-slider-next');
        const bisaScroll = track.scrollWidth > track.clientWidth + 5;

        if (!bisaScroll) {
            prevBtn?.classList.add('is-hidden');
            nextBtn?.classList.add('is-hidden');
            return;
        }
        prevBtn?.classList.toggle('is-hidden', track.scrollLeft <= 5);
        nextBtn?.classList.toggle('is-hidden', track.scrollLeft + track.clientWidth >= track.scrollWidth - 5);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.warung-slider-track').forEach(function (track) {
            updateSliderButtons(track);
            track.addEventListener('scroll', function () { updateSliderButtons(track); });
        });
        window.addEventListener('resize', function () {
            document.querySelectorAll('.warung-slider-track').forEach(updateSliderButtons);
        });
    });

    // --- Dropdown Urutkan (mandiri) ---
    function toggleDropdown(menuId) {
        const menu = document.getElementById(menuId);
        if (!menu) return;
        const sedangTerbuka = menu.classList.contains('is-open');
        document.querySelectorAll('.warung-dropdown-menu.is-open').forEach(m => m.classList.remove('is-open'));
        if (!sedangTerbuka) menu.classList.add('is-open');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.warung-dropdown')) {
            document.querySelectorAll('.warung-dropdown-menu.is-open').forEach(m => m.classList.remove('is-open'));
        }
    });
    // ===== Hero Slider =====
document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll(".hero-slide");

    if (!slides.length) return;

    let index = 0;

    setInterval(() => {

        slides[index].classList.remove("active");

        index = (index + 1) % slides.length;

        slides[index].classList.add("active");

    }, 5000);

});
</script>

@endsection