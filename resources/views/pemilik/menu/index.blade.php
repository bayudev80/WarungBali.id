@extends('pemilik.layouts.app')

@section('title', $warung->label_menu . ' Saya')

@section('content')

<div class="content-box">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1">Kelola {{ $warung->label_menu }}</h3>
            <p class="text-muted small mb-0">Daftar {{ strtolower($warung->label_menu) }} yang tampil di halaman warung Anda.</p>
        </div>
        <a href="{{ route('pemilik.menu.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle-fill"></i>
            Tambah {{ $warung->label_menu }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th style="width:72px">Foto</th>
                    <th>Nama {{ $warung->label_menu }}</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th class="text-end" style="width:110px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menu as $item)
                <tr>
                    <td class="text-muted fw-semibold">{{ $loop->iteration }}</td>
                    <td>
                        @if($item->foto_menu && file_exists(public_path('images/menu/'.$item->foto_menu)))
                            <img src="{{ asset('images/menu/'.$item->foto_menu) }}"
                                 style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:2px solid #e9ecef;">
                        @else
                            <div style="width:52px;height:52px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:1.2rem;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-semibold" style="font-size:14px">{{ $item->nama_menu }}</span>
                    </td>
                    <td>
                        <span class="text-muted" style="font-size:13px;max-width:220px;display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                            {{ $item->deskripsi ?? 'â€”' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:13.5px;font-weight:700;color:#d97706;">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('pemilik.menu.edit', $item->id_menu) }}"
                           class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('pemilik.menu.destroy', $item->id_menu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    title="Hapus"
                                    onclick="return confirm('Hapus {{ strtolower($warung->label_menu) }} ini?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div style="text-align:center;padding:48px 16px;color:#94a3b8;">
                            <i class="bi bi-basket2" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.4;"></i>
                            <p style="font-size:13.5px;margin:0;">Belum ada {{ strtolower($warung->label_menu) }}. Yuk tambahkan sekarang!</p>
                            <a href="{{ route('pemilik.menu.create') }}" class="btn btn-success btn-sm mt-3">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
