@extends('layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Lihat Kendaraan</h1>
        <nav class="d-flex justify-content-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kendaraan.index') }}">Kendaraan</a></li>
                <li class="breadcrumb-item active">Show</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="modal fade" id="dataPemilik" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Pemilik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $kendaraan->pelanggan->nama ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>No Telp</th>
                            <td>{{ $kendaraan->pelanggan->no_telp ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $kendaraan->pelanggan->alamat ?? '' }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div><!-- End Vertically centered Modal-->

    <section class="section">
        <div class="row">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('kendaraan.index') }}" class="btn">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-md-6 d-flex justify-content-center">
                                @if ($kendaraan->foto)
                                    <img src="{{ asset('storage') }}/{{ $kendaraan->foto }}" class="img-fluid rounded"
                                        alt="">
                                @else
                                    <i class="ri-car-line ri-9x"></i>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th>Pemilik</th>
                                        <td>{{ $kendaraan->pelanggan->nama ?? '' }}

                                            <button type="button" class="btn" data-bs-toggle="modal"
                                                data-bs-target="#dataPemilik">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>No Plat</th>
                                        <td>{{ $kendaraan->no_plat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merek</th>
                                        <td>{{ $kendaraan->merek }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe</th>
                                        <td>{{ $kendaraan->tipe }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $kendaraan->keterangan }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="datatable" class="table table-bordered dt-responsive table-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pemilik</th>
                                            <th>No Plat</th>
                                            <th>Merek</th>
                                            <th>Tipe</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

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
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
            });
        })
    </script>

    <script>
        function deleteData(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDelete-' + id).submit()
                }
            });
        }
    </script>
@endsection
