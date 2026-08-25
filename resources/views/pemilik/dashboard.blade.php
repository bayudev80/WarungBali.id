@extends('pemilik.layouts.app')

@section('title', 'Dashboard')

@section('content')

<style>
    .row.g-3 { align-items: stretch !important; }
    .row.g-3 > .col-md-4 { display: flex !important; }

    .stat-card {
        width: 100% !important;
        min-height: 92px !important;
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 16px !important;
        padding: 18px 16px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 6px !important;
        transition: transform .25s cubic-bezier(.4,0,.2,1), box-shadow .25s cubic-bezier(.4,0,.2,1);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,.07) !important;
    }

    .stat-label {
        order: 1 !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .6px !important;
        color: #64748b !important;
    }

    .stat-value {
        order: 2 !important;
        font-size: 1.05rem !important;
        font-weight: 800 !important;
        color: #d97706 !important;
        line-height: 1.3 !important;
        max-width: 100% !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>

<div class="content-box">

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-x-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @php
        $statusMap = [
            'pending'  => ['label' => 'Menunggu Persetujuan', 'badge' => 'bg-warning text-dark', 'dot' => '#f59e0b'],
            'approved' => ['label' => 'Disetujui & Tayang',   'badge' => 'bg-success',            'dot' => '#059669'],
            'rejected' => ['label' => 'Ditolak',              'badge' => 'bg-danger',             'dot' => '#dc2626'],
        ];
        $st = $statusMap[$warung->status];
    @endphp

    {{-- ── Page Header ── --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-2">{{ $warung->nama_warung }}</h3>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge {{ $st['badge'] }} rounded-pill">
                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:{{ $st['dot'] }};opacity:.7;margin-right:5px;"></span>
                    {!! $st['label'] !!}
                </span>
                @if($warung->is_cabang && $warung->indukWarung)
                    <span class="badge bg-secondary rounded-pill">Cabang dari {{ $warung->indukWarung->nama_warung }}</span>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pemilik.warung.edit') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-fill"></i> Edit Warung
            </a>
            @if(!$warung->is_cabang)
                <a href="{{ route('pemilik.menu.index') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-menu-button-wide-fill"></i> Kelola {{ $warung->label_menu }}
                </a>
            @endif
        </div>
    </div>

    {{-- ── Alerts ── --}}
    @if($warung->is_cabang && $warung->indukWarung)
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i>
            <span>
                Warung ini terdaftar sebagai cabang dari <strong>{{ $warung->indukWarung->nama_warung }}</strong>.
                {{ $warung->label_menu }} yang tampil ke pengunjung otomatis mengikuti {{ strtolower($warung->label_menu) }} milik warung utama.
            </span>
        </div>
    @endif

    @if($warung->status === 'pending')
        <div class="alert alert-warning no-auto-dismiss d-flex align-items-center gap-2 rounded-4 p-3 mb-4 shadow-sm border-0" style="background:#FFFBEB; border-left: 4px solid #F59E0B !important;">
            <i class="bi bi-hourglass-split text-warning fs-5 flex-shrink-0"></i>
            <span class="text-dark small">Warung Anda sedang ditinjau oleh admin. Warung akan otomatis tayang di halaman utama setelah disetujui.</span>
        </div>
    @elseif($warung->status === 'rejected')
        <div class="alert alert-danger no-auto-dismiss rounded-4 p-4 mb-4 shadow-sm border-0" style="background:#FEF2F2; border-left: 4px solid #EF4444 !important;">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-octagon-fill text-danger fs-3 flex-shrink-0 mt-1"></i>
                <div class="flex-grow-1">
                    <h5 class="fw-bold text-danger mb-1">Pengajuan Warung Belum Disetujui</h5>
                    <p class="text-dark small mb-2">
                        Mohon maaf, pengajuan pendaftaran warung Anda belum disetujui dengan catatan/alasan dari admin berikut:
                    </p>
                    <div class="p-3 bg-white rounded-3 border border-danger-subtle text-danger fw-semibold small mb-3">
                        <i class="bi bi-chat-left-quote-fill me-2"></i> "{{ $warung->alasan_penolakan ?: 'Data atau foto warung belum memenuhi syarat kelengkapan.' }}"
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <a href="{{ route('pemilik.warung.edit') }}" class="btn btn-danger btn-sm rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-pencil-square"></i> Perbaiki & Ajukan Ulang Data Warung
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">{{ $warung->label_menu }} Terdaftar</div>
                <div class="stat-value">{{ $warung->menu_tampil->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Kategori</div>
                <div class="stat-value" title="{{ $warung->kategori->nama_kategori ?? '-' }}">
                    {{ $warung->kategori->nama_kategori ?? '-' }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-label">Kabupaten</div>
                <div class="stat-value" title="{{ $warung->kabupaten->nama_kabupaten ?? '-' }}">
                    {{ $warung->kabupaten->nama_kabupaten ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── Info Warung ── --}}
    <h5 class="fw-bold mb-3">
        <i class="bi bi-shop me-2" style="color:var(--pk-primary,#d97706)"></i>Info Warung
    </h5>

    <table class="table table-borderless">
        <tr>
            <td class="text-muted">Alamat</td>
            <td>{{ $warung->alamat }}</td>
        </tr>
        <tr>
            <td class="text-muted">Telepon</td>
            <td>{{ $warung->telepon ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Jam Buka</td>
            <td>{{ $warung->jam_buka ?? '-' }} – {{ $warung->jam_tutup ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Deskripsi</td>
            <td>{{ $warung->deskripsi ?? '-' }}</td>
        </tr>
    </table>

    {{-- ── Cabang Warung ── --}}
    @if(!$warung->is_cabang)
        @php
            $cabangBadge = [
                'pending'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                'approved' => ['label' => 'Tayang',   'class' => 'bg-success'],
                'rejected' => ['label' => 'Ditolak',  'class' => 'bg-danger'],
            ];
        @endphp

        <div class="d-flex justify-content-between align-items-center mt-5 mb-3 pt-3 border-top flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-diagram-2 me-2" style="color:var(--pk-primary,#d97706)"></i>Cabang Warung
                </h5>
                <p class="text-muted small mb-0 mt-1">Kelola cabang dari warung utama Anda.</p>
            </div>
            <a href="{{ route('pemilik.warung.cabang.create') }}" class="btn btn-primary btn-sm rounded-pill">
                <i class="bi bi-plus-lg"></i> Tambah Cabang
            </a>
        </div>

        @if($warung->semuaCabang->isEmpty())
            <div style="background:#fafbfc;border:1px dashed #e2e8f0;border-radius:10px;padding:24px;text-align:center;color:#94a3b8;font-size:13.5px;">
                <i class="bi bi-diagram-2" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                Belum ada cabang. Klik <strong>Tambah Cabang</strong> untuk membuka lokasi baru.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Cabang</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warung->semuaCabang as $cabang)
                            <tr>
                                <td class="fw-semibold">{{ $cabang->nama_warung }}</td>
                                <td class="text-muted">{{ $cabang->alamat }}</td>
                                <td>
                                    <span class="badge {{ $cabangBadge[$cabang->status]['class'] }} rounded-pill">
                                        {{ $cabangBadge[$cabang->status]['label'] }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pemilik.warung.cabang.edit', $cabang->id_warung) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <form action="{{ route('pemilik.warung.cabang.destroy', $cabang->id_warung) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus cabang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

</div>

@endsection