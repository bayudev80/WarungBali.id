@extends('admin.layouts.app')

@section('title', 'Verifikasi Akun Pemilik - Kelola Verifikasi')

@section('content')

{{-- Tab Switcher Verifikasi --}}
<div class="verification-tabs-nav mb-4">
    <a href="{{ route('admin.pemilik-akun.index') }}" class="verification-tab-item active">
        <i class="bi bi-person-badge tab-icon"></i>
        <span>Verifikasi Akun Pemilik</span>
        @if(isset($pendingAkunCount) && $pendingAkunCount > 0)
            <span class="tab-badge">{{ $pendingAkunCount }}</span>
        @endif
    </a>

    <div class="verification-tab-divider d-none d-sm-block"></div>

    <a href="{{ route('admin.warung.verifikasi') }}" class="verification-tab-item">
        <i class="bi bi-shop-window tab-icon"></i>
        <span>Verifikasi Warung</span>
        @if(isset($pendingWarungCount) && $pendingWarungCount > 0)
            <span class="tab-badge">{{ $pendingWarungCount }}</span>
        @endif
    </a>
</div>

<div class="content-box">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-2">
                    <i class="bi bi-person-check-fill me-1"></i> Akun Pemilik
                </span>
                <h3 class="fw-bold mb-0">Daftar Akun Pemilik Menunggu Verifikasi</h3>
            </div>
            <p class="text-muted mb-0 small">
                Akun yang mendaftar lewat formulir pendaftaran pemilik. Verifikasi di sini akan mengaktifkan akun dan mengirimkan password login ke email pemilik.
            </p>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Pemilik</th>
                    <th>Email</th>
                    <th>Warung Diajukan</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($akun as $item)

                <tr>

                    <td>{{ $loop->iteration + ($akun->currentPage() - 1) * $akun->perPage() }}</td>

                    <td>{{ $item->nama }}</td>

                    <td>{{ $item->email }}</td>

                    <td>
                        @if($item->warung)
                            {{ $item->warung->nama_warung }}
                            <div class="text-muted small">{{ $item->warung->alamat }}</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-nowrap">

                        <form action="{{ route('admin.pemilik-akun.verifikasi', $item->id_user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" title="Verifikasi"
                                onclick="return confirm('Verifikasi akun ini? Password baru akan dibuat dan dikirim ke email &quot;' + '{{ $item->email }}' + '&quot;.')">
                                <i class="bi bi-patch-check"></i>
                                Verifikasi
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center">
                        Tidak ada akun pemilik yang menunggu verifikasi saat ini.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $akun->links() }}

</div>

@endsection
