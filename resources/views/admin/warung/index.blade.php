@extends('admin.layouts.app')

@section('title', 'Data Warung')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">Data Warung</h3>

        <a href="{{ route('admin.warung.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Tambah Warung
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Warung</th>
                    <th>Kategori</th>
                    <th>Kabupaten</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($warung as $item)

                @php
                    $statusBadge = [
                        'pending'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                        'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                    ][$item->status];
                @endphp

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

                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>

                    <td>{{ $item->kabupaten->nama_kabupaten ?? '-' }}</td>

                    <td>{{ $item->telepon }}</td>

                    <td>
                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                    </td>

                    <td class="text-nowrap">

                        @if($item->status === 'pending')
                            <form action="{{ route('admin.warung.approve', $item->id_warung) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" title="Setujui" onclick="return confirm('Setujui warung ini supaya tayang di website?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.warung.reject', $item->id_warung) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Tolak" onclick="return confirm('Tolak pengajuan warung ini?')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.warung.menu.index', $item->id_warung) }}" class="btn btn-info btn-sm" title="Kelola {{ $item->label_menu }}">
                            <i class="bi bi-menu-button-wide"></i>
                        </a>

                        <a href="{{ route('admin.warung.edit', $item->id_warung) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.warung.destroy', $item->id_warung) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus warung ini? Semua menu di dalamnya juga akan terhapus.')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="8" class="text-center">

                        Belum ada data warung.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $warung->links() }}

</div>

@endsection
