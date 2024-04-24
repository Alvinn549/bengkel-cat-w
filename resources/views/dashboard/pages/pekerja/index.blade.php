@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard Pekerja</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Recent Perbaikan -->
                    <div class="col-12">
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
                                                    <span
                                                        class="badge {{ $badge_bg }}">{{ $perbaikan->status ?? '-' }}</span>
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
                    </div><!-- End Recent Perbaikan -->
                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                        <div class="activity">

                            <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a>
                                    beatae
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">56 min</div>
                                <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                <div class="activity-content">
                                    Voluptatem blanditiis blanditiis eveniet
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                <div class="activity-content">
                                    Voluptates corrupti molestias voluptatem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">1 day</div>
                                <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                <div class="activity-content">
                                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati
                                        voluptatem</a> tempore
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 days</div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">
                                    Est sit eum reiciendis exercitationem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">4 weeks</div>
                                <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                <div class="activity-content">
                                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                </div>
                            </div><!-- End activity item-->

                        </div>

                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side columns -->
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
