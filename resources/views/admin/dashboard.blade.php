@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4 fw-bold">
    Dashboard WarungBali.id
</h2>

<div class="row">

    <div class="col-md-3 mb-3">
        <div class="card shadow border-0">
            <div class="card-body">
                <h6>Total Warung</h6>
                <h2>{{ $jumlahWarung }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow border-0">
            <div class="card-body">
                <h6>Total User</h6>
                <h2>{{ $jumlahUser }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow border-0">
            <div class="card-body">
                <h6>Total Review</h6>
                <h2>{{ $jumlahReview }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card shadow border-0">
            <div class="card-body">
                <h6>Total Kategori</h6>
                <h2>{{ $jumlahKategori }}</h2>
            </div>
        </div>
    </div>

</div>

@endsection