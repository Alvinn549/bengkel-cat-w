@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>merek</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">merek</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    {{-- Modal Create --}}
    @include('dashboard.pages.admin.merek.components.modal_create')
    {{-- End Modal Create --}}

    {{-- Modal Edit --}}
    @foreach ($mereks as $merek)
        <div class="modal fade" id="modalEdit{{ $merek->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="{{ $merek->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Edit Merek</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" action="{{ route('merek.update', $merek) }}" method="POST" id="form">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="inpuNamaMerek" class="form-label">Nama</label>
                                <input required type="text"
                                    class="form-control @error('nama_merek') is-invalid @enderror" name="nama_merek"
                                    id="inpuNamaMerek" value="{{ old('nama_merek') ?? $merek->nama_merek }}">
                                @error('nama_merek')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- End Modal Edit --}}

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Merek</h5>
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
                                @foreach ($mereks as $merek)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $merek->nama_merek }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $merek->id }}">
                                                <i class="ri-edit-2-line"></i>
                                            </button>
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Hapus" href="javascript:"
                                                onclick="deleteData({{ $merek->id }})">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>
                                            <form class="d-none" id="formDelete-{{ $merek->id }}"
                                                action="{{ route('merek.destroy', $merek->id) }}" method="POST">
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
