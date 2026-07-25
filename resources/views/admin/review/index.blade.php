@extends('admin.layouts.app')

@section('title', 'Review')

@section('content')

<div class="content-box">

    <h3 class="fw-bold mb-4">Kelola Review</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Warung</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($review as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->warung->nama_warung ?? '-' }}</td>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            {{ $item->rating }} <i class="bi bi-star-fill"></i>
                        </span>
                    </td>
                    <td>{{ $item->komentar ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.review.destroy', $item->id_review) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus review ini?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center">Belum ada data review.</td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
