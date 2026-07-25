@extends('admin.layouts.app')

@section('title', 'Menu - ' . $warung->nama_warung)

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">Kelola Menu</h3>
            <span class="text-muted">Warung: {{ $warung->nama_warung }}</span>
        </div>

        <div>
            <a href="{{ route('admin.warung.menu.create', $warung->id_warung) }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Tambah Menu
            </a>
            <a href="{{ route('admin.warung.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

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
                    <th>Nama Menu</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($menu as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        @if($item->foto_menu && file_exists(public_path('images/menu/'.$item->foto_menu)))
                            <img src="{{ asset('images/menu/'.$item->foto_menu) }}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                        @else
                            <span class="text-muted small">Tidak ada foto</span>
                        @endif
                    </td>

                    <td>{{ $item->nama_menu }}</td>

                    <td>{{ $item->deskripsi ?? '-' }}</td>

                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>

                    <td>

                        <a href="{{ route('admin.warung.menu.edit', [$warung->id_warung, $item->id_menu]) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('admin.warung.menu.destroy', [$warung->id_warung, $item->id_menu]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus menu ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center">Belum ada menu untuk warung ini.</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
