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
        <div class="row">
            @forelse ($kendaraans as $kendaraan)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Kendaraan</h5>

                            <div class="row">
                                <div class="col-md-6 d-flex justify-content-center">
                                    @if ($kendaraan->foto)
                                        <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                            class="col-md-6 img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-6 align-self-center">
                                    <table class="table">
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
                                        <tr>
                                            <td colspan="2">
                                                <a href="{{ route('dashboard.my-kendaraan-detail', $kendaraan->id) }}"
                                                    class="btn btn-secondary w-100">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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
