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
                            <th nowrap>No Telp</th>
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
            <div class="mb-4">
                <a href="{{ route('kendaraan.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Kendaraan</h5>

                        <div class="row">
                            <div class="col-md-6 d-flex justify-content-center">
                                @if ($kendaraan->foto)
                                    <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded"
                                        alt="">
                                @else
                                    <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                        class="col-md-6 img-fluid">
                                @endif
                            </div>
                            <div class="col-md-6 align-self-center">
                                <table class="table">
                                    <tr>
                                        <th>Pemilik</th>
                                        <td>
                                            {{ $kendaraan->pelanggan->nama ?? '' }}
                                            <button type="button" data-bs-toggle="modal" class="btn-show-pemilik"
                                                data-bs-target="#dataPemilik">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th nowrap>No Plat</th>
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
                                    <tr>
                                        <th nowrap>Terdaftar Sejak</th>
                                        <td>{{ $kendaraan->created_at }}</td>
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
                        <a class="btn btn-outline-primary mb-4"
                            href="{{ route('perbaikan.create', ['idKendaraan' => $kendaraan->id]) }}">
                            <i class="ri-add-circle-line"></i>
                            Tambah
                        </a>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="datatable" class="table table-bordered ">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Masuk</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kendaraan->perbaikans->sortByDesc('created_at') as $perbaikan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $perbaikan->kode_unik }}</td>
                                                <td>{{ $perbaikan->nama }}</td>
                                                <td>{{ $perbaikan->created_at ?? '-' }}</td>
                                                <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
                                                <td>
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
                                                        } else {
                                                            $badge_bg = 'text-dark';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badge_bg }}">
                                                        {{ $perbaikan->status ?? '-' }}
                                                    </span>
                                                </td>
                                                <td nowrap>
                                                    <a class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Lihat"
                                                        href="{{ route('perbaikan.show', $perbaikan->id) }}">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Edit"
                                                        href="{{ route('perbaikan.edit', $perbaikan->id) }}">
                                                        <i class="ri-edit-2-line"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Hapus" href="javascript:"
                                                        onclick="deleteData({{ $perbaikan->id }})">
                                                        <i class="ri-delete-bin-5-line"></i>
                                                    </a>

                                                    <form class="d-none" id="formDelete-{{ $perbaikan->id }}"
                                                        action="{{ route('perbaikan.destroy', $perbaikan->id) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
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
                responsive: true,
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
