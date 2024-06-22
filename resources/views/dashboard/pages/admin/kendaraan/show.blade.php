@extends('dashboard.layouts.main')

@section('css')
    <style>
        .btn-show-pemilik {
            cursor: pointer;
            border: none;
            background-color: transparent;
        }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Lihat Kendaraan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kendaraan.index') }}">Kendaraan</a></li>
                <li class="breadcrumb-item active">Show</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="modal fade" id="dataPemilik" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Data Pemilik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $kendaraan->pelanggan->nama ?? '' }}</td>
                        </tr>
                        <tr>
                            <th nowrap>No Telp</th>
                            <td>{{ $kendaraan->pelanggan->no_telp ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $kendaraan->pelanggan->alamat ?? '' }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div><!-- End Vertically centered Modal-->

    <section class="section">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip"
                    data-bs-placement="right" title="Kembali">
                    <i class="ri-arrow-go-back-line"></i>
                </a>
            </div>
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
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Pemilik</th>
                                        <td>
                                            {{ $kendaraan->pelanggan->nama ?? '-' }}
                                            <button type="button" data-bs-toggle="modal"
                                                class="btn-show-pemilik btn btn-outline-primary btn-sm ms-2"
                                                data-bs-target="#dataPemilik">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
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
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="datatable" class="table table-bordered ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama Perbaikan</th>
                                            <th>Masuk</th>
                                            <th>Selesai</th>
                                            <th>Biaya</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kendaraan->perbaikans->sortByDesc('created_at') as $perbaikan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $perbaikan->kode_unik }}</td>
                                                <td>{{ $perbaikan->nama }}</td>
                                                <td>{{ $perbaikan->created_at ?? '-' }}</td>
                                                <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
                                                <td>{{ $perbaikan->biaya }}</td>
                                                <td>
                                                    @php
                                                        $badge_bg = null;
                                                        $btn_color = null;

                                                        switch ($perbaikan->status) {
                                                            case 'Selesai':
                                                                $badge_bg = 'bg-success';
                                                                $btn_color = 'success';
                                                                break;
                                                            case 'Baru':
                                                                $badge_bg = 'bg-info';
                                                                $btn_color = 'info';
                                                                break;
                                                            case 'Antrian':
                                                                $badge_bg = 'bg-primary';
                                                                $btn_color = 'primary';
                                                                break;
                                                            case 'Dalam Proses':
                                                                $badge_bg = 'bg-secondary';
                                                                $btn_color = 'secondary';
                                                                break;
                                                            case 'Menunggu Bayar':
                                                                $badge_bg = 'bg-warning';
                                                                $btn_color = 'warning';
                                                                break;
                                                            default:
                                                                $badge_bg = 'bg-dark';
                                                                $btn_color = 'dark';
                                                                break;
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badge_bg }} w-100">
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
