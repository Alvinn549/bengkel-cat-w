@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Perbaikan Baru</h5>
                        <a href="{{ route('dashboard.admin.list-perbaikan-baru') }}">
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-hammer"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $countPerbaikansBaru }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Antrian Perbaikan</h5>
                        <a href="{{ route('dashboard.admin.list-perbaikan-antrian') }}">
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $countPerbaikansAntrian }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Perbaikan Dalam Proses</h5>
                        <a href="{{ route('dashboard.admin.list-perbaikan-dalam-proses') }}">
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $countPerbaikansProses }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Perbaikan Selesai Di Proses</h5>
                        <a href="{{ route('dashboard.admin.list-perbaikan-selesai-di-proses') }}">
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon {{ $countPerbaikansProsesSelesai != 0 ? 'bg-warning text-white' : '' }}  rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check2-circle"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $countPerbaikansProsesSelesai }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Perbaikan menunggu bayar</h5>
                        <a href="{{ route('dashboard.admin.list-perbaikan-menunggu-bayar') }}">
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon {{ $countPerbaikansMenungguBayar != 0 ? 'bg-warning text-white' : '' }}  rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $countPerbaikansMenungguBayar }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
