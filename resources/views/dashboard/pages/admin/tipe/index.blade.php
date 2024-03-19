@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Tipe</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Tipe</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    {{-- Modal Create --}}
    @include('dashboard.pages.admin.tipe.components.modal_create')
    {{-- End Modal Create --}}

    {{-- Modal Edit --}}
    @foreach ($tipes as $tipe)
        @include('dashboard.pages.admin.tipe.components.modal_edit', ['tipe' => $tipe])
    @endforeach
    {{-- End Modal Edit --}}

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Tipe</h5>
                        <button type="button" class="btn btn-outline-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#modalCreate">
                            <i class="ri-add-circle-line"></i>
                            Tambah
                        </button>

                        <table id="datatable"
                            class="display table table-hover table-bordered dt-responsive table-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tipes as $tipe)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tipe->nama_tipe }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $tipe->id }}">
                                                <i class="ri-edit-2-line"></i>
                                            </button>
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Hapus" href="javascript:"
                                                onclick="deleteData({{ $tipe->id }})">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>
                                            <form class="d-none" id="formDelete-{{ $tipe->id }}"
                                                action="{{ route('tipe.destroy', $tipe->id) }}" method="POST">
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
