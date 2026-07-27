@extends('pemilik.layouts.app')

@section('title', $warung->label_menu . ' Saya')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold mb-0">Kelola {{ $warung->label_menu }}</h3>

        <a href="{{ route('pemilik.menu.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Tambah {{ $warung->label_menu }}
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
                    <th>Nama {{ $warung->label_menu }}</th>
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

                        <a href="{{ route('pemilik.menu.edit', $item->id_menu) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('pemilik.menu.destroy', $item->id_menu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus {{ strtolower($warung->label_menu) }} ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center">Belum ada {{ strtolower($warung->label_menu) }}. Yuk tambahkan sekarang.</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
