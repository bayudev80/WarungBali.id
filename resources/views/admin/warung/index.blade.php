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

    {{-- Form pencarian & filter kategori --}}
    <form action="{{ route('admin.warung.index') }}" method="GET" class="row g-2 align-items-center mb-4">

        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari nama warung, alamat, atau telepon...">
            </div>
        </div>

        <div class="col-8 col-md-3">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id_kategori }}" @selected((string) $kategoriTerpilih === (string) $k->id_kategori)>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-4 col-md-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>

        @if($search !== '' || $kategoriTerpilih)
            <div class="col-12 col-md-2">
                <a href="{{ route('admin.warung.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        @endif

    </form>

    @php
        $grouped = $warung->getCollection()->groupBy(function ($item) {
            return $item->kategori->nama_kategori ?? 'Tanpa Kategori';
        });
    @endphp

    @if($warung->isEmpty())

        <div class="alert alert-light border text-center">
            @if($search !== '' || $kategoriTerpilih)
                Tidak ada warung yang cocok dengan pencarian/filter ini.
            @else
                Belum ada data warung.
            @endif
        </div>

    @else

        @foreach($grouped as $namaKategori => $items)

            @php $groupId = 'kategori-group-' . $loop->index; @endphp

            <div class="card mb-3 shadow-sm">

                <div class="card-header bg-light d-flex justify-content-between align-items-center"
                    role="button" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}"
                    aria-expanded="true" aria-controls="{{ $groupId }}">

                    <span class="fw-semibold">
                        <i class="bi bi-tag-fill me-1"></i>
                        {{ $namaKategori }}
                        <span class="badge bg-secondary ms-2">{{ $items->count() }} warung</span>
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </div>

                <div id="{{ $groupId }}" class="collapse show">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead>

                                <tr>

                                    <th>Foto</th>
                                    <th>Nama Warung</th>
                                    <th>Kabupaten</th>
                                    <th>Telepon</th>
                                    <th>Catering</th>
                                    <th>Status</th>
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($items as $item)

                                @php
                                    $statusBadge = [
                                        'pending'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                                        'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
                                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                                    ][$item->status];
                                @endphp

                                <tr>

                                    <td>
                                        @if($item->foto && file_exists(public_path('images/warung/'.$item->foto)))
                                            <img src="{{ asset('images/warung/'.$item->foto) }}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                        @else
                                            <span class="text-muted small">Tidak ada foto</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->nama_warung }}</td>

                                    <td>{{ $item->kabupaten->nama_kabupaten ?? '-' }}</td>

                                    <td>{{ $item->telepon }}</td>

                                    <td>
                                        @if(!$item->is_kuliner)
                                            <span class="text-muted small">-</span>
                                        @elseif($item->menerima_catering)
                                            <span class="badge bg-success-subtle text-success">Ya</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Tidak</span>
                                        @endif
                                    </td>

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

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endforeach

        {{ $warung->links() }}

    @endif

</div>

@endsection