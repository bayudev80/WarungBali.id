@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="hero">

    <div class="hero-slider">

        <div class="hero-slide active">
            <img src="{{ asset('images/hero1.webp') }}" alt="Warung Bali 1" class="hero-slide-img" loading="eager" onerror="this.src='{{ asset('images/hero1.png') }}'">
        </div>

        <div class="hero-slide">
            <img src="{{ asset('images/hero2.webp') }}" alt="Warung Bali 2" class="hero-slide-img" loading="lazy" onerror="this.src='{{ asset('images/hero2.png') }}'">
        </div>

        <div class="hero-slide">
            <img src="{{ asset('images/hero3.webp') }}" alt="Warung Bali 3" class="hero-slide-img" loading="lazy" onerror="this.src='{{ asset('images/hero3.png') }}'">
        </div>

        <div class="hero-slide">
            <img src="{{ asset('images/hero4.webp') }}" alt="Warung Bali 4" class="hero-slide-img" loading="lazy" onerror="this.src='{{ asset('images/hero4.png') }}'">
        </div>

        <div class="hero-slide">
            <img src="{{ asset('images/hero5.webp') }}" alt="Warung Bali 5" class="hero-slide-img" loading="lazy" onerror="this.src='{{ asset('images/hero5.png') }}'">
        </div>

    </div>

    <div class="hero-overlay"></div>

    <!-- Hero Slider Dots Indicators -->
    <div class="hero-slider-dots">
        <button type="button" class="hero-dot active" aria-label="Slide 1" data-index="0"></button>
        <button type="button" class="hero-dot" aria-label="Slide 2" data-index="1"></button>
        <button type="button" class="hero-dot" aria-label="Slide 3" data-index="2"></button>
        <button type="button" class="hero-dot" aria-label="Slide 4" data-index="3"></button>
        <button type="button" class="hero-dot" aria-label="Slide 5" data-index="4"></button>
    </div>

    <div class="container position-relative">

    <div class="hero-content text-center">

      <h1>
        Jelajahi Warung Lokal
      </h1>

      <h2>
        Pulau Dewata
      </h2>

      <p>
        Setiap warung memiliki cerita dan potensi untuk berkembang. WarungBali.id hadir sebagai jembatan yang menghubungkan masyarakat dengan berbagai warung lokal di Bali, membantu pelaku UMKM menjangkau lebih banyak pelanggan sekaligus memperkenalkan kekayaan usaha lokal kepada lebih banyak orang.
      </p>


      <form class="hero-search" method="GET" action="{{ route('home') }}">
        <div class="hero-search-wrapper">
            <div class="hero-search-input-group">
                <i class="bi bi-search hero-search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                  placeholder="Cari warung, kuliner, menu..."
                  autocomplete="off">
            </div>

            <div class="hero-search-divider"></div>

            <div class="hero-search-select-group">
                <i class="bi bi-geo-alt-fill hero-search-geo-icon"></i>
                <select name="kabupaten" class="hero-search-select" aria-label="Pilih Kabupaten atau Kota">
                    <option value="">Semua Wilayah</option>
                    @if(isset($kabupatenList))
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab->id_kabupaten }}" {{ (string)request('kabupaten') === (string)$kab->id_kabupaten ? 'selected' : '' }}>
                                {{ $kab->nama_kabupaten }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <button type="submit" class="hero-search-btn">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </div>
      </form>


    </div>

  </div>

</section>

@if(!$sedangFilter)
    @php
        $hasStatsTable = class_exists(\App\Models\SiteStatistic::class) && \Illuminate\Support\Facades\Schema::hasTable('site_statistics');
        $statsToDisplay = $hasStatsTable
            ? ($publicStatistics ?? collect())
            : collect([
                (object)['formatted' => $totalWarung . '+', 'label' => 'Warung Terdaftar'],
                (object)['formatted' => $totalUlasan . '+', 'label' => 'Ulasan Pengguna'],
                (object)['formatted' => (string)$totalKabupaten, 'label' => 'Kabupaten/Kota'],
                (object)['formatted' => number_format($totalPengunjungBulanIni), 'label' => 'Pengunjung Bulan Ini'],
            ]);
    @endphp

    @if($statsToDisplay->isNotEmpty())
        <section class="statistik">
            <div class="container">
                <div class="statistik-wrapper">
                    @foreach($statsToDisplay as $stat)
                        <div class="stat-item">
                            <h2>{{ method_exists($stat, 'getFormattedValue') ? $stat->getFormattedValue() : $stat->formatted }}</h2>
                            <p>{{ $stat->label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

<!-- KATEGORI -->
<section class="py-5 bg-light" id="kategori-section">

  <div class="container">

    <h2 class="fw-bold text-center mb-5">
        Jelajahi Berdasarkan Kategori
    </h2>

    <div class="kategori-scroll-wrapper">
    <div class="kategori-grid" id="kategori-grid">

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
        <a href="{{ route('home', array_filter(['search' => request('search'), 'kabupaten' => request('kabupaten'), 'urutan' => request('urutan')])) }}" class="text-decoration-none text-dark kategori-ajax-link" data-kategori-id="">
            <div class="card kategori-card border-0 shadow-sm {{ !request('kategori') ? 'border border-warning border-2' : '' }}">
                <div class="kategori-card-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <h6 class="kategori-card-label">Semua</h6>
            </div>
        </a>
    </div>

    @foreach($kategori->take(6) as $item)
        <div class="kategori-item">
            <a href="{{ route('kategori.show', array_merge(['slug' => $item->slug], array_filter(['search' => request('search'), 'kabupaten' => request('kabupaten'), 'urutan' => request('urutan')]))) }}" class="text-decoration-none text-dark kategori-ajax-link" data-kategori-id="{{ $item->id_kategori }}" data-kategori-slug="{{ $item->slug }}">
                <div class="card kategori-card border-0 shadow-sm {{ (string)request('kategori') === (string)$item->id_kategori ? 'border border-warning border-2' : '' }}">
                    <div class="kategori-card-icon">
                        <i class="bi {{ $icons[$item->nama_kategori] ?? 'bi-shop' }}"></i>
                    </div>
                    <h6 class="kategori-card-label">{{ $item->nama_kategori }}</h6>
                </div>
            </a>
        </div>
    @endforeach

    @if(count($kategori) > 6)
        <div class="kategori-item">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#kategoriModal" class="text-decoration-none text-dark">
                <div class="card kategori-card border-0 shadow-sm" style="background-color: #f8f9fa;">
                    <div class="kategori-card-icon">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <h6 class="kategori-card-label">Lainnya</h6>
                </div>
            </a>
        </div>
    @endif

    </div>
    </div>

  </div>

</section>
@endif

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

<!-- MODAL SEMUA KATEGORI -->
<div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold" id="kategoriModalLabel">Semua Kategori Warung</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-4 pt-3">
        <div class="kategori-grid" style="justify-content: flex-start;">
            <div class="kategori-item">
                <a href="{{ route('home', array_filter(['search' => request('search'), 'kabupaten' => request('kabupaten'), 'urutan' => request('urutan')])) }}" class="text-decoration-none text-dark kategori-ajax-link" data-kategori-id="">
                    <div class="card kategori-card border-0 shadow-sm {{ !request('kategori') ? 'border border-warning border-2' : '' }}">
                        <div class="kategori-card-icon">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                        <h6 class="kategori-card-label">Semua</h6>
                    </div>
                </a>
            </div>
            @foreach($kategori as $item)
                <div class="kategori-item">
                    <a href="{{ route('kategori.show', array_merge(['slug' => $item->slug], array_filter(['search' => request('search'), 'kabupaten' => request('kabupaten'), 'urutan' => request('urutan')]))) }}" class="text-decoration-none text-dark kategori-ajax-link" data-kategori-id="{{ $item->id_kategori }}" data-kategori-slug="{{ $item->slug }}">
                        <div class="card kategori-card border-0 shadow-sm {{ (string)request('kategori') === (string)$item->id_kategori ? 'border border-warning border-2' : '' }}">
                            <div class="kategori-card-icon">
                                <i class="bi {{ $icons[$item->nama_kategori] ?? 'bi-shop' }}"></i>
                            </div>
                            <h6 class="kategori-card-label">{{ $item->nama_kategori }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

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
        .warung-slider-track { padding-left: 16px; padding-right: 16px; gap: 12px; }
        .warung-slider-track > .warung-card-item { width: calc(100vw - 90px); max-width: 260px; min-width: 220px; }
        .warung-slider-btn { display: none; }
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
                    url('{{ asset('images/hero10.png') }}') center/cover no-repeat;
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

    // State filter aktif — selalu disinkronkan dengan URL saat ini agar
    // form pencarian bisa membaca kategori/kabupaten yang sedang aktif,
    // termasuk saat kategori aktif via path (/kategori/slug), bukan query string.
    const activeFilters = {
        kategori: '',
        kabupaten: '',
        urutan: '',
    };

    function syncActiveFiltersFromUrl(urlStr) {
        if (!urlStr) return;
        try {
            const urlObj = new URL(urlStr, window.location.href);
            const qs = urlObj.searchParams;

            // Kabupaten dan urutan selalu di query string
            activeFilters.kabupaten = qs.get('kabupaten') || '';
            activeFilters.urutan    = qs.get('urutan')    || '';

            // Kategori: cek query string dulu, lalu cek path /kategori/slug
            let katId = qs.get('kategori') || '';
            if (!katId && urlObj.pathname.includes('/kategori/')) {
                const slug = urlObj.pathname.split('/kategori/')[1]?.split('?')[0];
                if (slug) {
                    const matchedLink = document.querySelector(`.kategori-ajax-link[data-kategori-slug="${slug}"]`);
                    if (matchedLink) {
                        katId = matchedLink.getAttribute('data-kategori-id') || '';
                    }
                }
            }
            activeFilters.kategori = katId;
        } catch (e) {
            console.error(e);
        }
    }

    function updateActiveCategoryCards(urlStr) {
        if (!urlStr) return;
        try {
            syncActiveFiltersFromUrl(urlStr);
            const katId = activeFilters.kategori;

            document.querySelectorAll('.kategori-item').forEach(item => {
                const link = item.querySelector('.kategori-ajax-link');
                const card = item.querySelector('.kategori-card');
                if (!card || !link) return;

                const linkKatId = link.getAttribute('data-kategori-id') || '';
                if (String(linkKatId) === String(katId)) {
                    card.classList.add('border', 'border-warning', 'border-2');
                    card.classList.remove('border-0');
                } else {
                    card.classList.remove('border', 'border-warning', 'border-2');
                    card.classList.add('border-0');
                }
            });
        } catch (e) {
            console.error(e);
        }
    }

    const warungHasilEl = document.getElementById('warung-hasil');

    function muatHasilWarung(url, { pushState = true } = {}) {
        if (!warungHasilEl) return;

        updateActiveCategoryCards(url);

        warungHasilEl.classList.add('is-loading');

        const urlAjax = new URL(url, window.location.href);

        fetch(urlAjax.toString(), { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        })
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

                    const select = heroSearchForm.querySelector('select[name="kabupaten"]');
                    if (select) select.value = urlAjax.searchParams.get('kabupaten') || '';
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

    // Inisialisasi state filter dari URL awal saat halaman pertama dibuka
    syncActiveFiltersFromUrl(window.location.href);

    // Form pencarian hero
    const heroSearchForm = document.querySelector('.hero-search');
    if (heroSearchForm) {
        heroSearchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const searchInput = heroSearchForm.querySelector('input[name="search"]');
            const kabupatenSelect = heroSearchForm.querySelector('select[name="kabupaten"]');
            const params = new URLSearchParams();

            // Ambil kabupaten dari select form hero atau activeFilters
            const selectedKab = kabupatenSelect ? kabupatenSelect.value : activeFilters.kabupaten;
            if (selectedKab) {
                params.set('kabupaten', selectedKab);
                activeFilters.kabupaten = selectedKab;
            }

            if (activeFilters.urutan)    params.set('urutan',    activeFilters.urutan);
            if (activeFilters.kategori)  params.set('kategori',  activeFilters.kategori);

            // Set kata kunci pencarian
            if (searchInput && searchInput.value.trim() !== '') {
                params.set('search', searchInput.value.trim());
            }

            const url = '{{ route("home") }}' + (params.toString() ? '?' + params.toString() : '');
            muatHasilWarung(url);
        });

        const kabupatenSelect = heroSearchForm.querySelector('select[name="kabupaten"]');
        if (kabupatenSelect) {
            kabupatenSelect.addEventListener('change', function() {
                activeFilters.kabupaten = this.value;
            });
        }
    }

    // dalam #warung-hasil yang menunjuk balik ke route('home'), serta kategori links
    document.addEventListener('click', function (e) {
        const link = e.target.closest(
            '#warung-hasil .warung-dropdown-item, #warung-hasil .urutan-pill-link, #warung-hasil .hasil-chip a, #warung-hasil .pagination a, .kategori-ajax-link'
        );
        if (!link) return;

        e.preventDefault();

        // Otomatis hilangkan/tutup modal jika pilihan diklik dari dalam pop-up modal (seperti #kategoriModal)
        const modalEl = link.closest('.modal');
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        }

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
    // ===== Hero Slider (Zero-Stutter Dual-Layer Crossfade) =====
    document.addEventListener("DOMContentLoaded", function () {
        const slides = document.querySelectorAll(".hero-slide");
        const dots = document.querySelectorAll(".hero-dot");

        if (!slides.length) return;

        let currentIndex = 0;
        let slideInterval;
        const duration = 4500; // 4.5s per slide (lively & engaging)

        function goToSlide(nextIndex) {
            if (nextIndex === currentIndex) return;

            const prevIndex = currentIndex;
            currentIndex = (nextIndex + slides.length) % slides.length;

            // Retain previous slide at z-index: 1 while new slide blooms at z-index: 2
            slides.forEach(s => s.classList.remove("prev"));
            slides[prevIndex].classList.add("prev");
            slides[prevIndex].classList.remove("active");

            // Activate new slide
            slides[currentIndex].classList.add("active");

            // Update dot indicators
            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i === currentIndex);
            });

            // Clean up previous slide layer after crossfade finishes (1.4s)
            setTimeout(() => {
                if (slides[prevIndex] && !slides[prevIndex].classList.contains("active")) {
                    slides[prevIndex].classList.remove("prev");
                }
            }, 1400);
        }

        function startSlideShow() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, duration);
        }

        // Add click events to dots
        dots.forEach((dot, i) => {
            dot.addEventListener("click", function () {
                goToSlide(i);
                startSlideShow();
            });
        });

        // Initialize slider
        startSlideShow();
    });
</script>

@endsection