@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Form Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Form Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
                        <a class="btn btn-outline-primary mb-4" href="{{ route('perbaikan.create') }}">
                            <i class="ri-add-circle-line"></i>
                            Tambah
                        </a>
                        <table id="datatable"
                            class="display table table-hover table-bordered dt-responsive table-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>No Plat</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Nama Perbaikan</th>
                                    <th>Masuk</th>
                                    <th>Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
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
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('perbaikan.data-table') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'kode_unik',
                        name: 'kode_unik'
                    },
                    {
                        data: 'no_plat',
                        name: 'no_plat'
                    },
                    {
                        data: 'pelanggan',
                        name: 'pelanggan'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD MMMM YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'tgl_selesai',
                        name: 'tgl_selesai',
                        render: function(data) {
                            if (!data) {
                                return '-';
                            } else {
                                return moment(data).format('DD MMMM YYYY HH:mm:ss');
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let badge_bg = null;
                            switch (data) {
                                case 'Selesai':
                                    badge_bg = 'bg-success';
                                    break;
                                case 'Baru':
                                    badge_bg = 'bg-info';
                                    break;
                                case 'Antrian':
                                    badge_bg = 'bg-primary';
                                    break;
                                case 'Dalam Proses':
                                    badge_bg = 'bg-secondary';
                                    break;
                                case 'Menunggu Bayar':
                                    badge_bg = 'bg-warning';
                                    break;
                                default:
                                    badge_bg = 'bg-dark';
                                    break;
                            }
                            return '<span class="badge ' + badge_bg + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
            });
        });
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
