@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card">
                        <div class="card-body">
                            <h5 class="card-title">Dalam Proses Perbaikan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bxs-car-crash"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $perbaikanBerlangsungCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
                <div class="col-xxl-6 col-md-6">
                    <div class="card info-card ">
                        <div class="card-body">
                            <h5 class="card-title">Dalam Proses Transaksi</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $transaksiBerlangsungCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
                <hr>
                <div class="col-xxl-3 col-md-3">
                    <div class="card info-card ">
                        <div class="card-body">
                            <h5 class="card-title">Kendaraan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bxs-car"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $kendaraanCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
                <div class="col-xxl-3 col-md-3">
                    <div class="card info-card ">
                        <div class="card-body">
                            <h5 class="card-title">Pelanggan</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $pelangganCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
                <div class="col-xxl-3 col-md-3">
                    <div class="card info-card ">
                        <div class="card-body">
                            <h5 class="card-title">Perbaikan Selesai</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-badge-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $perbaikanSelesaiCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
                <div class="col-xxl-3 col-md-3">
                    <div class="card info-card ">
                        <div class="card-body">
                            <h5 class="card-title">Transaksi Selesai</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-credit-card-2-back-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $transaksiSelesaiCount ?? 0 }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Transaksi Sekarang Card -->
            </div>
            <div class="row">
                <div class="col-xxl-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Current Activity</h5>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
