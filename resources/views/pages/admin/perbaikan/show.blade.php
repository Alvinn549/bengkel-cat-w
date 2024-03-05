@extends('layouts.main')

@section('css')
    <style>
        .btn-show-pemilik {
            cursor: pointer;
            border: none;
            background-color: transparent;
        }

        .btn-show-pemilik:hover {
            color: #007bff;
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
                <li class="breadcrumb-item active">Show Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('kendaraan.show', $perbaikan->kendaraan_id) }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <div class="row g-3">
                            <div class="col-md-12 d-flex justify-content-center">
                                @if ($perbaikan->foto)
                                    <img src="{{ asset('storage/' . $perbaikan->foto) }}" class="img-fluid rounded"
                                        alt="">
                                @else
                                    <img src="{{ asset('assets/img/repair.png') }}" alt="Default"
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
                                                $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[0]);
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
                                        <td><span class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Data Perbaikan-->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Progres Perbaikan</h5>
                        <div class="activity">
                            @forelse ($perbaikan->progres as $progres)
                                @php
                                    $date = \Carbon\Carbon::parse($progres->created_at)->locale('id');
                                    $formattedDate = $date->format('d-m-Y');
                                    $formattedTime = $date->format('H:i');
                                    $diffForHumans = $date->diffForHumans();
                                @endphp
                                <div class="activity-item d-flex">
                                    <div class="activite-label" style="width: 150px">
                                        {{ $diffForHumans ?? '-' }} <br>
                                        {{ $formattedDate ?? '-' }} <br>
                                        {{ $formattedTime ?? '-' }}
                                    </div>
                                    <i
                                        class='bi bi-circle-fill activity-badge {{ $progres->status == 'Selesai' ? 'text-success' : 'text-warning' }} align-self-start'></i>
                                    <div class="activity-content">
                                        {{ $progres->keterangan }}
                                        <div class="col-md-4">
                                            @if ($progres->foto)
                                                <a href="#">
                                                    <img src="{{ asset('storage/' . $progres->foto) }}"
                                                        class="img-fluid rounded" alt=""
                                                        onclick="openImage('{{ asset('storage/' . $progres->foto) }}')">
                                                </a>
                                            @else
                                                <a href="#">
                                                    <img src="{{ asset('assets/img/spray-gun.png') }}"
                                                        class="img-fluid rounded" alt=""
                                                        onclick="openImage('{{ asset('assets/img/spray-gun.png') }}')">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div><!-- End Progres item-->
                            @empty
                                <h5>Belum ada progres</h5>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div><!-- End Progres -->
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
            });
        })
    </script>
    <script>
        function openImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
                imageWidth: 450,
                imageAlt: 'Foto Progres',
                showConfirmButton: false,
            });
        }
    </script>
@endsection
