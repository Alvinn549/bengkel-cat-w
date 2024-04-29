@extends('dashboard.layouts.main')

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
        <h1>Detail Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('dashboard.pelanggan.current-perbaikan', auth()->user()->pelanggan->id) }}">Perbaikan
                        Sekarang</a></li>
                <li class="breadcrumb-item active">Show Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('dashboard.pelanggan.current-perbaikan', auth()->user()->pelanggan->id) }}"
                    class="btn btn-outline-secondary">
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
                                    <img src="{{ asset('assets/dashboard/img/repair.png') }}" alt="Default"
                                        class="col-md-6 img-fluid">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <th>Kode</th>
                                        <td>
                                            <span class="badge bg-secondary" style="font-size: 1rem;">
                                                {{ $perbaikan->kode_unik ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
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
                                        <th>Status</th>
                                        <td><span class="badge {{ $badge_bg }}"
                                                style="font-size: 1rem;">{{ $perbaikan->status ?? '-' }}</span>
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
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    @forelse ($perbaikan->progres->sortByDesc('id') as $progres)
                                        @php
                                            $date = \Carbon\Carbon::parse($progres->created_at)->locale('id');
                                            $formattedDate = $date->format('d-m-Y');
                                            $formattedTime = $date->format('H:i');
                                            $diffForHumans = $date->diffForHumans();
                                        @endphp
                                        <div class="activity-item d-flex">
                                            <div class="activite-label" style="width: 150px">
                                                {{ $diffForHumans ?? '-' }} <br>
                                                {{ $formattedDate . ' ' . $formattedTime ?? '-' }} <br>
                                                Oleh : {{ $progres->pekerja->nama ?? '-' }}
                                            </div>
                                            <i
                                                class='bi bi-circle-fill activity-badge {{ $progres->is_selesai == 1 ? 'text-success' : 'text-warning' }} align-self-start'></i>
                                            <div class="activity-content">
                                                {{ $progres->keterangan ?? '-' }}
                                                <div class="col-md-5 mt-2">
                                                    @if ($progres->foto)
                                                        <a href="javascript:void(0)">
                                                            <img src="{{ asset('storage/' . $progres->foto) }}"
                                                                class="img-fluid rounded" alt=""
                                                                onclick="openImage('{{ asset('storage/' . $progres->foto) }}')">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div><!-- End Progres item-->
                                    @empty
                                        <div class="text-center">
                                            <h5>Tidak ada progres</h5>
                                            <img src="{{ asset('assets/dashboard/img/hang-around.png') }}"
                                                class="img-fluid rounded" alt="">
                                        </div>
                                    @endforelse
                                </div>
                            </div>
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
