{{--
    Partial hasil warung (dropdown urutan + grid/slider warung).
    Dipakai oleh home.blade.php (load halaman penuh) DAN oleh
    HomeController@searchAjax (load via fetch, tanpa reload).
    Markup di sini harus tetap satu-satunya sumber, supaya tampilan
    hasil pencarian selalu sama persis di kedua mode.
--}}

<div class="mb-4 mt-4">
    <div class="d-flex overflow-auto gap-2 pb-2" style="white-space: nowrap; scrollbar-width: none; -ms-overflow-style: none;">
        @foreach($urutanOptions as $key => $opsi)
            @php $isAktif = $urutan === $key; @endphp
            <a class="btn btn-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 text-decoration-none urutan-pill-link {{ $isAktif ? 'btn-warning text-white fw-bold shadow-sm' : 'btn-outline-secondary bg-white text-secondary border' }}"
               style="font-size: 0.875rem; transition: all 0.2s;"
               href="{{ route('home', array_filter([
                    'search'    => request('search'),
                    'kategori'  => request('kategori'),
                    'kabupaten' => request('kabupaten'),
                    'urutan'    => $key,
               ])) }}">
                <i class="bi {{ $opsi['icon'] }}" style="color: {{ $isAktif ? '#fff' : '#C85C2E' }};"></i>
                {{ $opsi['label'] }}
            </a>
        @endforeach
    </div>
</div>

@if($sedangFilter)

    <div class="hasil-context mb-4">

        <div class="hasil-context-count">
            <strong>{{ $warungPilihan->total() }}</strong> warung ditemukan
            @if(isset($kategoriAktif) && $kategoriAktif) kategori <strong>{{ $kategoriAktif->nama_kategori }}</strong> @endif
            @if($kabupatenAktif) di <strong>{{ $kabupatenAktif->nama_kabupaten }}</strong> @endif
            @if(request('search')) untuk &ldquo;<strong>{{ request('search') }}</strong>&rdquo; @endif
        </div>

        <div class="hasil-context-chips">

            @if(request('search'))
                <span class="hasil-chip">
                    🔍 {{ request('search') }}
                    <a href="{{ route('home', array_filter([
                        'kategori'  => request('kategori'),
                        'urutan'    => request('urutan'),
                        'kabupaten' => request('kabupaten'),
                    ])) }}" aria-label="Hapus kata kunci pencarian">✕</a>
                </span>
            @endif

            @if($kabupatenAktif)
                <span class="hasil-chip">
                    📍 {{ $kabupatenAktif->nama_kabupaten }}
                    <a href="{{ route('home', array_filter([
                        'search'   => request('search'),
                        'kategori' => request('kategori'),
                        'urutan'   => request('urutan'),
                    ])) }}" aria-label="Hapus filter kabupaten">✕</a>
                </span>
            @endif

            @if(isset($kategoriAktif) && $kategoriAktif)
                <span class="hasil-chip">
                    🏷️ {{ $kategoriAktif->nama_kategori }}
                    <a href="{{ route('home', array_filter([
                        'search'    => request('search'),
                        'kabupaten' => request('kabupaten'),
                        'urutan'    => request('urutan'),
                    ])) }}" aria-label="Hapus filter kategori">✕</a>
                </span>
            @endif

        </div>

    </div>

    @if($warungPilihan->count())

        <div class="warung-grid">

            @foreach($warungPilihan as $item)

                @include('partials.warung-card', ['item' => $item])

            @endforeach

        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $warungPilihan->links() }}
        </div>

    @else

        <div class="alert alert-warning text-center rounded-4">
            Belum ada warung yang cocok
            @if(isset($kategoriAktif) && $kategoriAktif) untuk kategori <strong>{{ $kategoriAktif->nama_kategori }}</strong> @endif
            @if($kabupatenAktif) di <strong>{{ $kabupatenAktif->nama_kabupaten }}</strong> @endif
            @if(request('search')) dengan pencarian &ldquo;<strong>{{ request('search') }}</strong>&rdquo; @endif
            . Coba kata kunci, kategori, atau kabupaten lain.
        </div>

    @endif

@else

@php
    $tampilkanPerKategori = !request('kategori');

    $groupedWarung = $tampilkanPerKategori
        ? $warungPilihan->groupBy(fn($w) => $w->kategori->nama_kategori ?? 'Lainnya')
        : collect(['__single__' => $warungPilihan]);
@endphp

@forelse($groupedWarung as $namaGrup => $items)

    @if($tampilkanPerKategori)

        <h3 class="fw-bold mt-5 mb-4">
            <i class="bi {{ $icons[$namaGrup] ?? 'bi-shop' }} text-warning"></i> {{ $namaGrup }}
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

@endif