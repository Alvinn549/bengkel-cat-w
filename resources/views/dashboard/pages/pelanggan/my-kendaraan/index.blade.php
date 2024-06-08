@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Kendraan Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Kendaraan Saya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row justify-content-center">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip"
                    data-bs-placement="right" title="Kembali">
                    <i class="ri-arrow-go-back-line"></i>
                </a>
            </div>
            @forelse ($kendaraans as $kendaraan)
                <div class="col-md-4">
                    <div class="card" style=" height: 100%;">
                        <div class="card-body">
                            <div class="row g-3 mt-2">
                                <div class="col-md-12 d-flex justify-content-center" style="height: 200px;">
                                    @if ($kendaraan->foto)
                                        <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded"
                                            alt="" style="object-fit: cover; height: 100%; width: auto;">
                                    @else
                                        <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                            class="col-md-6 img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <table class="table ">
                                        <tr>
                                            <th nowrap>No Plat</th>
                                            <td>{{ $kendaraan->no_plat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Merek</th>
                                            <td>{{ $kendaraan->merek->nama_merek ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipe</th>
                                            <td>{{ $kendaraan->tipe->nama_tipe ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $kendaraan->keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <th nowrap>Terdaftar Sejak</th>
                                            <td>{{ $kendaraan->created_at }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dashboard.pelanggan.my-kendaraan-detail', $kendaraan->id) }}"
                                class="btn btn-secondary w-100">
                                <i class="ri-eye-line me-2"></i> Lihat Detail
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
