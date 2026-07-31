@extends('layouts.app')

@section('content')

<div class="container py-5" style="margin-top:20px;">

    <h2 class="fw-bold mb-2">
        ❤️ Favorit Saya
    </h2>

    <p class="text-secondary mb-4">
        Kumpulan warung yang sudah kamu simpan / sukai.
    </p>

    @if($warungFavorit->isEmpty())

        <div class="alert alert-warning text-center rounded-4 p-5">

            <div class="fs-1 mb-3">🤍</div>

            <h5 class="fw-bold">Belum ada warung favorit</h5>

            <p class="text-secondary mb-3">
                Tekan ikon hati pada warung yang kamu suka supaya muncul di sini.
            </p>

            <a href="{{ route('home') }}#warung" class="btn text-white rounded-4 px-4" style="background:#C85C2E;">
                Jelajahi Warung
            </a>

        </div>

    @else

        <div class="row g-4">

            @foreach($warungFavorit as $item)

                <div class="col-md-6 col-lg-4">

                    @include('partials.warung-card', ['item' => $item])

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection
