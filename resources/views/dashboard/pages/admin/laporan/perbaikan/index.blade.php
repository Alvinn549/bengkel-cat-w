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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title"><i class="bi bi-filter me-2"></i>Filter</h1>
                        <div class="col-md-12">
                            <form method="get" class="row g-3" id="formFilter">
                                <div class="col-md-5">
                                    @php
                                        $status = [
                                            'Baru',
                                            'Antrian',
                                            'Dalam Proses',
                                            'Proses Selesai',
                                            'Menunggu Bayar',
                                            'Selesai',
                                        ];
                                    @endphp
                                    <select class="form-select" name="status" id="status">
                                        <option value="">Pilih Status</option>
                                        @foreach ($status as $item)
                                            <option value="{{ $item }}"
                                                {{ request('status') == $item ? 'selected' : '' }}>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
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
            </div>
            <div class="col-lg-12">
                <div class="row justify-content-center">
                    @if ($average_done_in_days != null)
                        <div class="col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Rata-rata Durasi Selesai</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $average_done_in_days }} Hari</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Perbaikan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-tools"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalPerbaikans }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card info-card sales-card">
                            <div class="card-body" style="height: 110px;">
                                <div class="stat_count">
                                    <table class="table mt-3 table-borderless text-center">
                                        <thead>
                                            <tr>
                                                <th>Baru</th>
                                                <th>Antrian</th>
                                                <th>Dalam Proses</th>
                                                <th>Proses Selesai</th>
                                                <th>Menunggu Bayar</th>
                                                <th>Selesai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $statusCounts['Baru'] ?? 0 }}</td>
                                                <td>{{ $statusCounts['Antrian'] ?? 0 }}</td>
                                                <td>{{ $statusCounts['Dalam Proses'] ?? 0 }}</td>
                                                <td>{{ $statusCounts['Proses Selesai'] ?? 0 }}</td>
                                                <td>{{ $statusCounts['Menunggu Bayar'] ?? 0 }}</td>
                                                <td>{{ $statusCounts['Selesai'] ?? 0 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h1 class="card-title">Data Perbaikan</h1>
                        <table id="datatable"
                            class="display table table-hover table-bordered dt-responsive table-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Preview</th>
                                    <th>Kode Perbaikan</th>
                                    <th>Nama Perbaikan</th>
                                    <th>Es. Durasi</th>
                                    <th>Status</th>
                                    <th>Selesai Pada</th>
                                    <th>Terdaftar Sejak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($perbaikans as $perbaikan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/' . $perbaikan->foto) }}" alt=""
                                                class="img-fluid rounded"
                                                style="max-width:100px; max-height:100px; cursor:pointer;"
                                                onclick="openImage('{{ asset('storage/' . $perbaikan->foto) }}')">
                                        </td>
                                        <td>{{ $perbaikan->kode_unik }}</td>
                                        <td>{{ $perbaikan->nama }}</td>
                                        <td class="text-center">
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
                                            {{ $days }} Hari <br>
                                            <small class="text-warning">{{ $perbaikan->durasi }}</small>
                                        </td>
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
                                            <span class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
                                        </td>
                                        <td>{{ $perbaikan->tgl_selesai }}</td>
                                        <td>{{ $perbaikan->created_at }}</td>
                                    </tr>
                                @empty
                                @endforelse
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
            var durasi = "{{ request('durasi') }}";
            var defaultDate = durasi.split(" to ");

            $('#durasi').flatpickr({
                mode: "range",
                dateFormat: "d-m-Y",
                defaultDate: defaultDate,
            });

            $('#datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'colvis', 'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

        function resetForm() {
            document.getElementById("formFilter").reset();
            window.location.href = "{{ route('laporan.perbaikan') }}";
        }

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
