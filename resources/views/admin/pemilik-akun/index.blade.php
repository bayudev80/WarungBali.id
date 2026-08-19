@extends('admin.layouts.app')

@section('title', 'Kelola Akun Pemilik')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Kelola Akun Pemilik</h3>
            <p class="text-muted mb-0">Akun yang daftar lewat "Daftar sebagai Pemilik Warung" dan menunggu verifikasi. Verifikasi di sini terpisah dari approve/reject warungnya di menu Data Warung.</p>
        </div>

        <a href="{{ route('admin.warung.verifikasi') }}" class="btn btn-outline-secondary">
            <i class="bi bi-shop"></i>
            Verifikasi Warung
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
                            {{ $item->warung->nama_warung }}
                            <div class="text-muted small">{{ $item->warung->alamat }}</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td class="text-nowrap">

                        <form action="{{ route('admin.pemilik-akun.verifikasi', $item->id_user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" title="Verifikasi"
                                onclick="return confirm('Verifikasi akun ini? Password baru akan dibuat dan dikirim ke email &quot;' + '{{ $item->email }}' + '&quot;.')">
                                <i class="bi bi-patch-check"></i>
                                Verifikasi
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center">
                        Tidak ada akun pemilik yang menunggu verifikasi saat ini.
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $akun->links() }}

</div>

@endsection
