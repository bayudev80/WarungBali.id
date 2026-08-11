@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ================= GREETING ================= --}}
<div class="dash-greeting mb-4">
    <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
        Selamat datang, {{ explode(' ', Auth::user()->nama)[0] }}! 👋
    </h2>
    <p class="text-muted mb-0">
        Siap mengelola <span class="fw-medium text-dark">WarungBali.id</span> hari ini? Ini ringkasan aktivitas per {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}.
    </p>
</div>

{{-- ================= ALERT VERIFIKASI ================= --}}
@if($jumlahWarungPending > 0)

    <a href="{{ route('admin.warung.verifikasi') }}" class="dash-alert mb-4">

        <div class="dash-alert-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>

        <div class="dash-alert-text">
            <strong>{{ $jumlahWarungPending }} warung</strong> sedang menunggu verifikasi.
            <span>Tinjau pendaftaran warung sebelum tampil ke publik.</span>
        </div>

        <div class="dash-alert-cta">
            Tinjau Sekarang <i class="bi bi-arrow-right"></i>
        </div>

    </a>

@endif

{{-- ================= STAT CARDS ================= --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#2e7d32;--accent-soft:rgba(46,125,50,0.12);">
            <div class="stat-card-icon"><i class="bi bi-shop"></i></div>
            <h6>Total Warung</h6>
            <h2>{{ $jumlahWarung }}</h2>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#2563eb;--accent-soft:rgba(37,99,235,0.12);">
            <div class="stat-card-icon"><i class="bi bi-people-fill"></i></div>
            <h6>Total Pengguna</h6>
            <h2>{{ $jumlahUser }}</h2>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#f4b400;--accent-soft:rgba(244,180,0,0.15);">
            <div class="stat-card-icon"><i class="bi bi-star-fill"></i></div>
            <h6>Total Review</h6>
            <h2>{{ $jumlahReview }}</h2>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#7c3aed;--accent-soft:rgba(124,58,237,0.12);">
            <div class="stat-card-icon"><i class="bi bi-grid-fill"></i></div>
            <h6>Total Kategori</h6>
            <h2>{{ $jumlahKategori }}</h2>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#db2777;--accent-soft:rgba(219,39,119,0.12);">
            <div class="stat-card-icon"><i class="bi bi-heart-fill"></i></div>
            <h6>Total Favorit</h6>
            <h2>{{ $jumlahFavorit }}</h2>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <div class="dashboard-card h-100" style="--accent:#0d9488;--accent-soft:rgba(13,148,136,0.12);">
            <div class="stat-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <h6>Pengunjung Bulan Ini</h6>
            <h2>{{ $jumlahPengunjungBulanIni }}</h2>
        </div>
    </div>

</div>

{{-- ================= CHART & RINGKASAN ================= --}}
<div class="row g-3 mb-4">

    <div class="col-lg-5">
        <div class="content-box h-100 chart-card">

            <div class="section-head">
                <h5>Distribusi Kategori</h5>
            </div>

            @if($kategoriChart->sum('warung_count') > 0)
                <canvas id="chartKategori"></canvas>
            @else
                <div class="activity-empty">Belum ada data warung.</div>
            @endif

        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-box h-100">

            <div class="section-head">
                <h5>Ringkasan</h5>
            </div>

            <p class="text-muted small fw-semibold mb-2">Status Warung</p>

            <div class="progress-row">
                <div class="progress-row-head">
                    <span>Disetujui</span>
                    <span>{{ $jumlahWarungApproved }} warung</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: {{ $jumlahWarung ? round($jumlahWarungApproved / $jumlahWarung * 100) : 0 }}%"></div>
                </div>
            </div>

            <div class="progress-row">
                <div class="progress-row-head">
                    <span>Menunggu</span>
                    <span>{{ $jumlahWarungPending }} warung</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: {{ $jumlahWarung ? round($jumlahWarungPending / $jumlahWarung * 100) : 0 }}%"></div>
                </div>
            </div>

            <div class="progress-row mb-4">
                <div class="progress-row-head">
                    <span>Ditolak</span>
                    <span>{{ $jumlahWarungRejected }} warung</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: {{ $jumlahWarung ? round($jumlahWarungRejected / $jumlahWarung * 100) : 0 }}%"></div>
                </div>
            </div>

            <p class="text-muted small fw-semibold mb-2">Peran Pengguna</p>

            <div class="progress-row">
                <div class="progress-row-head">
                    <span>Pemilik Warung</span>
                    <span>{{ $jumlahPemilik }} orang</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $jumlahUser ? round($jumlahPemilik / $jumlahUser * 100) : 0 }}%;background:#2563eb;"></div>
                </div>
            </div>

            <div class="progress-row">
                <div class="progress-row-head">
                    <span>Admin</span>
                    <span>{{ $jumlahAdmin }} orang</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $jumlahUser ? round($jumlahAdmin / $jumlahUser * 100) : 0 }}%;background:#7c3aed;"></div>
                </div>
            </div>

            <div class="progress-row">
                <div class="progress-row-head">
                    <span>Pengguna Umum</span>
                    <span>{{ $jumlahUmum }} orang</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $jumlahUser ? round($jumlahUmum / $jumlahUser * 100) : 0 }}%;background:#0d9488;"></div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ================= AKTIVITAS TERBARU ================= --}}
<div class="row g-3 mb-4">

    <div class="col-lg-6">
        <div class="content-box h-100">

            <div class="section-head">
                <h5>Warung Terbaru</h5>
                <a href="{{ route('admin.warung.index') }}">Lihat semua</a>
            </div>

            <div class="activity-list">

                @forelse($warungTerbaru as $item)

                    @php
                        $statusBadge = [
                            'pending'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                            'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                        ][$item->status] ?? ['label' => $item->status, 'class' => 'bg-secondary'];
                    @endphp

                    <a href="{{ route('admin.warung.edit', $item->id_warung) }}" class="activity-item">

                        @if($item->foto && file_exists(public_path('images/warung/'.$item->foto)))
                            <img src="{{ asset('images/warung/'.$item->foto) }}" class="activity-thumb">
                        @else
                            <div class="activity-thumb d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif

                        <div class="activity-body">
                            <div class="activity-title">{{ $item->nama_warung }}</div>
                            <div class="activity-sub">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                                &middot; {{ $item->kabupaten->nama_kabupaten ?? '-' }}
                            </div>
                        </div>

                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>

                    </a>

                @empty

                    <div class="activity-empty">Belum ada warung terdaftar.</div>

                @endforelse

            </div>

        </div>
    </div>

    <div class="col-lg-6">
        <div class="content-box h-100">

            <div class="section-head">
                <h5>Review Terbaru</h5>
                <a href="{{ route('admin.review.index') }}">Lihat semua</a>
            </div>

            <div class="activity-list">

                @forelse($reviewTerbaru as $item)

                    <div class="activity-item">

                        <div class="activity-avatar">
                            {{ strtoupper(substr($item->user->nama ?? '?', 0, 1)) }}
                        </div>

                        <div class="activity-body">
                            <div class="activity-title">
                                {{ $item->user->nama ?? 'Pengguna' }}
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $item->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size:11px;"></i>
                                    @endfor
                                </span>
                            </div>
                            <div class="activity-sub">
                                {{ $item->warung->nama_warung ?? 'Warung' }} &middot; {{ $item->komentar }}
                            </div>
                        </div>

                    </div>

                @empty

                    <div class="activity-empty">Belum ada review masuk.</div>

                @endforelse

            </div>

        </div>
    </div>

</div>

{{-- ================= QUICK ACTIONS ================= --}}
<div class="section-head">
    <h5>Aksi Cepat</h5>
</div>

<div class="quick-actions mb-2">

    <a href="{{ route('admin.warung.verifikasi') }}" class="quick-action" style="--accent:#f4b400;">
        <i class="bi bi-patch-check-fill"></i>
        <span>Verifikasi Warung</span>
    </a>

    <a href="{{ route('admin.kategori.index') }}" class="quick-action" style="--accent:#7c3aed;">
        <i class="bi bi-grid-fill"></i>
        <span>Kelola Kategori</span>
    </a>

    <a href="{{ route('admin.user.index') }}" class="quick-action" style="--accent:#2563eb;">
        <i class="bi bi-people-fill"></i>
        <span>Kelola Pengguna</span>
    </a>

    <a href="{{ route('admin.review.index') }}" class="quick-action" style="--accent:#2e7d32;">
        <i class="bi bi-star-fill"></i>
        <span>Kelola Review</span>
    </a>

</div>

@endsection

@push('scripts')

    @if($kategoriChart->sum('warung_count') > 0)

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

        <script>
            const ctxKategori = document.getElementById('chartKategori');

            new Chart(ctxKategori, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kategoriChart->pluck('nama_kategori')) !!},
                    datasets: [{
                        data: {!! json_encode($kategoriChart->pluck('warung_count')) !!},
                        backgroundColor: [
                            '#2e7d32', '#2563eb', '#f4b400', '#7c3aed',
                            '#db2777', '#0d9488', '#ea580c', '#64748b'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 14, font: { size: 12.5 } }
                        }
                    },
                    cutout: '65%',
                }
            });
        </script>

    @endif

@endpush