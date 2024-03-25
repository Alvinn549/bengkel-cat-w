@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Transaksi Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Transaksi Saya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transaksi Berlangsung</h5>
                        <table id="datatable"
                            class="display table table-hover table-bordered dt-responsive table-responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Jumlah</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->order_id }}</td>
                                        <td>Rp. {{ number_format($transaksi->gross_amount) }}</td>
                                        <td>{{ $transaksi->email }}</td>
                                        <td>{{ $transaksi->phone }}</td>
                                        <td>
                                            @php
                                                $badge_bg = null;

                                                if ($transaksi->status == 'Selesai') {
                                                    $badge_bg = 'bg-success';
                                                } elseif ($transaksi->status == 'Dalam Proses') {
                                                    $badge_bg = 'bg-info';
                                                } elseif ($transaksi->status == 'Ditunda') {
                                                    $badge_bg = 'bg-secondary';
                                                } elseif ($transaksi->status == 'Dibatalkan') {
                                                    $badge_bg = 'bg-warning';
                                                } elseif ($transaksi->status == 'Tidak Dapat Diperbaiki') {
                                                    $badge_bg = 'bg-danger';
                                                } else {
                                                    $badge_bg = 'bg-warning';
                                                }
                                            @endphp
                                            <span class="badge {{ $badge_bg }}" style="width: 100%">
                                                {{ $transaksi->transaction_status ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Proses" href="#">
                                                <i class="ri-bank-card-fill"></i> Proses
                                            </a>
                                            <a class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Lihat"
                                                href="{{ route('dashboard.my-transaksi-detail', $transaksi->id) }}">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
