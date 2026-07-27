@extends('admin.layouts.app')

@section('title', 'Verifikasi Warung')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Verifikasi Warung</h3>
            <p class="text-muted mb-0">Daftar pemilik warung yang mengajukan pendaftaran dan menunggu persetujuan.</p>
        </div>

        <a href="{{ route('admin.warung.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-shop"></i>
            Semua Data Warung
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

                        <form action="{{ route('admin.warung.approve', $item->id_warung) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" title="Setujui" onclick="return confirm('Setujui warung ini supaya tayang di website?')">
                                <i class="bi bi-check-lg"></i>
                                Terima
                            </button>
                        </form>

                        <form action="{{ route('admin.warung.reject', $item->id_warung) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Tolak" onclick="return confirm('Tolak pengajuan warung ini?')">
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

                    <td colspan="8" class="text-center">
                        Tidak ada pengajuan warung yang menunggu verifikasi saat ini.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $warung->links() }}

</div>

@endsection
