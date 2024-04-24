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
        <h1>History Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">History Perbaikan</li>
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
                            <a href="{{ route('dashboard.pelanggan.history-perbaikan-detail', $perbaikan->id) }}"
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
