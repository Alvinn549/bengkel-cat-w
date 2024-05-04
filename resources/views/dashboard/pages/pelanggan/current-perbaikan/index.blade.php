@extends('dashboard.layouts.main')

@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Perbaikan Saat Ini</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Perbaikan Saat Ini</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row justify-content-center">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>

            @forelse ($perbaikans as $perbaikan)
                <div class="col-md-4">
                    <div class="card" style=" height: 100%;">
                        <div class="card-body">
                            <h5 class="card-title text-center">
                                <span class="badge bg-secondary text-white rounded-pill"
                                    style="font-size: 16px">{{ $perbaikan->kode_unik }}</span>
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-12 d-flex justify-content-center" style="height: 200px;">
                                    @if ($perbaikan->foto)
                                        <img src="{{ asset('storage/' . $perbaikan->foto) }}" class="img-fluid rounded"
                                            alt="" style="object-fit: cover; height: 100%; width: auto;">
                                    @else
                                        <img src="{{ asset('assets/dashboard/img/repair.png') }}" alt="Default"
                                            class="col-md-6 img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-borderless">
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
                                            <td><span
                                                    class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            @if ($perbaikan->transaksi)
                                <p class="text-center">Silahkan lakukan pembayaran</p>
                                <a href="{{ route('dashboard.pelanggan.my-transaksi-detail', $perbaikan->transaksi->id) }}"
                                    class="btn btn-warning text-white mb-2 w-100"><i class="bi bi-cash me-2"></i>
                                    Proses Pembayaran
                                </a>
                            @endif
                            <a href="{{ route('dashboard.pelanggan.current-perbaikan-detail', $perbaikan->id) }}"
                                class="btn btn-secondary w-100"><i class="bi bi-eye me-2"></i>
                                Lihat Detail Perbaikan
                            </a>
                        </div>
                    </div>
                </div>
            @empty
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
