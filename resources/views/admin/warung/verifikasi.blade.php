@extends('admin.layouts.app')

@section('title', 'Verifikasi Warung - Kelola Verifikasi')

@section('content')

{{-- Tab Switcher Verifikasi --}}
<div class="verification-tabs-nav mb-4">
    <a href="{{ route('admin.pemilik-akun.index') }}" class="verification-tab-item">
        <i class="bi bi-person-badge tab-icon"></i>
        <span>Verifikasi Akun Pemilik</span>
        @if(isset($pendingAkunCount) && $pendingAkunCount > 0)
            <span class="tab-badge">{{ $pendingAkunCount }}</span>
        @endif
    </a>

    <div class="verification-tab-divider d-none d-sm-block"></div>

    <a href="{{ route('admin.warung.verifikasi') }}" class="verification-tab-item active">
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
                    <i class="bi bi-shop me-1"></i> Data Warung
                </span>
                <h3 class="fw-bold mb-0">Daftar Pengajuan Warung Menunggu Persetujuan</h3>
            </div>
            <p class="text-muted mb-0 small">
                Pengajuan warung baru yang didaftarkan. Menyetujui pengajuan akan langsung menampilkan warung di website publik.
            </p>
        </div>

        <a href="{{ route('admin.warung.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Semua Data Warung
        </a>

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
                    <th>Foto</th>
                    <th>Nama Warung</th>
                    <th>Pemilik</th>
                    <th>Kategori</th>
                    <th>Kabupaten</th>
                    <th>Telepon</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($warung as $item)

                <tr>

                    <td>{{ $loop->iteration + ($warung->currentPage() - 1) * $warung->perPage() }}</td>

                    <td>
                        @if($item->foto && file_exists(public_path('images/warung/'.$item->foto)))
                            <img src="{{ asset('images/warung/'.$item->foto) }}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                        @else
                            <span class="text-muted small">Tidak ada foto</span>
                        @endif
                    </td>

                    <td>{{ $item->nama_warung }}</td>

                    <td>
                        {{ $item->user->nama ?? '-' }}
                        <div class="text-muted small">{{ $item->user->email ?? '' }}</div>
                    </td>

                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>

                    <td>{{ $item->kabupaten->nama_kabupaten ?? '-' }}</td>

                    <td>{{ $item->telepon ?? '-' }}</td>

                    <td class="text-nowrap">

                        <form action="{{ route('admin.warung.approve', $item->id_warung) }}" method="POST" class="d-inline form-approve-warung" data-nama="{{ $item->nama_warung }}">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-success btn-sm btn-action-approve" title="Setujui">
                                <i class="bi bi-check-lg"></i>
                                Terima
                            </button>
                        </form>

                        <form action="{{ route('admin.warung.reject', $item->id_warung) }}" method="POST" class="d-inline form-reject-warung" data-nama="{{ $item->nama_warung }}">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-outline-danger btn-sm btn-action-reject" title="Tolak">
                                <i class="bi bi-x-lg"></i>
                                Tolak
                            </button>
                        </form>

                        <a href="{{ route('admin.warung.edit', $item->id_warung) }}" class="btn btn-outline-secondary btn-sm" title="Lihat / Edit Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-shop fs-3 d-block mb-2 text-secondary opacity-50"></i>
                        Tidak ada pengajuan warung yang menunggu verifikasi saat ini.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $warung->links() }}

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Approve Action
    document.querySelectorAll('.btn-action-approve').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const nama = form.getAttribute('data-nama') || 'Warung';

            window.showWarungBaliModal({
                title: 'Setujui Pengajuan Warung',
                message: `Warung <b>${nama}</b> akan disetujui dan langsung ditayangkan di website publik WarungBali.id.`,
                icon: 'bi bi-shop',
                variant: 'success',
                confirmText: '<i class="bi bi-check-lg me-1"></i> Ya, Setujui',
                onConfirm: function () {
                    form.submit();
                }
            });
        });
    });

    // Reject Action
    document.querySelectorAll('.btn-action-reject').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const nama = form.getAttribute('data-nama') || 'Warung';

            window.showWarungBaliModal({
                title: 'Tolak Pengajuan Warung',
                message: `Apakah Anda yakin ingin menolak pengajuan warung <b>${nama}</b>?`,
                icon: 'bi bi-x-circle-fill',
                variant: 'danger',
                confirmText: '<i class="bi bi-x-lg me-1"></i> Ya, Tolak',
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
