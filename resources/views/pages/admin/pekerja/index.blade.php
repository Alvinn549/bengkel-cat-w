@extends('layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Pekerja</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pekerja</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Pekerja</h5>
                        <a class="btn btn-outline-primary" href="{{ route('pekerja.create') }}">
                            <i class="ri-add-circle-line"></i>
                            Tambah
                        </a>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>Alamat</th>
                                    <th>Jenis K</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pekerjas as $pekerja)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pekerja->nama }}</td>
                                        <td>{{ $pekerja->no_telp }}</td>
                                        <td>{{ $pekerja->alamat }}</td>
                                        <td>{{ $pekerja->jenis_k }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('pekerja.edit', $pekerja->id) }}">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="javascript:"
                                                onclick="deleteData({{ $pekerja->id }})">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>

                                            <form class="d-none" id="formDelete-{{ $pekerja->id }}"
                                                action="{{ route('pekerja.destroy', $pekerja->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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