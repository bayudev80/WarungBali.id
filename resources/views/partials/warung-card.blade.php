@php
    $avgRating   = round($item->review->avg('rating') ?? 0, 1);
    $totalReview = $item->review->count();
    $isFavorit   = auth()->check() &&
        $item->favorit->where('id_user', auth()->user()->id_user)->count() > 0;
    // Ambil 2 menu unggulan (nama saja)
    $menuUnggulan = $item->menu_tampil->take(2)->pluck('nama_menu');
@endphp

<div class="warung-card-item mb-4 d-flex flex-column">

    <div class="card warung-card border-0 shadow flex-fill w-100">

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
      <div class="modal fade" id="detail{{ $item->id_warung }}" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

          <div class="modal-content border-0 rounded-4 overflow-hidden">

            <!-- HEADER IMAGE -->
            <div class="position-relative">

              <img src="{{ asset('images/warung/'.$item->foto) }}" class="w-100" style="height:200px;object-fit:cover;">

              <button type="button" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-2"
                data-bs-dismiss="modal" style="width:38px;height:38px;font-size:0.8rem;">
                ✕
              </button>

              <div class="position-absolute bottom-0 start-0 p-3 text-white w-100"
                style="background:linear-gradient(transparent,rgba(0,0,0,.75));">

                <span class="badge bg-warning text-dark px-2 py-1 mb-2" style="font-size:0.75rem;">
                  Kuliner Bali
                </span>

                <h5 class="fw-bold mb-2">
                  {{ $item->nama_warung }}
                </h5>

                <div class="d-flex flex-wrap gap-4">

                  <div id="review-summary-{{ $item->id_warung }}">
                    @if($item->review->count() > 0)
                    <i class="bi bi-star-fill text-warning me-1"></i>
                    <strong class="review-summary-avg">{{ number_format($item->review->avg('rating'), 1) }}</strong>
                    <span class="text-light review-summary-count">
                      ({{ $item->review->count() }} Ulasan)
                    </span>
                    @else
                    <i class="bi bi-star text-warning me-1"></i>
                    <strong class="review-summary-avg">0.0</strong>
                    <span class="text-light review-summary-count">
                      (Belum ada ulasan)
                    </span>
                    @endif
                  </div>

                  <div>
                    <i class="bi bi-clock me-1 text-light"></i>
                    {{ substr($item->jam_buka,0,5) }}
                    -
                    {{ substr($item->jam_tutup,0,5) }}
                  </div>

                  <div>
                    @if($item->wa_link)
                      <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-white text-decoration-none">
                        <i class="fa-brands fa-whatsapp"></i> {{ $item->telepon }}
                      </a>
                    @else
                      <i class="bi bi-telephone-fill me-1 text-light"></i> Telepon belum tersedia
                    @endif
                  </div>

                </div>

              </div>

            </div>

            <!-- BODY -->
            <div class="modal-body p-3">

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

                  <p class="text-secondary" style="font-size:0.9rem;">

                    {{ $item->deskripsi }}

                  </p>

                  <div class="rounded-3 p-3 mt-3" style="background:#FFF8EC;">

                    <p class="mb-3">
                      <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                      {{ $item->alamat }}
                      <br>
                      <a href="{{ $item->maps_link }}" target="_blank" rel="noopener" class="text-warning fw-bold">
                        <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                      </a>
                    </p>

                    <p class="mb-3">
                      <i class="bi bi-clock me-1 text-muted"></i>
                      {{ substr($item->jam_buka,0,5) }} - {{ substr($item->jam_tutup,0,5) }}
                    </p>

                    <p class="mb-0">
                      @if($item->wa_link)
                        <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-success fw-bold">
                          <i class="fa-brands fa-whatsapp"></i> {{ $item->telepon }}
                        </a>
                      @else
                        <i class="bi bi-telephone-fill me-1 text-muted"></i> Belum tersedia
                      @endif
                    </p>

                    @if($item->is_cabang && $item->indukWarung)
                      <hr>
                      <p class="mb-0">
                        <i class="bi bi-shop me-1 text-warning"></i>
                        <strong>Cabang dari</strong><br>
                        Warung ini adalah cabang dari
                        <a href="#" class="fw-bold text-warning btn-lihat-cabang"
                           data-cabang-target="#detail{{ $item->indukWarung->id_warung }}">
                          {{ $item->indukWarung->nama_warung }}
                        </a>,
                        dengan menu yang sama seperti warung utamanya.
                      </p>
                    @endif

                  </div>

                  @if(!$item->is_cabang && $item->cabang->count() > 0)
                    <div class="rounded-4 p-4 mt-4" style="background:#FFF8EC;">

                      <p class="mb-3">
                        <i class="bi bi-shop me-1 text-warning"></i>
                        <strong>Cabang Warung Ini ({{ $item->cabang->count() }})</strong>
                      </p>

                      @foreach($item->cabang as $cabang)
                        <div class="d-flex justify-content-between align-items-center mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                          <div>
                            <strong>{{ $cabang->nama_warung }}</strong><br>
                            <span class="text-secondary small"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $cabang->alamat }}</span>
                          </div>
                          <a href="#" class="btn btn-sm btn-warning text-white btn-lihat-cabang"
                             data-cabang-target="#detail{{ $cabang->id_warung }}">
                            Lihat Detail
                          </a>
                        </div>
                      @endforeach

                    </div>

                    {{-- Modal detail untuk setiap cabang -- wajib ada di DOM
                         agar tombol "Lihat Detail" bisa membukanya meski
                         cabang tidak tampil sebagai card terpisah di halaman. --}}
                    @foreach($item->cabang as $cabang)
                      <div class="modal fade" id="detail{{ $cabang->id_warung }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                          <div class="modal-content border-0 rounded-4 overflow-hidden">

                            <div class="position-relative">
                              <img src="{{ asset('images/warung/'.$cabang->foto) }}" class="w-100" style="height:200px;object-fit:cover;">
                              <button type="button" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-2"
                                data-bs-dismiss="modal" style="width:38px;height:38px;font-size:0.8rem;">✕</button>
                              <div class="position-absolute bottom-0 start-0 p-3 text-white w-100"
                                style="background:linear-gradient(transparent,rgba(0,0,0,.75));">
                                <h5 class="fw-bold mb-2">{{ $cabang->nama_warung }}</h5>
                                <div class="d-flex flex-wrap gap-4">
                                  <div>
                                    @if($cabang->review->count() > 0)
                                      <i class="bi bi-star-fill text-warning me-1"></i>
                                      <strong>{{ number_format($cabang->review->avg('rating'), 1) }}</strong>
                                      <span class="text-light">({{ $cabang->review->count() }} Ulasan)</span>
                                    @else
                                      <i class="bi bi-star text-warning me-1"></i>
                                      <strong>0.0</strong>
                                      <span class="text-light">(Belum ada ulasan)</span>
                                    @endif
                                  </div>
                                  <div>
                                    <i class="bi bi-clock me-1 text-light"></i>
                                    {{ substr($cabang->jam_buka,0,5) }} - {{ substr($cabang->jam_tutup,0,5) }}
                                  </div>
                                  <div>
                                    @if($cabang->wa_link)
                                      <a href="{{ $cabang->wa_link }}" target="_blank" rel="noopener" class="text-white text-decoration-none">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $cabang->telepon }}
                                      </a>
                                    @else
                                      <i class="bi bi-telephone-fill me-1 text-light"></i> Belum tersedia
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="modal-body p-3">
                              <ul class="nav nav-tabs mb-4">
                                <li class="nav-item">
                                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info{{ $cabang->id_warung }}">Informasi</button>
                                </li>
                                <li class="nav-item">
                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#menu{{ $cabang->id_warung }}">Menu</button>
                                </li>
                                <li class="nav-item">
                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#review{{ $cabang->id_warung }}">Ulasan</button>
                                </li>
                              </ul>

                              <div class="tab-content">

                                <div class="tab-pane fade show active" id="info{{ $cabang->id_warung }}">
                                  <p class="text-secondary" style="font-size:0.9rem;">{{ $cabang->deskripsi }}</p>
                                  <div class="rounded-3 p-3 mt-3" style="background:#FFF8EC;">
                                    <p class="mb-3">
                                      <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                      {{ $cabang->alamat }}<br>
                                      <a href="{{ $cabang->maps_link }}" target="_blank" rel="noopener" class="text-warning fw-bold">
                                        <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                                      </a>
                                    </p>
                                    <p class="mb-3">
                                      <i class="bi bi-clock me-1 text-muted"></i>
                                      {{ substr($cabang->jam_buka,0,5) }} - {{ substr($cabang->jam_tutup,0,5) }}
                                    </p>
                                    <p class="mb-3">
                                      @if($cabang->wa_link)
                                        <a href="{{ $cabang->wa_link }}" target="_blank" rel="noopener" class="text-success fw-bold">
                                          <i class="fa-brands fa-whatsapp"></i> {{ $cabang->telepon }}
                                        </a>
                                      @else
                                        <i class="bi bi-telephone-fill me-1 text-muted"></i> Belum tersedia
                                      @endif
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                      <i class="bi bi-shop me-1 text-warning"></i>
                                      <strong>Cabang dari</strong><br>
                                      Warung ini adalah cabang dari
                                      <a href="#" class="fw-bold text-warning btn-lihat-cabang"
                                         data-cabang-target="#detail{{ $item->id_warung }}">
                                        {{ $item->nama_warung }}
                                      </a>.
                                    </p>
                                  </div>
                                </div>

                                <div class="tab-pane fade" id="menu{{ $cabang->id_warung }}">
                                  <div class="row g-4 mt-3">
                                    @forelse($cabang->menu_tampil as $menu)
                                    <div class="col-md-6">
                                      <div class="card border-0 shadow-sm rounded-4 h-100">
                                        <img src="{{ asset('images/menu/'.$menu->foto_menu) }}" class="card-img-top"
                                          style="height:180px;object-fit:cover;" alt="{{ $menu->nama_menu }}">
                                        <div class="card-body">
                                          <h5 class="fw-bold">{{ $menu->nama_menu }}</h5>
                                          <p class="text-secondary">{{ $menu->deskripsi }}</p>
                                          <h5 class="text-warning fw-bold">Rp{{ number_format($menu->harga,0,',','.') }}</h5>
                                        </div>
                                      </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                      <div class="alert alert-warning text-center rounded-4">Belum ada menu.</div>
                                    </div>
                                    @endforelse
                                  </div>
                                </div>

                                <div class="tab-pane fade" id="review{{ $cabang->id_warung }}">
                                  <div class="mt-3">
                                    @forelse($cabang->review as $rev)
                                    <div class="border rounded-4 p-4 mb-3">
                                      <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="bi bi-person-circle text-secondary me-1"></i>{{ $rev->user->nama }}</strong>
                                        <small class="text-secondary">{{ date('d M Y', strtotime($rev->created_at)) }}</small>
                                      </div>
                                      <div class="mb-2">
                                        @for($i=1;$i<=5;$i++)
                                          <span class="{{ $i<=$rev->rating ? 'text-warning' : 'text-secondary' }} fs-5">{{ $i<=$rev->rating ? '★' : '☆' }}</span>
                                        @endfor
                                      </div>
                                      <p class="mb-0">{{ $rev->komentar }}</p>
                                    </div>
                                    @empty
                                    <div class="alert alert-warning rounded-4">Belum ada ulasan untuk cabang ini.</div>
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


                  <div class="mt-4">
                    @if($item->menerima_catering)
                      <span class="detail-layanan-badge detail-layanan-badge--aktif">
                        <i class="{{ $item->layanan_icon }} me-1"></i>{{ $item->layanan_label }}
                      </span>
                    @else
                      <span class="detail-layanan-badge detail-layanan-badge--nonaktif">
                        <i class="{{ $item->layanan_icon }} me-1"></i>Tidak {{ $item->layanan_label }}
                      </span>
                    @endif
                  </div>

                  <h6 class="mt-4 fw-bold">
                    Kisaran Harga
                  </h6>

                  <h5 class="text-warning fw-bold">

                    Rp{{ number_format($item->harga_min,0,',','.') }}

                    -

                    Rp{{ number_format($item->harga_max,0,',','.') }}

                  </h5>

                  <div class="mt-4">

                    @if($item->is_legendaris)
                      <span class="badge bg-warning-subtle text-dark me-2 p-2">
                        Legendaris
                      </span>
                    @endif

                    @if($item->is_kuliner)
                      <span class="badge bg-warning-subtle text-dark me-2 p-2">
                        Kuliner Bali
                      </span>
                    @endif

                    @if($item->is_favorit_wisatawan)
                      <span class="badge bg-warning-subtle text-dark me-2 p-2">
                        Favorit Wisatawan
                      </span>
                    @endif

                  </div>

                </div>
                
                <!-- MENU -->
                <div class="tab-pane fade" id="menu{{ $item->id_warung }}">

                  <div class="row g-4 mt-3">

                    @forelse($item->menu_tampil as $menu)

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

                    <!-- FORM ULASAN -->
                    <div class="border rounded-4 p-4 mb-4" style="background:#FFF8EC;">

                      <h6 class="fw-bold mb-3">Bagikan Pengalaman Anda</h6>

                      @auth
                        @php
                          $ulasanSaya = $item->review->firstWhere('id_user', auth()->user()->id_user);
                        @endphp

                        <form class="review-form" data-warung-id="{{ $item->id_warung }}">
                          @csrf

                          <div class="mb-3">
                            <label class="form-label d-block">Rating</label>

                            <div class="star-rating" data-target="rating-input-{{ $item->id_warung }}">
                              @for ($i = 1; $i <= 5; $i++)
                                <span class="star" data-value="{{ $i }}" style="font-size:1.7rem;cursor:pointer;color:#ccc;">★</span>
                              @endfor
                            </div>

                            <input type="hidden" name="rating" id="rating-input-{{ $item->id_warung }}" value="{{ $ulasanSaya->rating ?? 0 }}">
                          </div>

                          <div class="mb-3">
                            <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis ulasan Anda tentang warung ini...">{{ $ulasanSaya->komentar ?? '' }}</textarea>
                          </div>

                          <button type="submit" class="btn btn-warning text-white">
                            {{ $ulasanSaya ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
                          </button>

                          <div class="review-form-message small mt-2"></div>

                        </form>
                      @else
                        <p class="text-secondary mb-3">
                          Anda perlu masuk (login) terlebih dahulu untuk memberi ulasan warung ini.
                        </p>

                        <a href="{{ route('login') }}" class="btn btn-warning text-white">
                          Login untuk Memberi Ulasan
                        </a>
                      @endauth

                    </div>
                    <!-- /form ulasan -->

                    <div id="review-list-{{ $item->id_warung }}">

                    @forelse($item->review as $review)

                    <div class="border rounded-4 p-4 mb-3" data-review-user="{{ $review->id_user }}">

                      <div class="d-flex justify-content-between align-items-center mb-2">

                        <strong>
                          <i class="bi bi-person-circle text-secondary me-1"></i>{{ $review->user->nama }}
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

                    <div class="alert alert-warning rounded-4 review-empty-state">

                      Belum ada ulasan untuk warung ini.

                    </div>

                    @endforelse

                    </div>
                    <!-- /review-list -->

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