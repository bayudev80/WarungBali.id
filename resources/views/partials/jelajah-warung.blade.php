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

            <a href="{{ route('kategori.show', $item->slug) }}"
               data-kategori-id="{{ $item->id_kategori }}"
               class="text-decoration-none text-dark">

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

    <h2 class="fw-bold mb-5">

      @if($sedangFilter)
      Hasil Pencarian
      @else
      Warung Populer
      @endif
    </h2>

    @php
        $urutanOptions = [
            'populer'    => 'Terpopuler',
            'disukai'    => 'Banyak Disukai',
            'rating'     => 'Rating Tertinggi',
            'terbaru'    => 'Terbaru',
            'terjangkau' => 'Harga Terjangkau',
        ];
    @endphp

    <div class="mb-4 d-flex flex-wrap gap-2">

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
                                'search'    => request('search'),
                                'kategori'  => request('kategori'),
                                'kabupaten' => request('kabupaten'),
                                'urutan'    => $key,
                           ])) }}">
                            {{ $label }}
                        </a>
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

    @if($sedangFilter)

        <div class="hasil-context mb-4">

            <div class="hasil-context-count">
                <strong>{{ $warungPilihan->total() }}</strong> warung ditemukan
                @if($kabupatenAktif) di <strong>{{ $kabupatenAktif->nama_kabupaten }}</strong> @endif
                @if(request('search')) untuk &ldquo;<strong>{{ request('search') }}</strong>&rdquo; @endif
            </div>

            <div class="hasil-context-chips">

                @if(request('search'))
                    <span class="hasil-chip">
                        <i class="bi bi-search me-1 text-muted"></i> {{ request('search') }}
                        <a href="{{ route('home', array_filter([
                            'kategori'  => request('kategori'),
                            'urutan'    => request('urutan'),
                            'kabupaten' => request('kabupaten'),
                        ])) }}" aria-label="Hapus kata kunci pencarian">✕</a>
                    </span>
                @endif

                @if($kabupatenAktif)
                    <span class="hasil-chip">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $kabupatenAktif->nama_kabupaten }}
                        <a href="{{ route('home', array_filter([
                            'search'   => request('search'),
                            'kategori' => request('kategori'),
                            'urutan'   => request('urutan'),
                        ])) }}" aria-label="Hapus filter kabupaten">✕</a>
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
                @if($kabupatenAktif) di {{ $kabupatenAktif->nama_kabupaten }} @endif
                @if(request('search')) untuk &ldquo;{{ request('search') }}&rdquo; @endif
                . Coba kata kunci atau kabupaten lain.
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

  </div>
  <!-- /container -->

</section>
