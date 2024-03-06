@extends('layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Transaksi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Transaksi</h5>
                        <a class="btn btn-outline-primary mb-4" href="{{ route('transaksi.create') }}">
                            <i class="ri-add-circle-line"></i>
                            Tambah
                        </a>

                        <!-- Table with stripped rows -->
                        <table id="datatable" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>gross_amount</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->order_id }}</td>
                                        <td>Rp. {{ number_format($transaksi->gross_amount) }}</td>
                                        <td nowrap>
                                            {{-- <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit"
                                                href="{{ route('transaksi.edit', $transaksi->id) }}">
                                                <i class="ri-edit-2-line"></i>
                                            </a> --}}
                                            <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Hapus" href="javascript:"
                                                onclick="deleteData({{ $transaksi->id }})">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </a>

                                            <form class="d-none" id="formDelete-{{ $transaksi->id }}"
                                                action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST">
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
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                lengthMenu: [
                    [5, 10, 25, -1],
                    [5, 10, 25, "All"]
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
