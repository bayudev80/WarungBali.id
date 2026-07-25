@extends('admin.layouts.app')

@section('title', 'Pengguna')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">Kelola Pengguna</h3>

        <a href="{{ route('admin.user.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Tambah Pengguna
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($user as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->email }}</td>
                    <td>
                        <span class="badge {{ $item->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                            {{ ucfirst($item->role) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.user.edit', $item->id_user) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.user.destroy', $item->id_user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">Belum ada data pengguna.</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection