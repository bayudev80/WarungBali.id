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
                            <div class="fw-semibold">{{ $item->warung->nama_warung }}</div>
                            <div class="text-muted small">{{ $item->warung->alamat }}</div>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i> Diisi setelah login
                            </span>
                        @endif
                    </td>

                    <td class="text-nowrap">

                        <form action="{{ route('admin.pemilik-akun.verifikasi', $item->id_user) }}" method="POST" class="d-inline form-verifikasi-pemilik" data-nama="{{ $item->nama }}" data-email="{{ $item->email }}">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-success btn-sm btn-submit-verifikasi" title="Verifikasi">
                                <i class="bi bi-patch-check"></i>
                                Verifikasi
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-3 d-block mb-2 text-success opacity-50"></i>
                        Tidak ada akun pemilik yang menunggu verifikasi saat ini.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $akun->links() }}

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-submit-verifikasi').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const nama = form.getAttribute('data-nama') || 'Pemilik';
            const email = form.getAttribute('data-email') || '';

            window.showWarungBaliModal({
                title: 'Verifikasi Akun Pemilik',
                message: `Konfirmasi verifikasi untuk akun <b>${nama}</b>.<br><span class="text-muted small">Password login resmi akan dibuat otomatis dan dikirimkan ke email <b>${email}</b>.</span>`,
                icon: 'bi bi-patch-check-fill',
                variant: 'success',
                confirmText: '<i class="bi bi-patch-check me-1"></i> Ya, Verifikasi',
                onConfirm: function () {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

@endsection
