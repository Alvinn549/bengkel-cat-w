@extends('dashboard.layouts.main')

@section('css')
    <style>
        .stat_count {
            overflow: auto;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Laporan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Laporan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Filter -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title"><i class="bi bi-filter me-2"></i>Filter</h1>
                        <div class="col-md-12">
                            <form method="get" class="row g-3" id="formFilter">
                                <div class="col-md-6">
                                    <select class="form-select" name="pelanggan" id="pelanggan">
                                        <option value="">All</option>
                                        @foreach ($pelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}"
                                                {{ request('pelanggan') == $pelanggan->id ? 'selected' : '' }}>
                                                {{ $pelanggan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" name="status" id="status">
                                        <option value="">All</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="settlement"
                                            {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="durasi" name="durasi" class="form-control "
                                        placeholder="Masukkan durasi pencarian" value="">
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search me-2"></i>
                                            Cari
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- End Filter -->
            <!-- Info -->
            <div class="col-lg-12">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Income</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Rp. {{ number_format($totalIncome, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Potensial Income</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ri-line-chart-line"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>Rp. {{ number_format($potentialIncome, 2) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Info -->
            <!-- Data -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title"><i class="bi bi-filter me-2"></i>Data Transaksi</h1>

                        <table id="datatable"
                            class="display table table-hover table-bordered dt-responsive table-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Jumlah</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>Status</th>
                                    <th>Masuk</th>
                                    <th>Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->order_id }}</td>
                                        <td>{{ $transaksi->gross_amount }}</td>
                                        <td>{{ $transaksi->email }}</td>
                                        <td>{{ $transaksi->phone }}</td>
                                        <td>
                                            @if ($transaksi->transaction_status == 'pending')
                                                <span class="badge bg-warning">{{ $transaksi->transaction_status }}</span>
                                            @elseif($transaksi->transaction_status == 'settlement')
                                                <span class="badge bg-success">{{ $transaksi->transaction_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaksi->created_at }}</td>
                                        <td>{{ $transaksi->perbaikan->tgl_selesai ?? '-' }}</td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- End Data -->
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var durasi = "{{ request('durasi') }}";
            var defaultDate = durasi.split(" to ");

            $('#durasi').flatpickr({
                mode: "range",
                dateFormat: "d-m-Y",
                defaultDate: defaultDate,
            });

            $('#pelanggan').select2({
                theme: 'bootstrap-5'
            });

            $('#datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'colvis', 'copy', 'csv', 'excel', 'print',
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ]
            });
        });

        function resetForm() {
            document.getElementById("formFilter").reset();
            window.location.href = "{{ route('laporan.transaksi') }}";
        }
    </script>
@endsection
