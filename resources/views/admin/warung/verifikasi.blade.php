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

                        <button type="button" class="btn btn-outline-danger btn-sm btn-action-reject" data-id="{{ $item->id_warung }}" data-nama="{{ $item->nama_warung }}" data-action="{{ route('admin.warung.reject', $item->id_warung) }}" title="Tolak">
                            <i class="bi bi-x-lg"></i>
                            Tolak
                        </button>

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

<!-- Modal Tolak Pengajuan Warung -->
<div class="modal fade" id="modalTolakWarung" tabindex="-1" aria-labelledby="modalTolakWarungLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="modalTolakWarungLabel">
                    <i class="bi bi-x-circle-fill fs-4"></i> Tolak Pengajuan Warung
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formModalTolakWarung" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body py-3">
                    <p class="text-dark small mb-3">
                        Anda akan menolak pengajuan warung: <strong id="modalNamaWarung" class="text-danger"></strong>. 
                        Sertakan catatan/alasan penolakan di bawah agar pemilik warung dapat memperbaiki datanya:
                    </p>

                    <!-- Quick Preset Reasons -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Pilih Alasan Cepat (Opsional):</label>
                        <div class="d-flex flex-wrap gap-1">
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-1 small btn-preset-reason" style="font-size: 11px;" onclick="setAlasanTolak(this.innerText)">Foto warung buram / tidak sesuai</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-1 small btn-preset-reason" style="font-size: 11px;" onclick="setAlasanTolak(this.innerText)">Alamat atau lokasi kurang jelas</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-1 small btn-preset-reason" style="font-size: 11px;" onclick="setAlasanTolak(this.innerText)">Nomor telepon tidak dapat dihubungi</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-1 small btn-preset-reason" style="font-size: 11px;" onclick="setAlasanTolak(this.innerText)">Bukan kategori kuliner Bali</button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark">Alasan / Catatan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" id="modalAlasanPenolakan" class="form-control rounded-3" rows="3" placeholder="Tuliskan catatan alasan penolakan untuk pemilik warung..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                        <i class="bi bi-x-lg me-1"></i> Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setAlasanTolak(text) {
    const textarea = document.getElementById('modalAlasanPenolakan');
    if (textarea) {
        textarea.value = text;
        textarea.focus();
    }
}

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

    // Reject Action via Modal
    const modalEl = document.getElementById('modalTolakWarung');
    const modalInstance = modalEl ? new bootstrap.Modal(modalEl) : null;
    const formReject = document.getElementById('formModalTolakWarung');
    const namaLabel = document.getElementById('modalNamaWarung');
    const textareaAlasan = document.getElementById('modalAlasanPenolakan');

    document.querySelectorAll('.btn-action-reject').forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const nama = this.getAttribute('data-nama') || 'Warung';
            const action = this.getAttribute('data-action') || '';

            if (formReject) formReject.action = action;
            if (namaLabel) namaLabel.textContent = nama;
            if (textareaAlasan) textareaAlasan.value = '';

            if (modalInstance) {
                modalInstance.show();
            }
        });
    });
});
</script>
@endpush

@endsection
