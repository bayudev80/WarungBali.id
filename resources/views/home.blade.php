@extends('layouts.app')

@section('content')

<!-- HERO -->
<section class="py-5" style="background:#FFF8EC;">
    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <span class="badge bg-warning text-dark px-3 py-2 mb-3">
                    Jelajahi Kuliner Bali
                </span>

                <h1 class="display-3 fw-bold">
                    Temukan Warung Terbaik di Bali
                </h1>

                <p class="lead text-secondary mt-4">
                    Cari warung berdasarkan lokasi, kategori makanan,
                    dan rekomendasi masyarakat Bali.
                </p>

                <div class="mt-4">

                    <a href="#" class="btn btn-warning btn-lg text-white me-2">
                        Jelajahi Sekarang
                    </a>

                    <a href="#" class="btn btn-outline-dark btn-lg">
                        Lihat Warung
                    </a>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <img
                src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=900"
                class="img-fluid rounded-4 shadow"
                alt="Warung Bali">

            </div>

        </div>

    </div>
</section>

<!-- WARUNG -->
<section class="py-5">

<div class="container">

<h2 class="fw-bold mb-4">
Warung Populer
</h2>

<div class="row">

@foreach($warung as $item)

<div class="col-lg-4 mb-4">

<div class="card shadow h-100">

<img src="{{ asset('images/warung/' . $item->foto) }}"
     class="card-img-top"
     alt="{{ $item->nama_warung }}"
     style="height:220px; object-fit:cover;">

<div class="card-body">

<h4>
{{ $item->nama_warung }}
</h4>

<p class="text-secondary">

<i class="bi bi-geo-alt-fill text-warning"></i>

{{ $item->alamat }}

</p>

<p>

{{ $item->deskripsi }}

</p>

<a href="#"
class="btn btn-warning text-white">

Lihat Detail

</a>

</div>

</div>

</div>

@endforeach

</div>

</div>

</section>

@endsection