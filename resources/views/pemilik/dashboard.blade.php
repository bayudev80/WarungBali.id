@extends('pemilik.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="content-box">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $statusBadge = [
            'pending'  => ['label' => 'Menunggu Persetujuan', 'class' => 'bg-warning text-dark'],
            'approved' => ['label' => 'Disetujui &amp; Tayang', 'class' => 'bg-success'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
        ][$warung->status];
    @endphp

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold mb-1">{{ $warung->nama_warung }}</h3>
            <span class="badge {{ $statusBadge['class'] }}">{!! $statusBadge['label'] !!}</span>
            @if($warung->is_cabang && $warung->indukWarung)
                <span class="badge bg-secondary">Cabang dari {{ $warung->indukWarung->nama_warung }}</span>
            @endif
        </div>

        <div>
            <a href="{{ route('pemilik.warung.edit') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit Warung
            </a>
            @if(!$warung->is_cabang)
                <a href="{{ route('pemilik.menu.index') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-menu-button-wide"></i> Kelola {{ $warung->label_menu }}
                </a>
            @endif
        </div>

    </div>

    @if($warung->is_cabang && $warung->indukWarung)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Warung ini terdaftar sebagai cabang dari <strong>{{ $warung->indukWarung->nama_warung }}</strong>.
            {{ $warung->label_menu }} yang tampil ke pengunjung otomatis mengikuti {{ strtolower($warung->label_menu) }} milik warung utama tersebut,
            jadi tidak perlu dikelola terpisah di sini.
        </div>
    @endif

    @if($warung->status === 'pending')
        <div class="alert alert-warning">
            <i class="bi bi-hourglass-split"></i>
            Warung Anda sedang ditinjau oleh admin. Warung belum tampil di halaman utama sampai disetujui.
        </div>
    @elseif($warung->status === 'rejected')
        <div class="alert alert-danger">
            <i class="bi bi-x-circle"></i>
            Pengajuan warung Anda ditolak admin. Silakan periksa kembali data warung Anda, lalu simpan ulang untuk diajukan kembali.
        </div>
    @endif

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center">
                <div class="fs-4 fw-bold">{{ $warung->menu_tampil->count() }}</div>
                <div class="text-muted small">{{ $warung->label_menu }} Terdaftar</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center">
                <div class="fs-4 fw-bold">{{ $warung->kategori->nama_kategori ?? '-' }}</div>
                <div class="text-muted small">Kategori</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="border rounded-3 p-3 text-center">
                <div class="fs-4 fw-bold">{{ $warung->kabupaten->nama_kabupaten ?? '-' }}</div>
                <div class="text-muted small">Kabupaten</div>
            </div>
        </div>

    </div>

    <h5 class="fw-bold mb-3">Info Warung</h5>

    <table class="table table-borderless">
        <tr>
            <td class="text-muted" style="width:180px;">Alamat</td>
            <td>{{ $warung->alamat }}</td>
        </tr>
        <tr>
            <td class="text-muted">Telepon</td>
            <td>{{ $warung->telepon ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Jam Buka</td>
            <td>{{ $warung->jam_buka ?? '-' }} - {{ $warung->jam_tutup ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-muted">Deskripsi</td>
            <td>{{ $warung->deskripsi ?? '-' }}</td>
        </tr>
    </table>

    @if(!$warung->is_cabang)
        @php
            $cabangBadge = [
                'pending'  => ['label' => 'Menunggu Persetujuan', 'class' => 'bg-warning text-dark'],
                'approved' => ['label' => 'Tayang', 'class' => 'bg-success'],
                'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
            ];
        @endphp

        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h5 class="fw-bold mb-0">Cabang Warung</h5>
            <a href="{{ route('pemilik.warung.cabang.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Cabang Baru
            </a>
        </div>

        @if($warung->semuaCabang->isEmpty())
            <p class="text-muted small">Warung Anda belum punya cabang. Klik "Tambah Cabang Baru" kalau ingin membuka lokasi baru.</p>
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
                                <td>{{ $cabang->nama_warung }}</td>
                                <td>{{ $cabang->alamat }}</td>
                                <td>
                                    <span class="badge {{ $cabangBadge[$cabang->status]['class'] }}">
                                        {{ $cabangBadge[$cabang->status]['label'] }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pemilik.warung.cabang.edit', $cabang->id_warung) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('pemilik.warung.cabang.destroy', $cabang->id_warung) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus cabang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
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
