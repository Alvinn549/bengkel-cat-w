@extends('dashboard.layouts.main')

@section('css')
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Detail Kendaraan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('dashboard.my-kendaraan', auth()->user()->pelanggan->id) }}">Kendaraan Saya</a></li>
                <li class="breadcrumb-item active">Detail Kendaraan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('dashboard.my-kendaraan', auth()->user()->pelanggan->id) }}"
                    class="btn btn-outline-secondary">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Kendaraan</h5>

                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                @if ($kendaraan->foto)
                                    <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded"
                                        alt="">
                                @else
                                    <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                        class="col-md-12 img-fluid">
                                @endif
                            </div>
                            <div class="col-md-12 mt-3 align-self-center">
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
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="datatable" class="table table-bordered " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Masuk</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kendaraan->perbaikans->sortByDesc('created_at') as $perbaikan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $perbaikan->kode_unik }}</td>
                                                <td>{{ $perbaikan->created_at ?? '-' }}</td>
                                                <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $badge_bg = null;

                                                        if ($perbaikan->status == 'Selesai') {
                                                            $badge_bg = 'bg-success';
                                                        } elseif ($perbaikan->status == 'Dalam Proses') {
                                                            $badge_bg = 'bg-info';
                                                        } elseif ($perbaikan->status == 'Ditunda') {
                                                            $badge_bg = 'bg-secondary';
                                                        } elseif ($perbaikan->status == 'Dibatalkan') {
                                                            $badge_bg = 'bg-warning';
                                                        } elseif ($perbaikan->status == 'Tidak Dapat Diperbaiki') {
                                                            $badge_bg = 'bg-danger';
                                                        } else {
                                                            $badge_bg = 'text-dark';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badge_bg }}">
                                                        {{ $perbaikan->status ?? '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
            });
        })
    </script>
@endsection
