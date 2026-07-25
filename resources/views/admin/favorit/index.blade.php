@extends('admin.layouts.app')

@section('title', 'Favorit')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Kelola Favorit</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Warung</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($favorit as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>{{ $item->warung->nama_warung ?? '-' }}</td>
                    <td>
                        <form action="{{ route('admin.favorit.destroy', $item->id_favorit) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus favorit ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center">Belum ada data favorit.</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
