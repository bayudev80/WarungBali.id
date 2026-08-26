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
    .nav-tabs-user {
        border-bottom: 2px solid #f1f3f5;
        gap: 8px;
    }
    .nav-tabs-user .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 18px;
        border-radius: 10px 10px 0 0;
        position: relative;
        transition: all 0.2s;
    }
    .nav-tabs-user .nav-link:hover {
        color: #C85C2E;
        background: rgba(200, 92, 46, 0.05);
    }
    .nav-tabs-user .nav-link.active {
        color: #C85C2E;
        background: transparent;
    }
    .nav-tabs-user .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #C85C2E;
        border-radius: 2px;
    }
</style>

<div class="content-box px-2">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Data Pengguna</h3>
            <p class="text-muted small mb-0 mt-1">Kelola akun pemilik warung, pengguna umum, dan tinjau riwayat status penghapusan akun.</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabs Navigasi: Pengguna Aktif & Riwayat Dihapus -->
    <ul class="nav nav-tabs nav-tabs-user mb-4" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active d-flex align-items-center gap-2" id="tab-aktif" data-bs-toggle="tab" data-bs-target="#panel-aktif" type="button" role="tab" aria-selected="true">
                <i class="bi bi-people-fill"></i> Pengguna Aktif
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1 small">{{ $user->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center gap-2" id="tab-riwayat" data-bs-toggle="tab" data-bs-target="#panel-riwayat" type="button" role="tab" aria-selected="false">
                <i class="bi bi-person-x-fill text-danger"></i> Riwayat Pengguna Dihapus
                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 small">{{ $deletionLogs->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">

        <!-- TAB 1: PENGGUNA AKTIF -->
        <div class="tab-pane fade show active" id="panel-aktif" role="tabpanel" aria-labelledby="tab-aktif">
            <div class="card modern-card">
                <div class="table-responsive">
                    <table class="table table-borderless user-table mb-0">
                        <colgroup>
                            <col style="width: 50px;">
                            <col style="width: 24%;">
                            <col style="width: 24%;">
                            <col style="width: 14%;">
                            <col style="width: 18%;">
                            <col style="width: 140px;">
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
                                        <a href="{{ route('admin.user.edit', $item->id_user) }}" class="btn btn-warning text-white" title="Edit Pengguna">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($item->id_user !== auth()->id())
                                            <button type="button" class="btn btn-danger text-white" title="Hapus Pengguna" 
                                                onclick="bukaModalHapus({{ $item->id_user }}, '{{ addslashes($item->nama) }}', '{{ addslashes($item->email) }}', '{{ ucfirst($item->role) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
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

        <!-- TAB 2: RIWAYAT PENGGUNA DIHAPUS (STATUS & ALASAN) -->
        <div class="tab-pane fade" id="panel-riwayat" role="tabpanel" aria-labelledby="tab-riwayat">
            <div class="card modern-card">
                <div class="table-responsive">
                    <table class="table table-borderless user-table mb-0">
                        <colgroup>
                            <col style="width: 50px;">
                            <col style="width: 22%;">
                            <col style="width: 10%;">
                            <col style="width: 32%;">
                            <col style="width: 18%;">
                            <col style="width: 140px;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Data Pengguna Dihapus</th>
                                <th>Role</th>
                                <th>Alasan & Status Penghapusan</th>
                                <th>Dihapus Oleh</th>
                                <th>Waktu Penghapusan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($deletionLogs as $log)
                            <tr>
                                <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $log->nama }}</div>
                                    <small class="text-secondary">{{ $log->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 rounded-pill">
                                        {{ ucfirst($log->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill align-self-start px-2 py-1">
                                            <i class="bi bi-shield-x me-1"></i> {{ $log->alasan_kategori }}
                                        </span>
                                        @if(!empty($log->alasan_detail))
                                            <small class="text-muted fst-italic" style="font-size: 12px;">
                                                "{{ $log->alasan_detail }}"
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small"><i class="bi bi-person-check text-primary me-1"></i>{{ $log->deleted_by_name ?? 'Administrator' }}</div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $log->created_at ? $log->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-shield-check fs-1 text-success opacity-50 d-block mb-3"></i>
                                    <h6 class="text-secondary fw-semibold">Belum ada riwayat pengguna yang dihapus.</h6>
                                    <p class="text-muted small">Semua aktivitas penghapusan akun beserta alasan penindakannya akan otomatis tercatat di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- MODAL HAPUS PENGGUNA DENGAN ALASAN -->
<div class="modal fade" id="modalHapusUser" tabindex="-1" aria-labelledby="modalHapusUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h6 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalHapusUserLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Penghapusan Pengguna
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formHapusUser" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="small text-muted mb-1">Pengguna yang akan dihapus:</div>
                        <h6 class="fw-bold text-dark mb-0" id="delNamaUser">-</h6>
                        <small class="text-secondary" id="delEmailUser">-</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">
                            Pilih Alasan Penghapusan: <span class="text-danger">*</span>
                        </label>
                        <select name="alasan_kategori" class="form-select rounded-3" required id="selectAlasanHapus">
                            <option value="Akun Palsu / Indikasi Spam">Akun Palsu / Indikasi Spam</option>
                            <option value="Melanggar Pedoman Komunitas & Ulasan Negatif">Melanggar Pedoman Komunitas & Ulasan Negatif</option>
                            <option value="Penyalahgunaan Akun & Aktivitas Mencurigakan">Penyalahgunaan Akun & Aktivitas Mencurigakan</option>
                            <option value="Permintaan Penghapusan Mandiri oleh Pengguna">Permintaan Penghapusan Mandiri oleh Pengguna</option>
                            <option value="Pelanggaran Ketentuan Kemitraan Warung">Pelanggaran Ketentuan Kemitraan Warung</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark">Catatan / Detail Alasan (Opsional):</label>
                        <textarea name="alasan_detail" class="form-control rounded-3" rows="2" placeholder="Tuliskan keterangan detail tambahan mengapa akun ini ditindak/dihapus..."></textarea>
                    </div>

                    <div class="alert alert-warning py-2 px-3 small border-0 rounded-3 mb-0 mt-3">
                        <i class="bi bi-info-circle me-1"></i> Data dan alasan penindakan ini akan disimpan secara permanen di riwayat audit log admin.
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-3 btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 btn-sm fw-bold">
                        <i class="bi bi-trash me-1"></i> Hapus & Catat Alasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function bukaModalHapus(userId, nama, email, role) {
        const form = document.getElementById('formHapusUser');
        form.action = "{{ url('admin/user') }}/" + userId;
        document.getElementById('delNamaUser').textContent = nama + ' (' + role + ')';
        document.getElementById('delEmailUser').textContent = email;

        const modal = new bootstrap.Modal(document.getElementById('modalHapusUser'));
        modal.show();
    }
</script>

@endsection