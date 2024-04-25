@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->
    <hr>
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="card overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">Recent Perbaikan</h5>

                        <table id="datatableCurrentPerbaikan"
                            class="display table table-hover table-bordered dt-responsive table-responsive"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Preview</th>
                                    <th>Nama Perbaikan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentPerbaikans as $key => $perbaikan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td nowrap><strong>{{ $perbaikan->kode_unik }}</strong></td>
                                        <td class="text-center">
                                            @if ($perbaikan->foto)
                                                <a href="javascript:void(0)">
                                                    <img src="{{ asset('storage/' . $perbaikan->foto) }}"
                                                        class="img-fluid rounded" alt=""
                                                        onclick="openImage('{{ asset('storage/' . $perbaikan->foto) }}')"
                                                        style="width: 100px;">
                                                </a>
                                            @else
                                                <a href="javascript:void(0)">
                                                    <img src="{{ asset('assets/dashboard/img/repair.png') }}"
                                                        class="img-fluid rounded" alt=""
                                                        onclick="openImage('{{ asset('assets/dashboard/img/repair.png') }}')"
                                                        style="width: 100px;">
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $perbaikan->nama }}</td>
                                        <td>
                                            @php
                                                $badge_bg = null;

                                                if ($perbaikan->status == 'Selesai') {
                                                    $badge_bg = 'bg-success';
                                                    $btn_bg = 'btn-success';
                                                } elseif ($perbaikan->status == 'Dalam Proses') {
                                                    $badge_bg = 'bg-info';
                                                    $btn_bg = 'btn-info';
                                                } elseif ($perbaikan->status == 'Ditunda') {
                                                    $badge_bg = 'bg-secondary';
                                                    $btn_bg = 'btn-secondary';
                                                } elseif ($perbaikan->status == 'Dibatalkan') {
                                                    $badge_bg = 'bg-warning';
                                                    $btn_bg = 'btn-warning';
                                                } elseif ($perbaikan->status == 'Tidak Dapat Diperbaiki') {
                                                    $badge_bg = 'bg-danger';
                                                    $btn_bg = 'btn-danger';
                                                }
                                            @endphp
                                            <span class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <a class="btn {{ $btn_bg }} btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="bottom" title="Proses Perbaikan"
                                                href="{{ route('dashboard.pekerja.proses-perbaikan', $perbaikan->id) }}">
                                                <i class="ri-bank-card-fill"></i> Proses
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Left side columns -->
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatableCurrentPerbaikan').DataTable({
                responsive: true
            });
        })

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
