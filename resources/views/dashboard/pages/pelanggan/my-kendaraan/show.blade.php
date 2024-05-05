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
                        href="{{ route('dashboard.pelanggan.my-kendaraan', auth()->user()->pelanggan->id) }}">Kendaraan
                        Saya</a></li>
                <li class="breadcrumb-item active">Detail Kendaraan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('dashboard.pelanggan.my-kendaraan', auth()->user()->pelanggan->id) }}"
                    class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Kembali">
                    <i class="ri-arrow-go-back-line"></i>
                </a>
            </div>
            <div class="col-lg-5">
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
                                <table class="table table-borderless">
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
            <div class="col-lg-7">
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
