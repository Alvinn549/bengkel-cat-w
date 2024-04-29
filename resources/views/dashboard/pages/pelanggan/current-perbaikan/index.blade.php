@extends('dashboard.layouts.main')

@section('css')
    <style>
        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            flex: 1 1 300px;
            margin: 0 10px 20px;
        }

        .card-body {
            display: flex;
            flex-direction: column;
        }

        .card-body .btn {
            margin-top: auto;
        }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Perbaikan Sekarang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Perbaikan Sekarang</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row card-deck">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>
            <div class="card-deck">
                @forelse ($perbaikans as $perbaikan)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{ $perbaikan->kode_unik }}</h5>
                            <div class="row g-3">
                                <div class="col-md-12 d-flex justify-content-center">
                                    @if ($perbaikan->foto)
                                        <img src="{{ asset('storage/' . $perbaikan->foto) }}" class="img-fluid rounded"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/dashboard/img/repair.png') }}" alt="Default"
                                            class="col-md-6 img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <table class="table">
                                        <tr>
                                            <th>Nama</th>
                                            <td>{{ $perbaikan->nama ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $perbaikan->keterangan ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Biaya</th>
                                            <td>Rp. {{ number_format($perbaikan->biaya) ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                if ($perbaikan->durasi) {
                                                    $durations = explode(' to ', $perbaikan->durasi);
                                                    $startDate = \Carbon\Carbon::createFromFormat(
                                                        'd-m-Y',
                                                        $durations[0],
                                                    );
                                                    $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[1]);

                                                    $days = $startDate->diffInDays($endDate);
                                                }
                                            @endphp
                                            <th>Durasi</th>
                                            <td>
                                                @if ($perbaikan->durasi)
                                                    {{ $days ?? '-' }} Hari <br> {{ $perbaikan->durasi ?? '-' }}
                                                @else
                                                    {{ $perbaikan->durasi ?? '-' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Masuk</th>
                                            <td>{{ $perbaikan->created_at ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Selesai</th>
                                            <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $badge_bg = null;
                                                $btn_color = null;

                                                if ($perbaikan->status == 'Selesai') {
                                                    $badge_bg = 'bg-success';
                                                    $btn_color = 'success';
                                                } elseif (
                                                    $perbaikan->status == 'Baru' ||
                                                    $perbaikan->status == 'Antrian'
                                                ) {
                                                    $badge_bg = 'bg-info';
                                                    $btn_color = 'info';
                                                } elseif ($perbaikan->status == 'Dalam Proses') {
                                                    $badge_bg = 'bg-secondary';
                                                    $btn_color = 'secondary';
                                                } elseif ($perbaikan->status == 'Menunggu Bayar') {
                                                    $badge_bg = 'bg-warning';
                                                    $btn_color = 'warning';
                                                } else {
                                                    $badge_bg = 'bg-dark';
                                                    $btn_color = 'dark';
                                                }
                                            @endphp
                                            <th>Status</th>
                                            <td><span
                                                    class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.pelanggan.current-perbaikan-detail', $perbaikan->id) }}"
                                class="btn btn-secondary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
            </div><!-- End Data Perbaikan-->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <div class="alert alert-danger" role="alert">
                            Tidak ada data perbaikan
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </section>
@endsection

@section('js')
@endsection
