@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Kendraan Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Kendraan Saya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @forelse ($kendaraans as $kendaraan)
                <div class="col-xxl-4 col-md-4">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $kendaraan->no_plat }}</h5>
                            <a href="#">
                                <div class="d-flex align-items-center">
                                    <div class="col-md-3    ">
                                        @if ($kendaraan->foto)
                                            <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded"
                                                alt="">
                                        @else
                                            <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                                class="col-md-6 img-fluid">
                                        @endif
                                    </div>
                                    <div class="ps-3">
                                        <span>{{ $kendaraan->tipe->nama_tipe }}</span><br>
                                        <span>{{ $kendaraan->tipe->nama_tipe }}</span><br>
                                        <span>{{ $kendaraan->tipe->nama_tipe }}</span><br>
                                        <span>{{ $kendaraan->tipe->nama_tipe }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Belum ada kendaraan</p>
            @endforelse
        </div>
    </section>
@endsection

@section('js')
@endsection
