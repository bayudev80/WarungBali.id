@extends('admin.layouts.app')

@section('title','Tambah Kategori')

@section('content')

<div class="container">

<h3 class="mb-4">

Tambah Kategori

</h3>

<form action="{{ route('admin.kategori.store') }}" method="POST">

@csrf

<div class="mb-3">

<label class="form-label">

Nama Kategori

</label>

<input
type="text"
name="nama_kategori"
class="form-control"
required>

</div>

<button class="btn btn-success">

Simpan

</button>

<a href="{{ route('admin.kategori.index') }}"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

@endsection