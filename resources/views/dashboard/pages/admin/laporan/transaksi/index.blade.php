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
                                    <select class="form-select" name="status" id="status">
                                        <option value="">Pilih Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="settlement">Settlement</option>
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
            window.location.href = "{{ route('laporan.transaksi') }}";
        }
    </script>
@endsection
