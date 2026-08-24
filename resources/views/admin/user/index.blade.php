@extends('admin.layouts.app')

@section('title', 'Pengguna')

@section('content')

<style>
    .user-table {
        table-layout: fixed;
        width: 100%;
        font-size: 0.85rem;
    }
    .user-table th {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f8f9fa;
    }
    .user-table th, .user-table td {
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
        border-color: #f1f3f5;
    }
    .user-table tbody tr {
        transition: all 0.2s ease;
    }
    .user-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    .action-buttons {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .action-buttons .btn {
        border-radius: 8px;
        padding: 0.35rem 0.5rem;
        font-size: 0.85rem;
        transition: all 0.2s;
        border: none;
    }
    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
</style>

<div class="content-box px-2">

    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Data Pengguna</h3>
            <p class="text-muted small mb-0 mt-1">Kelola akun admin, pemilik, dan pengguna.</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card modern-card">
        <div class="table-responsive">
            <table class="table table-borderless user-table mb-0">
                <colgroup>
                    <col style="width: 60px;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                    <col style="width: 15%;">
                    <col style="width: 18%;">
                    <col style="width: 110px;">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th>Status Akun</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($user as $item)
                    <tr>
                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                        <td class="fw-medium text-dark">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">
                                    {{ strtoupper(substr($item->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $item->nama }}</div>
                                    @if(!empty($item->google_id))
                                        <small class="text-muted"><i class="bi bi-google text-danger"></i> Google</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $item->email }}</td>
                        <td>
                            @if($item->role === 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Admin</span>
                            @elseif($item->role === 'pemilik')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill">Pemilik</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">User</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_akun === 'pending')
                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-50 px-2 py-1 rounded-pill">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Terverifikasi
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons justify-content-center">
                                <a href="{{ route('admin.user.edit', $item->id_user) }}" class="btn btn-warning text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.user.destroy', $item->id_user) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-white" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted opacity-50 d-block mb-3"></i>
                            <h6 class="text-secondary fw-semibold">Belum ada data pengguna.</h6>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection