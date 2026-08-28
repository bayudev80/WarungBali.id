@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-gray-800 fw-bold">Kelola Kategori Warung</h2>
            <p class="text-muted small mb-0">Kelola master kategori warung dan setujui kategori baru yang diajukan oleh pemilik warung.</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Status Tabs --}}
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.kategori.index') }}" 
           class="btn btn-sm rounded-pill px-3 py-2 {{ empty($filterStatus) ? 'btn-dark text-white fw-bold shadow-sm' : 'btn-light border text-secondary bg-white' }}">
            Semua ({{ $totalKategori }})
        </a>
        <a href="{{ route('admin.kategori.index', ['status' => 'approved']) }}" 
           class="btn btn-sm rounded-pill px-3 py-2 {{ $filterStatus === 'approved' ? 'btn-success text-white fw-bold shadow-sm' : 'btn-light border text-secondary bg-white' }}">
            <i class="bi bi-check-circle me-1"></i> Disetujui ({{ $approvedCount }})
        </a>
        <a href="{{ route('admin.kategori.index', ['status' => 'pending']) }}" 
           class="btn btn-sm rounded-pill px-3 py-2 {{ $filterStatus === 'pending' ? 'btn-warning text-dark fw-bold shadow-sm' : 'btn-light border text-secondary bg-white' }}">
            <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
            @if($pendingCount > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Daftar Kategori Warung</h6>
            <span class="badge bg-light text-muted border px-2.5 py-1.5">{{ count($kategori) }} Kategori</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="70">No</th>
                            <th>Nama Kategori</th>
                            <th class="text-center" width="140">Jumlah Warung</th>
                            <th class="text-center" width="180">Status</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($kategori as $item)
                        <tr class="{{ $item->status === 'pending' ? 'table-warning bg-opacity-25' : '' }}">
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $item->nama_kategori }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Slug: <code>{{ $item->slug }}</code></div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-secondary border px-2.5 py-1">
                                    <i class="bi bi-shop me-1"></i> {{ $item->warung_count ?? 0 }} Warung
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2.5 py-1.5 rounded-pill shadow-xs">
                                        <i class="bi bi-clock-history me-1"></i> Diajukan Pemilik
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1.5">
                                    @if($item->status === 'pending')
                                        <form action="{{ route('admin.kategori.approve', $item->id_kategori) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-2.5 shadow-sm" title="Setujui Kategori Ini">
                                                <i class="bi bi-check-lg me-1"></i> Terima
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.kategori.edit', $item->id_kategori) }}" class="btn btn-sm btn-outline-warning rounded-circle shadow-xs" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Edit Kategori">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.kategori.destroy', $item->id_kategori) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-circle shadow-xs" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" title="Hapus Kategori">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                    <h6 class="fw-bold">Belum ada data kategori.</h6>
                                    <p class="small mb-0">Silakan tambahkan kategori baru atau ubah filter status di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection