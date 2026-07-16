@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="hero" style="background-image: url('{{ asset('images/hero.jpeg') }}');">

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

      <div class="hero-tags">

      <span>🍛 Warung Makan</span>

      <span>🥤 Warung Minuman</span>

      <span>🛒 Warung Sembako</span>

      <span>🎁 Oleh-Oleh Bali</span>

      </div>

    </div>

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

        <a href="{{ route('home', array_filter(['search' => request('search')])) }}" class="text-decoration-none text-dark">

            <div class="card border-0 shadow-sm p-3 h-100 {{ !request('kategori') ? 'border border-warning border-2' : '' }}">

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

            <a href="{{ route('home', array_filter(['search' => request('search'), 'kategori' => $item->id_kategori])) }}" class="text-decoration-none text-dark">

                <div class="card border-0 shadow-sm p-3 h-100 {{ request('kategori') == $item->id_kategori ? 'border border-warning border-2' : '' }}">

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

    @if(request('search'))

    <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4">

      <h5 class="mb-1">
        🔍 Hasil pencarian untuk:
        <strong>"{{ request('search') }}"</strong>
      </h5>

      <small class="text-secondary">
        Ditemukan <strong>{{ $warung->count() }}</strong> warung
      </small>

    </div>

    @endif
    <div class="row">

      @foreach($warung as $item)

      @php
    $isFavorit = auth()->check() &&
        $item->favorit->where('id_user', auth()->user()->id_user)->count() > 0;
      @endphp

      <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow h-100">

          <div class="position-relative">

    <img src="{{ asset('images/warung/'.$item->foto) }}"
        class="card-img-top"
        style="height:220px;object-fit:cover;">


<button
    class="btn btn-light rounded-circle shadow favorite-btn position-absolute top-0 end-0 m-2"
    data-id="{{ $item->id_warung }}"
    data-login="{{ auth()->check() ? 'true' : 'false' }}"
    style="width:45px;height:45px;">

    @auth

        @if($isFavorit)
            <i class="fa-solid fa-heart text-danger"></i>
        @else
            <i class="fa-regular fa-heart"></i>
        @endif

    @else

        <i class="fa-regular fa-heart"></i>

    @endauth

</button>


</div>

          <div class="card-body">

            <h4 class="fw-bold">
              {{ $item->nama_warung }}
            </h4>

            <p class="text-secondary">
              📍 {{ $item->alamat }}
            </p>

            <p>
              {{ $item->deskripsi }}
            </p>

            <p class="text-warning fw-bold">
              Rp{{ number_format($item->harga_min,0,',','.') }}
              -
              Rp{{ number_format($item->harga_max,0,',','.') }}
            </p>

            <p>
              🕒
              {{ substr($item->jam_buka,0,5) }}
              -
              {{ substr($item->jam_tutup,0,5) }}
            </p>

            <button class="btn btn-warning text-white w-100" data-bs-toggle="modal"
              data-bs-target="#detail{{ $item->id_warung }}">

              Lihat Detail

            </button>

          </div>

        </div>

      </div>

      <!-- MODAL DETAIL WARUNG -->
      <div class="modal fade" id="detail{{ $item->id_warung }}" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

          <div class="modal-content border-0 rounded-4 overflow-hidden">

            <!-- HEADER IMAGE -->
            <div class="position-relative">

              <img src="{{ asset('images/warung/'.$item->foto) }}" class="w-100" style="height:320px;object-fit:cover;">

              <button type="button" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3"
                data-bs-dismiss="modal" style="width:50px;height:50px;">
                ✕
              </button>

              <div class="position-absolute bottom-0 start-0 p-4 text-white w-100"
                style="background:linear-gradient(transparent,rgba(0,0,0,.75));">

                <span class="badge bg-warning text-dark px-3 py-2 mb-3">
                  Kuliner Bali
                </span>

                <h2 class="fw-bold mb-3">
                  {{ $item->nama_warung }}
                </h2>

                <div class="d-flex flex-wrap gap-4">

                  <div>
                    @if($item->review->count() > 0)
                    ⭐
                    <strong>{{ number_format($item->review->avg('rating'), 1) }}</strong>
                    <span class="text-light">
                      ({{ $item->review->count() }} Ulasan)
                    </span>
                    @else
                    ⭐
                    <strong>0.0</strong>
                    <span class="text-light">
                      (Belum ada ulasan)
                    </span>
                    @endif
                  </div>

                  <div>
                    🕒
                    {{ substr($item->jam_buka,0,5) }}
                    -
                    {{ substr($item->jam_tutup,0,5) }}
                  </div>

                  <div>
                    📞 {{ $item->telepon }}
                  </div>

                </div>

              </div>

            </div>

            <!-- BODY -->
            <div class="modal-body p-4">

              <!-- TAB -->
              <ul class="nav nav-tabs mb-4">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info{{ $item->id_warung }}">
                    Informasi
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#menu{{ $item->id_warung }}">
                    Menu
                  </button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#review{{ $item->id_warung }}">
                    Ulasan
                  </button>
                </li>

              </ul>

              <div class="tab-content">

                <!-- INFORMASI -->
                <div class="tab-pane fade show active" id="info{{ $item->id_warung }}">

                  <p class="fs-5 text-secondary">

                    {{ $item->deskripsi }}

                  </p>

                  <div class="rounded-4 p-4 mt-4" style="background:#FFF8EC;">

                    <p class="mb-3">
                      📍
                      <strong>Alamat</strong><br>
                      {{ $item->alamat }}
                    </p>

                    <p class="mb-3">
                      🕒
                      <strong>Jam Operasional</strong><br>

                      {{ substr($item->jam_buka,0,5) }}

                      -

                      {{ substr($item->jam_tutup,0,5) }}

                    </p>

                    <p class="mb-0">
                      📞
                      <strong>Telepon</strong><br>

                      {{ $item->telepon }}

                    </p>

                  </div>

                  <h5 class="mt-5 fw-bold">
                    Kisaran Harga
                  </h5>

                  <h4 class="text-warning fw-bold">

                    Rp{{ number_format($item->harga_min,0,',','.') }}

                    -

                    Rp{{ number_format($item->harga_max,0,',','.') }}

                  </h4>

                  <div class="mt-4">

                    <span class="badge bg-warning-subtle text-dark me-2 p-2">
                      Legendaris
                    </span>

                    <span class="badge bg-warning-subtle text-dark me-2 p-2">
                      Kuliner Bali
                    </span>

                    <span class="badge bg-warning-subtle text-dark me-2 p-2">
                      Favorit Wisatawan
                    </span>

                  </div>

                </div>
                
                <!-- MENU -->
                <div class="tab-pane fade" id="menu{{ $item->id_warung }}">

                  <div class="row g-4 mt-3">

                    @forelse($item->menu as $menu)

                    <div class="col-md-6">

                      <div class="card border-0 shadow-sm rounded-4 h-100">

                        <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" class="card-img-top"
                          style="height:180px;object-fit:cover;" alt="{{ $menu->nama_menu }}">

                        <div class="card-body">

                          <h5 class="fw-bold">
                            {{ $menu->nama_menu }}
                          </h5>

                          <p class="text-secondary">
                            {{ $menu->deskripsi }}
                          </p>

                          <h5 class="text-warning fw-bold">
                            Rp{{ number_format($menu->harga,0,',','.') }}
                          </h5>

                        </div>

                      </div>

                    </div>

                    @empty

                    <div class="col-12">

                      <div class="alert alert-warning text-center rounded-4">

                        Belum ada menu untuk warung ini.

                      </div>

                    </div>

                    @endforelse

                  </div>

                </div>

                <!-- ULASAN -->
                <div class="tab-pane fade" id="review{{ $item->id_warung }}">

                  <div class="mt-3">

                    @forelse($item->review as $review)

                    <div class="border rounded-4 p-4 mb-3">

                      <div class="d-flex justify-content-between align-items-center mb-2">

                        <strong>
                          👤 {{ $review->user->nama }}
                        </strong>

                        <small class="text-secondary">
                          {{ date('d M Y', strtotime($review->created_at)) }}
                        </small>

                      </div>

                      <div class="mb-2">

                        @for($i = 1; $i <= 5; $i++) @if($i <=$review->rating)

                          <span class="text-warning fs-5">★</span>

                          @else

                          <span class="text-secondary fs-5">☆</span>

                          @endif

                          @endfor

                      </div>

                      <p class="mb-0">
                        {{ $review->komentar }}
                      </p>

                    </div>

                    @empty

                    <div class="alert alert-warning rounded-4">

                      Belum ada ulasan untuk warung ini.

                    </div>

                    @endforelse

                  </div>

                </div>
                <!-- /tab-pane review -->

              </div>
              <!-- /tab-content -->

            </div>
            <!-- /modal-body -->

          </div>
          <!-- /modal-content -->

        </div>
        <!-- /modal-dialog -->

      </div>
      <!-- /modal fade -->

      @endforeach

    </div>
    <!-- /row -->

  </div>
  <!-- /container -->

</section>

@endsection