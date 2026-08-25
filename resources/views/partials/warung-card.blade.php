@php
    $avgRating   = round($item->review->avg('rating') ?? 0, 1);
    $totalReview = $item->review->count();
    $isFavorit   = auth()->check() &&
        $item->favorit->where('id_user', auth()->user()->id_user)->count() > 0;
    // Ambil 2 menu unggulan (nama saja)
    $menuUnggulan = $item->menu_tampil->take(2)->pluck('nama_menu');
    $isMyWarung  = auth()->check() && (auth()->user()->id_user === $item->id_user);
@endphp

<div class="warung-card-item mb-4 d-flex flex-column">

    <div class="card warung-card border-0 shadow flex-fill w-100 position-relative" @if($isMyWarung) style="border: 2px solid #f59e0b !important;" @endif>

        {{-- Badge Penanda Warung Milik Sendiri --}}
        @if($isMyWarung)
            <div class="position-absolute top-0 start-0 m-2 z-3">
                <span class="badge rounded-pill px-2 py-1 shadow text-dark fw-bold d-inline-flex align-items-center gap-1" style="background: #fef08a; border: 1.5px solid #facc15; font-size: 11px;">
                    <i class="bi bi-shop text-dark"></i> Warung Milik Anda
                </span>
            </div>
        @endif

        {{-- GAMBAR --}}
        <div class="warung-card__img-wrap position-relative">

            <img src="{{ asset('images/warung/'.$item->foto) }}"
                 class="warung-card__img" alt="{{ $item->nama_warung }}">

            {{-- Overlay gradient bawah --}}
            <div class="warung-card__img-overlay"></div>


            {{-- Tombol favorit --}}
            <button
                class="warung-card__fav-btn favorite-btn"
                data-id="{{ $item->id_warung }}"
                data-login="{{ auth()->check() ? 'true' : 'false' }}"
                aria-label="Favorit">
                @auth
                    <i class="{{ $isFavorit ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                @else
                    <i class="fa-regular fa-heart"></i>
                @endauth
            </button>

        </div>

        {{-- BODY --}}
        <div class="warung-card__body">

            {{-- Nama Warung --}}
            <h4 class="warung-card__name">{{ $item->nama_warung }}</h4>

            {{-- Baris 1: Lokasi --}}
            <p class="warung-card__location">
                <i class="bi bi-geo-alt-fill"></i>
                {{ $item->kabupaten->nama_kabupaten ?? $item->alamat }}
            </p>

            {{-- Baris 2: Rating & Ulasan --}}
            <div class="warung-card__rating">
                @if($totalReview > 0)
                    <span class="warung-card__stars">
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= floor($avgRating))
                                <i class="bi bi-star-fill"></i>
                            @elseif($s - $avgRating < 1 && $s - $avgRating > 0)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </span>
                    <strong>{{ number_format($avgRating, 1) }}</strong>
                    <span class="warung-card__review-count">· {{ $totalReview }} ulasan</span>
                @else
                    <span class="warung-card__no-review">
                        <i class="bi bi-star"></i> Belum ada ulasan
                    </span>
                @endif


            </div>

            {{-- Baris 3: Menu unggulan — selalu render supaya tinggi card konsisten --}}
            <p class="warung-card__menu-chips">
                @if($menuUnggulan->isNotEmpty())
                    <i class="bi bi-bowl-hot-fill"></i>
                    @foreach($menuUnggulan as $mn)
                        <span class="warung-card__chip">{{ $mn }}</span>
                    @endforeach
                @endif
            </p>

            {{-- Footer: Harga & Jam --}}
            <div class="warung-card__footer">

                <div class="warung-card__meta">
                    <span class="warung-card__price">
                        <i class="bi bi-currency-exchange"></i>
                        Mulai Rp{{ number_format($item->harga_min, 0, ',', '.') }}
                    </span>
                    <span class="warung-card__hours">
                        <i class="bi bi-clock"></i>
                        {{ substr($item->jam_buka, 0, 5) }} – {{ substr($item->jam_tutup, 0, 5) }}
                    </span>
                </div>

                <button
                    class="warung-card__cta"
                    data-bs-toggle="modal"
                    data-bs-target="#detail{{ $item->id_warung }}">
                    Lihat Detail
                    <i class="bi bi-arrow-right"></i>
                </button>

            </div>

        </div>

    </div>

</div>

      <!-- MODAL DETAIL WARUNG -->
      <div class="modal fade" id="detail{{ $item->id_warung }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->id_warung }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content border-0">

            <!-- HERO HEADER -->
            <div class="detail-modal-hero">
              <img src="{{ asset('images/warung/'.$item->foto) }}" alt="{{ $item->nama_warung }}">

              <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Tutup">
                <i class="bi bi-x-lg"></i>
              </button>

              <div class="detail-modal-overlay">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                  <span class="detail-cat-badge">
                    <i class="bi bi-shop"></i> {{ $item->kategori->nama_kategori ?? 'Kuliner Bali' }}
                  </span>
                  @if($isMyWarung)
                    <span class="badge rounded-pill bg-warning text-dark fw-bold px-2 py-1 shadow-sm d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                      <i class="bi bi-check-circle-fill"></i> Warung Milik Anda
                    </span>
                    <a href="{{ route('pemilik.dashboard') }}" class="btn btn-sm btn-light rounded-pill px-2 py-1 fw-bold text-dark d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                      <i class="bi bi-speedometer2"></i> Dashboard Pemilik
                    </a>
                  @endif
                </div>

                <h3 class="detail-modal-title" id="modalLabel{{ $item->id_warung }}">
                  {{ $item->nama_warung }}
                </h3>

                <div class="detail-meta-row">
                  <div id="review-summary-{{ $item->id_warung }}" class="detail-meta-item">
                    <i class="bi bi-star-fill text-warning"></i>
                    <strong class="review-summary-avg">{{ number_format($item->review->avg('rating') ?? 0, 1) }}</strong>
                    <span class="review-summary-count opacity-75">
                      ({{ $item->review->count() }} Ulasan)
                    </span>
                  </div>

                  <div class="detail-meta-item">
                    <i class="bi bi-clock"></i>
                    <span>{{ substr($item->jam_buka, 0, 5) }} – {{ substr($item->jam_tutup, 0, 5) }}</span>
                  </div>

                  @if($item->wa_link)
                    <div class="detail-meta-item">
                      <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-white text-decoration-none d-inline-flex align-items-center gap-1">
                        <i class="fa-brands fa-whatsapp text-success fs-6"></i>
                        <span>{{ $item->telepon }}</span>
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- MODAL BODY -->
            <div class="modal-body p-3 p-md-4">

              <!-- SEGMENTED PILL TABS -->
              <ul class="nav detail-nav-pills mb-4" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#info{{ $item->id_warung }}" type="button" role="tab">
                    <i class="bi bi-info-circle me-1"></i> Informasi
                  </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#menu{{ $item->id_warung }}" type="button" role="tab">
                    <i class="bi bi-journal-text me-1"></i> Menu ({{ $item->menu_tampil->count() }})
                  </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#review{{ $item->id_warung }}" type="button" role="tab">
                    <i class="bi bi-star-fill me-1"></i> Ulasan
                  </button>
                </li>
              </ul>

              <div class="tab-content">

                <!-- TAB 1: INFORMASI -->
                <div class="tab-pane fade show active" id="info{{ $item->id_warung }}" role="tabpanel">

                  @if($item->deskripsi)
                    <div class="detail-desc-box mb-4">
                      <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-quote fs-3 text-warning opacity-75 lh-1"></i>
                        <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                          {{ $item->deskripsi }}
                        </p>
                      </div>
                    </div>
                  @endif

                  <!-- GRID INFO TILES -->
                  <div class="detail-info-grid mb-4">
                    <!-- Tile Lokasi -->
                    <div class="detail-info-tile">
                      <div class="detail-tile-icon icon-geo">
                        <i class="bi bi-geo-alt-fill"></i>
                      </div>
                      <div class="detail-tile-body">
                        <h6>Alamat & Lokasi</h6>
                        <p class="small mb-1">{{ $item->alamat }}</p>
                        @if($item->maps_link)
                          <a href="{{ $item->maps_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-warning rounded-pill py-1 px-3 mt-1 fw-bold" style="font-size: 12px;">
                            <i class="fa-solid fa-map-location-dot me-1"></i> Buka Google Maps
                          </a>
                        @endif
                      </div>
                    </div>

                    <!-- Tile Jam Operasional -->
                    <div class="detail-info-tile">
                      <div class="detail-tile-icon icon-clock">
                        <i class="bi bi-clock-fill"></i>
                      </div>
                      <div class="detail-tile-body">
                        <h6>Jam Operasional</h6>
                        <p class="mb-0">
                          {{ substr($item->jam_buka, 0, 5) }} – {{ substr($item->jam_tutup, 0, 5) }} WITA
                        </p>
                      </div>
                    </div>

                    <!-- Tile Kontak -->
                    <div class="detail-info-tile">
                      <div class="detail-tile-icon icon-phone">
                        <i class="bi bi-telephone-fill"></i>
                      </div>
                      <div class="detail-tile-body">
                        <h6>Kontak Pemilik</h6>
                        @if($item->wa_link)
                          <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-success fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="fa-brands fa-whatsapp fs-6"></i> {{ $item->telepon }}
                          </a>
                        @else
                          <p class="text-muted small mb-0">Nomor belum tersedia</p>
                        @endif
                      </div>
                    </div>

                    <!-- Tile Rentang Harga -->
                    <div class="detail-info-tile">
                      <div class="detail-tile-icon icon-price">
                        <i class="bi bi-cash-stack"></i>
                      </div>
                      <div class="detail-tile-body">
                        <h6>Kisaran Harga</h6>
                        <p class="fw-bold mb-0" style="color: #c85c2e;">
                          Rp{{ number_format($item->harga_min, 0, ',', '.') }} – Rp{{ number_format($item->harga_max, 0, ',', '.') }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- BADGES FITUR & LAYANAN -->
                  <div class="mb-4">
                    <h6 class="fw-bold mb-2 small text-uppercase text-muted" style="letter-spacing: 0.05em;">Layanan & Keunggulan</h6>
                    <div class="d-flex flex-wrap">
                      @if($item->menerima_catering)
                        <span class="detail-tag-pill tag-catering">
                          <i class="bi bi-check-circle-fill"></i> Menerima Pesanan Katering
                        </span>
                      @endif

                      @if($item->is_legendaris)
                        <span class="detail-tag-pill tag-highlight">
                          <i class="bi bi-award-fill"></i> Warung Legendaris
                        </span>
                      @endif

                      @if($item->is_kuliner)
                        <span class="detail-tag-pill tag-highlight">
                          <i class="bi bi-fire"></i> Kuliner Khas Bali
                        </span>
                      @endif

                      @if($item->is_favorit_wisatawan)
                        <span class="detail-tag-pill tag-highlight">
                          <i class="bi bi-heart-fill text-danger"></i> Favorit Wisatawan
                        </span>
                      @endif
                    </div>
                  </div>

                  <!-- CABANG WARUNG -->
                  @if($item->is_cabang && $item->indukWarung)
                    <div class="detail-cabang-box mb-3">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-shop text-warning fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">Cabang dari Warung Utama</h6>
                      </div>
                      <p class="text-secondary small mb-2">
                        Warung ini merupakan cabang resmi dari <strong>{{ $item->indukWarung->nama_warung }}</strong>.
                      </p>
                      <a href="#" class="btn btn-sm btn-warning text-white rounded-pill px-3 fw-bold btn-lihat-cabang"
                         data-cabang-target="#detail{{ $item->indukWarung->id_warung }}">
                        <i class="bi bi-box-arrow-in-up-right me-1"></i> Lihat Warung Utama
                      </a>
                    </div>
                  @endif

                  @if(!$item->is_cabang && $item->cabang->count() > 0)
                    <div class="detail-cabang-box mb-3">
                      <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                          <i class="bi bi-shop text-warning fs-5"></i>
                          <h6 class="fw-bold mb-0 text-dark">Cabang Lainnya ({{ $item->cabang->count() }})</h6>
                        </div>
                      </div>

                      @foreach($item->cabang as $cabang)
                        <div class="detail-cabang-item">
                          <div>
                            <strong class="text-dark">{{ $cabang->nama_warung }}</strong>
                            <div class="text-secondary small">
                              <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $cabang->alamat }}
                            </div>
                          </div>
                          <a href="#" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold btn-lihat-cabang"
                             data-cabang-target="#detail{{ $cabang->id_warung }}">
                            Lihat Detail
                          </a>
                        </div>
                      @endforeach
                    </div>
                  @endif

                </div>

                <!-- TAB 2: MENU -->
                <div class="tab-pane fade" id="menu{{ $item->id_warung }}" role="tabpanel">
                  <div class="row g-3">
                    @forelse($item->menu_tampil as $menu)
                      <div class="col-md-6">
                        <div class="detail-menu-card">
                          <div class="detail-menu-img-wrap">
                            <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" alt="{{ $menu->nama_menu }}">
                            <span class="detail-menu-price-tag">
                              Rp{{ number_format($menu->harga, 0, ',', '.') }}
                            </span>
                          </div>
                          <div class="detail-menu-body">
                            <h5 class="detail-menu-title">{{ $menu->nama_menu }}</h5>
                            @if($menu->deskripsi)
                              <p class="detail-menu-desc">{{ $menu->deskripsi }}</p>
                            @endif
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="col-12 text-center py-5">
                        <div class="fs-1 text-muted mb-2"><i class="bi bi-journal-x"></i></div>
                        <h6 class="fw-bold text-secondary">Belum ada daftar menu</h6>
                        <p class="text-muted small">Menu makanan dan minuman untuk warung ini akan segera ditambahkan.</p>
                      </div>
                    @endforelse
                  </div>
                </div>

                <!-- TAB 3: ULASAN -->
                <div class="tab-pane fade" id="review{{ $item->id_warung }}" role="tabpanel">
                  <!-- Form Ulasan -->
                  <div class="detail-review-form-card mb-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                      <i class="bi bi-pencil-square text-warning"></i> Bagikan Pengalaman Anda
                    </h6>

                    @auth
                      @php
                        $ulasanSaya = $item->review->firstWhere('id_user', auth()->user()->id_user);
                      @endphp

                      <form class="review-form" data-warung-id="{{ $item->id_warung }}">
                        @csrf
                        <div class="mb-3">
                          <label class="form-label small fw-bold text-muted mb-1">Beri Rating Bintang</label>
                          <div class="star-rating d-inline-block" data-target="rating-input-{{ $item->id_warung }}">
                            @for ($i = 1; $i <= 5; $i++)
                              <span class="star" data-value="{{ $i }}" style="font-size:1.8rem;cursor:pointer;color:#d1d5db;transition:color 0.2s;">★</span>
                            @endfor
                          </div>
                          <input type="hidden" name="rating" id="rating-input-{{ $item->id_warung }}" value="{{ $ulasanSaya->rating ?? 0 }}">
                        </div>

                        <div class="mb-3">
                          <textarea name="komentar" class="form-control rounded-3" rows="3" placeholder="Ceritakan rasa masakan, suasana, dan pelayanan di warung ini..." style="font-size:14px;border-color:#e5e7eb;">{{ $ulasanSaya->komentar ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-submit-review">
                          {{ $ulasanSaya ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                        </button>

                        <div class="review-form-message small mt-2"></div>
                      </form>
                    @else
                      <div class="text-center py-3">
                        <p class="text-secondary mb-3 small">
                          Masuk ke akun Anda untuk membagikan ulasan dan rating pada warung ini.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-submit-review px-4">
                          <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Mengulas
                        </a>
                      </div>
                    @endauth
                  </div>

                  <!-- Review List -->
                  <div id="review-list-{{ $item->id_warung }}">
                    @forelse($item->review as $review)
                      <div class="detail-review-bubble" data-review-user="{{ $review->id_user }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <div class="d-flex align-items-center gap-2">
                            <div class="detail-avatar-circle">
                              {{ strtoupper(substr($review->user->nama ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                              <strong class="text-dark small d-block">{{ $review->user->nama }}</strong>
                              <span class="text-muted" style="font-size:11px;">{{ date('d M Y', strtotime($review->created_at)) }}</span>
                            </div>
                          </div>
                          <div>
                            @for($i = 1; $i <= 5; $i++)
                              @if($i <= $review->rating)
                                <span class="text-warning fs-6">★</span>
                              @else
                                <span class="text-muted opacity-50 fs-6">☆</span>
                              @endif
                            @endfor
                          </div>
                        </div>
                        <p class="mb-0 text-secondary" style="font-size:13.5px; line-height: 1.5;">
                          {{ $review->komentar }}
                        </p>
                      </div>
                    @empty
                      <div class="alert alert-warning rounded-4 review-empty-state text-center py-4 border-0" style="background:#FFF7ED; color:#9A3412;">
                        <i class="bi bi-chat-heart fs-3 d-block mb-1"></i>
                        <strong>Belum ada ulasan untuk warung ini.</strong>
                        <div class="small text-muted mt-1">Jadilah yang pertama memberikan ulasan dan rating!</div>
                      </div>
                    @endforelse
                  </div>

                </div>

              </div>

            </div>

          </div>
        </div>
      </div>

      @if(!$item->is_cabang && $item->cabang->count() > 0)
        {{-- Modal detail untuk setiap cabang --}}
        @foreach($item->cabang as $cabang)
          <div class="modal fade" id="detail{{ $cabang->id_warung }}" tabindex="-1" aria-labelledby="modalLabel{{ $cabang->id_warung }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content border-0">

                <!-- HERO HEADER -->
                <div class="detail-modal-hero">
                  <img src="{{ asset('images/warung/'.$cabang->foto) }}" alt="{{ $cabang->nama_warung }}">

                  <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                  </button>

                  <div class="detail-modal-overlay">
                    <div>
                      <span class="detail-cat-badge">
                        <i class="bi bi-shop"></i> Cabang Resmi
                      </span>
                    </div>

                    <h3 class="detail-modal-title" id="modalLabel{{ $cabang->id_warung }}">
                      {{ $cabang->nama_warung }}
                    </h3>

                    <div class="detail-meta-row">
                      <div class="detail-meta-item">
                        @if($cabang->review->count() > 0)
                          <i class="bi bi-star-fill text-warning"></i>
                          <strong>{{ number_format($cabang->review->avg('rating'), 1) }}</strong>
                          <span class="opacity-75">({{ $cabang->review->count() }} Ulasan)</span>
                        @else
                          <i class="bi bi-star text-warning"></i>
                          <strong class="opacity-75">0.0 (Belum ada ulasan)</strong>
                        @endif
                      </div>

                      <div class="detail-meta-item">
                        <i class="bi bi-clock"></i>
                        <span>{{ substr($cabang->jam_buka,0,5) }} – {{ substr($cabang->jam_tutup,0,5) }}</span>
                      </div>

                      @if($cabang->wa_link)
                        <div class="detail-meta-item">
                          <a href="{{ $cabang->wa_link }}" target="_blank" rel="noopener" class="text-white text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="fa-brands fa-whatsapp text-success fs-6"></i>
                            <span>{{ $cabang->telepon }}</span>
                          </a>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- MODAL BODY -->
                <div class="modal-body p-3 p-md-4">
                  <ul class="nav detail-nav-pills mb-4" role="tablist">
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link active w-100" data-bs-toggle="tab" data-bs-target="#info{{ $cabang->id_warung }}" type="button" role="tab">
                        <i class="bi bi-info-circle me-1"></i> Informasi
                      </button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#menu{{ $cabang->id_warung }}" type="button" role="tab">
                        <i class="bi bi-journal-text me-1"></i> Menu ({{ $cabang->menu_tampil->count() }})
                      </button>
                    </li>
                    <li class="nav-item flex-fill" role="presentation">
                      <button class="nav-link w-100" data-bs-toggle="tab" data-bs-target="#review{{ $cabang->id_warung }}" type="button" role="tab">
                        <i class="bi bi-star-fill me-1"></i> Ulasan
                      </button>
                    </li>
                  </ul>

                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="info{{ $cabang->id_warung }}" role="tabpanel">
                      @if($cabang->deskripsi)
                        <div class="detail-desc-box mb-4">
                          <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-quote fs-3 text-warning opacity-75 lh-1"></i>
                            <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                              {{ $cabang->deskripsi }}
                            </p>
                          </div>
                        </div>
                      @endif

                      <div class="detail-info-grid mb-4">
                        <div class="detail-info-tile">
                          <div class="detail-tile-icon icon-geo">
                            <i class="bi bi-geo-alt-fill"></i>
                          </div>
                          <div class="detail-tile-body">
                            <h6>Alamat & Lokasi</h6>
                            <p class="small mb-1">{{ $cabang->alamat }}</p>
                            @if($cabang->maps_link)
                              <a href="{{ $cabang->maps_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-warning rounded-pill py-1 px-3 mt-1 fw-bold" style="font-size: 12px;">
                                <i class="fa-solid fa-map-location-dot me-1"></i> Buka Google Maps
                              </a>
                            @endif
                          </div>
                        </div>

                        <div class="detail-info-tile">
                          <div class="detail-tile-icon icon-clock">
                            <i class="bi bi-clock-fill"></i>
                          </div>
                          <div class="detail-tile-body">
                            <h6>Jam Operasional</h6>
                            <p class="mb-0">{{ substr($cabang->jam_buka,0,5) }} – {{ substr($cabang->jam_tutup,0,5) }} WITA</p>
                          </div>
                        </div>

                        <div class="detail-info-tile">
                          <div class="detail-tile-icon icon-phone">
                            <i class="bi bi-telephone-fill"></i>
                          </div>
                          <div class="detail-tile-body">
                            <h6>Kontak Cabang</h6>
                            @if($cabang->wa_link)
                              <a href="{{ $cabang->wa_link }}" target="_blank" rel="noopener" class="text-success fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-brands fa-whatsapp fs-6"></i> {{ $cabang->telepon }}
                              </a>
                            @else
                              <p class="text-muted small mb-0">Nomor belum tersedia</p>
                            @endif
                          </div>
                        </div>

                        <div class="detail-info-tile">
                          <div class="detail-tile-icon icon-price">
                            <i class="bi bi-cash-stack"></i>
                          </div>
                          <div class="detail-tile-body">
                            <h6>Kisaran Harga</h6>
                            <p class="fw-bold mb-0" style="color: #c85c2e;">
                              Rp{{ number_format($cabang->harga_min, 0, ',', '.') }} – Rp{{ number_format($cabang->harga_max, 0, ',', '.') }}
                            </p>
                          </div>
                        </div>
                      </div>

                      <div class="detail-cabang-box mb-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                          <i class="bi bi-shop text-warning fs-5"></i>
                          <h6 class="fw-bold mb-0 text-dark">Cabang dari Warung Utama</h6>
                        </div>
                        <p class="text-secondary small mb-2">
                          Warung ini adalah cabang resmi dari <strong>{{ $item->nama_warung }}</strong>.
                        </p>
                        <a href="#" class="btn btn-sm btn-warning text-white rounded-pill px-3 fw-bold btn-lihat-cabang"
                           data-cabang-target="#detail{{ $item->id_warung }}">
                          <i class="bi bi-box-arrow-in-up-right me-1"></i> Lihat Warung Utama
                        </a>
                      </div>
                    </div>

                    <div class="tab-pane fade" id="menu{{ $cabang->id_warung }}" role="tabpanel">
                      <div class="row g-3">
                        @forelse($cabang->menu_tampil as $menu)
                          <div class="col-md-6">
                            <div class="detail-menu-card">
                              <div class="detail-menu-img-wrap">
                                <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" alt="{{ $menu->nama_menu }}">
                                <span class="detail-menu-price-tag">
                                  Rp{{ number_format($menu->harga, 0, ',', '.') }}
                                </span>
                              </div>
                              <div class="detail-menu-body">
                                <h5 class="detail-menu-title">{{ $menu->nama_menu }}</h5>
                                @if($menu->deskripsi)
                                  <p class="detail-menu-desc">{{ $menu->deskripsi }}</p>
                                @endif
                              </div>
                            </div>
                          </div>
                        @empty
                          <div class="col-12 text-center py-5">
                            <div class="fs-1 text-muted mb-2"><i class="bi bi-journal-x"></i></div>
                            <h6 class="fw-bold text-secondary">Belum ada daftar menu</h6>
                          </div>
                        @endforelse
                      </div>
                    </div>

                    <div class="tab-pane fade" id="review{{ $cabang->id_warung }}" role="tabpanel">
                      <div class="mt-3">
                        @forelse($cabang->review as $rev)
                          <div class="detail-review-bubble">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                              <div class="d-flex align-items-center gap-2">
                                <div class="detail-avatar-circle">
                                  {{ strtoupper(substr($rev->user->nama ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                  <strong class="text-dark small d-block">{{ $rev->user->nama }}</strong>
                                  <span class="text-muted" style="font-size:11px;">{{ date('d M Y', strtotime($rev->created_at)) }}</span>
                                </div>
                              </div>
                              <div>
                                @for($i = 1; $i <= 5; $i++)
                                  @if($i <= $rev->rating)
                                    <span class="text-warning fs-6">★</span>
                                  @else
                                    <span class="text-muted opacity-50 fs-6">☆</span>
                                  @endif
                                @endfor
                              </div>
                            </div>
                            <p class="mb-0 text-secondary" style="font-size:13.5px; line-height: 1.5;">
                              {{ $rev->komentar }}
                            </p>
                          </div>
                        @empty
                          <div class="alert alert-warning rounded-4 review-empty-state text-center py-4 border-0" style="background:#FFF7ED; color:#9A3412;">
                            <i class="bi bi-chat-heart fs-3 d-block mb-1"></i>
                            <strong>Belum ada ulasan untuk cabang ini.</strong>
                          </div>
                        @endforelse
                      </div>
                    </div>

                  </div>
                </div>

              </div>
            </div>
          </div>
        @endforeach
      @endif

      @once
        <script>
          // Pindah dari modal detail warung ini ke modal detail warung
          // induk/cabang-nya (tombol "Lihat Detail" cabang & link "Cabang dari").
          // Cuma didaftarkan sekali (@@once) walau partial ini dipanggil
          // berkali-kali di dalam perulangan daftar warung.
          document.addEventListener('click', function (e) {
            const link = e.target.closest('.btn-lihat-cabang');
            if (!link) return;

            e.preventDefault();

            const targetEl = document.querySelector(link.getAttribute('data-cabang-target'));

            if (!targetEl) {
              alert('Detail warung tersebut tidak tampil di halaman ini (kemungkinan tersaring oleh pencarian/filter yang aktif). Coba hapus pencarian atau filter kategori, lalu coba lagi.');
              return;
            }

            const modalSaatIni = link.closest('.modal');

            const bukaTarget = function () {
              bootstrap.Modal.getOrCreateInstance(targetEl).show();
            };

            if (modalSaatIni && modalSaatIni !== targetEl) {
              modalSaatIni.addEventListener('hidden.bs.modal', bukaTarget, { once: true });
              bootstrap.Modal.getOrCreateInstance(modalSaatIni).hide();
            } else {
              bukaTarget();
            }
          });
        </script>
      @endonce