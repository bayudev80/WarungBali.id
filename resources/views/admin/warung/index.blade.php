@extends('admin.layouts.app')

@section('title', 'Data Warung')

@section('content')

<div class="content-box">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">Data Warung</h3>

        <a href="#" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Tambah Warung
        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Warung</th>
                    <th>Kategori</th>
                    <th>Kabupaten</th>
                    <th>Telepon</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($warung as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->nama_warung }}</td>

                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>

                    <td>{{ $item->kabupaten->nama_kabupaten ?? '-' }}</td>

                    <td>{{ $item->telepon }}</td>

                    <td>

                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center">

                        Belum ada data warung.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection