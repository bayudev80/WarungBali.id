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

      <p> <br>
        Setiap warung memiliki cerita dan potensi untuk berkembang. WarungBali.id hadir sebagai jembatan yang menghubungkan masyarakat dengan berbagai warung lokal di Bali, membantu pelaku UMKM menjangkau lebih banyak pelanggan sekaligus memperkenalkan kekayaan usaha lokal kepada lebih banyak orang.
      </p> </br>

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
            <h2>{{ number_format($totalPengunjungBulanIni) }}</h2>
            <p>Pengunjung Bulan Ini</p>
        </div>
</section>
<!-- KATEGORI -->
<section class="py-5 bg-light">

  <div class="container">

    <h2 class="fw-bold text-center mb-5">
        Jelajahi Berdasarkan Kategori
    </h2>

    <div class="kategori-scroll-wrapper">
    <div class="kategori-grid">

    @php
        $icons = [
            'Warung Makan' => 'bi-shop-window',
            'Warung Minuman' => 'bi-cup-straw',
            'Warung Sembako' => 'bi-basket2-fill',
            'Oleh-Oleh Bali' => 'bi-gift-fill',
            'Warung Buah & Sayur' => 'bi-flower1',
            'Warung Herbal' => 'bi-flower2',
            'Warung Pulsa & PPOB' => 'bi-phone-fill',
            'Warung ATK & Fotokopi' => 'bi-printer-fill',
        ];
    @endphp

    <div class="kategori-item">

        <a href="{{ route('home', array_filter(['search' => request('search'), 'urutan' => request('urutan')])) }}" class="text-decoration-none text-dark">

            <div class="card kategori-card border-0 shadow-sm {{ !request('kategori') ? 'border border-warning border-2' : '' }}">

                <div class="kategori-card-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>

                <h6 class="kategori-card-label">
                    Semua
                </h6>

            </div>

        </a>

    </div>

    @foreach($kategori as $item)

        <div class="kategori-item">

            <a href="{{ route('kategori.show', $item->slug) }}" class="text-decoration-none text-dark">

                <div class="card kategori-card border-0 shadow-sm {{ request('kategori') == $item->id_kategori ? 'border border-warning border-2' : '' }}">

                    <div class="kategori-card-icon">
                        <i class="bi {{ $icons[$item->nama_kategori] ?? 'bi-shop' }}"></i>
                    </div>

                    <h6 class="kategori-card-label">
                        {{ $item->nama_kategori }}
                    </h6>

                </div>

            </a>

        </div>

    @endforeach

</div>
    </div>

  </div>

</section>



<!-- WARUNG -->
<section class="py-5" id="warung">

  <div class="container">

    <div id="warung-hasil">
        @include('partials.warung-results', compact(
            'sedangFilter', 'urutan', 'urutanOptions', 'warungPilihan', 'kabupatenAktif', 'icons'
        ))
    </div>

  </div>
  <!-- /container -->

</section>

<!-- CTA DAFTARKAN WARUNG -->
<section class="cta-warung">

    <div class="container">

        <div class="cta-warung-box">

            <h2 class="fw-bold">
                Punya Warung di Bali?
            </h2>

            <p>
                Daftarkan warung Anda secara gratis dan jangkau ribuan
                wisatawan setiap bulan.
            </p>

            @if(!auth()->check() || auth()->user()->role !== 'admin')
                <a href="{{ route('pemilik.warung.create') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-pill">
                    Daftarkan Warung Saya
                </a>
            @endif

        </div>

    </div>

</section>

<style>
    /* ===== Loading state hasil warung (AJAX) ===== */
    #warung-hasil.is-loading {
        opacity: .45;
        pointer-events: none;
        transition: opacity .15s ease;
    }

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

    /* ===== CTA Daftarkan Warung ===== */
    .cta-warung {
        padding: 60px 0;
    }
    .cta-warung-box {
        background: linear-gradient(rgba(30,20,15,.6), rgba(30,20,15,.6)),
                    url('{{ asset('images/hero3.jpeg') }}') center/cover no-repeat;
        border-radius: 24px;
        padding: 60px 50px;
        color: #fff;
        max-width: 100%;
        margin: 0 auto;
        text-align: center;
    }
    .cta-warung-box h2 {
        font-size: 2rem;
        margin-bottom: 12px;
    }
    .cta-warung-box p {
        font-size: 1.05rem;
        margin-bottom: 24px;
        opacity: .9;
    }
    @media (max-width: 768px) {
        .cta-warung-box {
            padding: 40px 25px;
            max-width: 100%;
        }
    }
</style>

<script>
    // ==========================================================
    // PENCARIAN & URUTAN TANPA RELOAD (AJAX)
    // ==========================================================
    // Semua interaksi yang tadinya bikin form/link menavigasi ulang
    // halaman (search box, dropdown urutan, chip hapus filter,
    // pagination) sekarang ditangkap di sini, dikirim ke endpoint
    // /search-ajax, lalu cuma #warung-hasil yang diganti isinya.
    // Event delegation dipakai (listener nempel di document, bukan
    // di tiap link) supaya tetap jalan meski link-nya diganti baru
    // setiap kali #warung-hasil di-render ulang.

    const warungHasilEl = document.getElementById('warung-hasil');
    const searchAjaxPath = new URL('{{ route("search.ajax") }}', window.location.href).pathname;

    function muatHasilWarung(url, { pushState = true } = {}) {
        if (!warungHasilEl) return;

        warungHasilEl.classList.add('is-loading');

        const urlAjax = new URL(url, window.location.href);
        urlAjax.pathname = searchAjaxPath;

        fetch(urlAjax.toString(), { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                warungHasilEl.innerHTML = data.html;
                warungHasilEl.classList.remove('is-loading');

                if (pushState) {
                    window.history.pushState({}, '', url);
                }

                // Sinkronkan kotak pencarian di hero dengan kata kunci yang
                // aktif sekarang (penting saat chip "hapus pencarian" diklik
                // atau tombol back/forward browser dipakai).
                if (heroSearchForm) {
                    const input = heroSearchForm.querySelector('input[name="search"]');
                    if (input) input.value = urlAjax.searchParams.get('search') || '';
                }

                initWarungSliders();

                warungHasilEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(() => {
                // Kalau AJAX gagal (mis. server mati), jangan diam saja —
                // fallback ke navigasi biasa supaya user tetap dapat hasilnya.
                window.location.href = url;
            });
    }

    // Form pencarian hero
    const heroSearchForm = document.querySelector('.hero-search');
    if (heroSearchForm) {
        heroSearchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const input = heroSearchForm.querySelector('input[name="search"]');
            const params = new URLSearchParams();
            if (input && input.value.trim() !== '') {
                params.set('search', input.value.trim());
            }
            const url = '{{ route("home") }}' + (params.toString() ? '?' + params.toString() : '');
            muatHasilWarung(url);
        });
    }

    // Dropdown urutan + chip hapus filter + pagination (link-link di
    // dalam #warung-hasil yang menunjuk balik ke route('home'))
    document.addEventListener('click', function (e) {
        const link = e.target.closest(
            '#warung-hasil .warung-dropdown-item, #warung-hasil .hasil-chip a, #warung-hasil .pagination a'
        );
        if (!link) return;

        e.preventDefault();
        muatHasilWarung(link.getAttribute('href'));
    });

    // Balik/maju browser (tombol back/forward) tetap harus memuat
    // ulang hasil yang sesuai dengan URL saat itu.
    window.addEventListener('popstate', function () {
        muatHasilWarung(window.location.href, { pushState: false });
    });

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
// Dibungkus jadi fungsi (bukan langsung nempel di DOMContentLoaded)
// supaya bisa dipanggil ulang tiap kali #warung-hasil diganti oleh
// AJAX — kalau tidak, slider hasil pencarian baru tidak akan
// auto-geser / tombol prev-next-nya tidak berfungsi.

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

function initWarungSliders() {

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

        updateSliderButtons(track);
        track.addEventListener('scroll', function () { updateSliderButtons(track); });

    });

}

document.addEventListener("DOMContentLoaded", initWarungSliders);

window.addEventListener('resize', function () {
    document.querySelectorAll('.warung-slider-track').forEach(updateSliderButtons);
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