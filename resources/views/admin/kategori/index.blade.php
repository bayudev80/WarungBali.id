@extends('admin.layouts.app')

@section('title', 'Kategori')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>Kelola Kategori</h3>

        <a href="{{ route('admin.kategori.create') }}"
            class="btn btn-success">

            <i class="fas fa-plus"></i>
            Tambah Kategori

        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <table class="table table-bordered table-striped">

        <thead class="table-success">

            <tr>

                <th width="80">No</th>

                <th>Nama Kategori</th>

                <th width="180">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($kategori as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->nama_kategori }}</td>

                <td>

                    <a href="{{ route('admin.kategori.edit',$item->id_kategori) }}"
                        class="btn btn-warning btn-sm">

                        Edit

                    </a>

                    <form
                        action="{{ route('admin.kategori.destroy',$item->id_kategori) }}"
                        method="POST"
                        class="d-inline">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus kategori ini?')">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3" class="text-center">

                    Belum ada data kategori.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection