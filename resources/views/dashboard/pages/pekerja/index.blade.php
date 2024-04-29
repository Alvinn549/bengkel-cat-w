@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->
    <hr>
    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title">Perbaikan Baru</h5>
                        <a href="{{ route('dashboard.pekerja.list-perbaikan-baru') }}">
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon {{ $countPerbaikansBaru != 0 ? 'bg-warning text-white' : '' }}  rounded-circle d-flex align-items-center justify-content-center">
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
                        <h5 class="card-title">Dalam Antrian</h5>
                        <a href="{{ route('dashboard.pekerja.list-perbaikan-antrian') }}">
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon {{ $countPerbaikansAntrian != 0 ? 'bg-warning text-white' : '' }}  rounded-circle d-flex align-items-center justify-content-center">
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
                        <h5 class="card-title">Dalam Proses</h5>
                        <a href="{{ route('dashboard.pekerja.list-perbaikan-dalam-proses') }}">
                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon {{ $countPerbaikansProses != 0 ? 'bg-warning text-white' : '' }}  rounded-circle d-flex align-items-center justify-content-center">
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
        </div>
    </section>
@endsection

@section('js')
@endsection
