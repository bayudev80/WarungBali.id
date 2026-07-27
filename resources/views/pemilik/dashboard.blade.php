@extends('pemilik.layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="content-box">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
        </div>

        <div>
            <a href="{{ route('pemilik.warung.edit') }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Edit Warung
            </a>
            <a href="{{ route('pemilik.menu.index') }}" class="btn btn-info btn-sm">
                <i class="bi bi-menu-button-wide"></i> Kelola {{ $warung->label_menu }}
            </a>
        </div>

    </div>

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
                <div class="fs-4 fw-bold">{{ $warung->menu->count() }}</div>
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

</div>

@endsection
