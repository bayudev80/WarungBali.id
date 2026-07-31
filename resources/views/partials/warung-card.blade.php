@php
    $isFavorit = auth()->check() &&
        $item->favorit->where('id_user', auth()->user()->id_user)->count() > 0;
      @endphp

<div class="warung-card-item mb-4">

    <div class="card warung-card border-0 shadow">

        <div class="position-relative">

            <img src="{{ asset('images/warung/'.$item->foto) }}"
                class="card-img-top warung-image">

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

        <div class="card-body d-flex flex-column">

            <h4 class="fw-bold warung-title">
                {{ $item->nama_warung }}
            </h4>

            <p class="text-secondary warung-address">
                📍 {{ $item->alamat }}
            </p>

            <p class="warung-description">
                {{ $item->deskripsi }}
            </p>

            <div class="warung-catering-slot mb-2">
                @if($item->is_kuliner && $item->menerima_catering)
                    <span class="badge bg-success-subtle text-success align-self-start">
                        🍱 Menerima Catering
                    </span>
                @endif
            </div>

            <div class="mt-auto">

                <p class="text-warning fw-bold mb-2">
                    Rp{{ number_format($item->harga_min,0,',','.') }}
                    -
                    Rp{{ number_format($item->harga_max,0,',','.') }}
                </p>

                <p class="mb-3">
                    🕒 {{ substr($item->jam_buka,0,5) }}
                    -
                    {{ substr($item->jam_tutup,0,5) }}
                </p>

                <button
                    class="btn btn-warning text-white w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#detail{{ $item->id_warung }}">

                    Lihat Detail

                </button>

            </div>

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

                  <div id="review-summary-{{ $item->id_warung }}">
                    @if($item->review->count() > 0)
                    ⭐
                    <strong class="review-summary-avg">{{ number_format($item->review->avg('rating'), 1) }}</strong>
                    <span class="text-light review-summary-count">
                      ({{ $item->review->count() }} Ulasan)
                    </span>
                    @else
                    ⭐
                    <strong class="review-summary-avg">0.0</strong>
                    <span class="text-light review-summary-count">
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
                    @if($item->wa_link)
                      <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-white text-decoration-none">
                        <i class="fa-brands fa-whatsapp"></i> {{ $item->telepon }}
                      </a>
                    @else
                      📞 Telepon belum tersedia
                    @endif
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
                      <br>
                      <a href="{{ $item->maps_link }}" target="_blank" rel="noopener" class="text-warning fw-bold">
                        <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                      </a>
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

                      @if($item->wa_link)
                        <a href="{{ $item->wa_link }}" target="_blank" rel="noopener" class="text-success fw-bold">
                          <i class="fa-brands fa-whatsapp"></i> {{ $item->telepon }} (Chat WhatsApp)
                        </a>
                      @else
                        Belum tersedia
                      @endif

                    </p>

                    @if($item->is_cabang && $item->indukWarung)
                      <hr>
                      <p class="mb-0">
                        🏬
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
                        🏬
                        <strong>Cabang Warung Ini ({{ $item->cabang->count() }})</strong>
                      </p>

                      @foreach($item->cabang as $cabang)
                        <div class="d-flex justify-content-between align-items-center mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                          <div>
                            <strong>{{ $cabang->nama_warung }}</strong><br>
                            <span class="text-secondary small">📍 {{ $cabang->alamat }}</span>
                          </div>
                          <a href="#" class="btn btn-sm btn-warning text-white btn-lihat-cabang"
                             data-cabang-target="#detail{{ $cabang->id_warung }}">
                            Lihat Detail
                          </a>
                        </div>
                      @endforeach

                    </div>
                  @endif

                  <div class="mt-4">

                    @if($item->is_kuliner)
                      @if($item->menerima_catering)
                        <span class="badge bg-success p-2">
                          🍱 Menerima Layanan Catering
                        </span>
                      @else
                        <span class="badge bg-secondary p-2">
                          Tidak Menerima Layanan Catering
                        </span>
                      @endif
                    @endif

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