@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Proses Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.admin.list-perbaikan-menunggu-bayar') }}">Perbaikan menunggu
                        bayar</a>
                </li>
                <li class="breadcrumb-item active">Proses Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="mb-4">
            <a href="{{ route('dashboard.admin.list-perbaikan-menunggu-bayar') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-go-back-line"></i>
            </a>
        </div>

        <!-- Modal Data Kendaraan -->
        <div class="modal fade" id="dataKendaraan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalLabel">Data Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                @if ($perbaikan->kendaraan->foto)
                                    <img src="{{ asset('storage/' . $perbaikan->kendaraan->foto) }}"
                                        class="img-fluid rounded" alt="">
                                @else
                                    <img src="{{ asset('assets/dashboard/img/hatchback.png') }}" alt="Default"
                                        class="img-fluid">
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>No Plat</th>
                                        <td>{{ $perbaikan->kendaraan->no_plat ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe</th>
                                        <td>{{ $perbaikan->kendaraan->tipe->nama_tipe ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Merek</th>
                                        <td>{{ $perbaikan->kendaraan->merek->nama_merek ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terdaftar Sejak</th>
                                        <td>{{ $perbaikan->kendaraan->created_at ?? '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title flex-grow-1">Data Perbaikan</h5>
                            <button type="button" class="btn btn-outline-primary" style="height: 38px"
                                data-bs-toggle="modal" data-bs-target="#dataKendaraan">
                                <i class="ri-car-line"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 d-flex justify-content-center">
                                @if ($perbaikan->foto)
                                    <img src="{{ asset('storage/' . $perbaikan->foto) }}" class="img-fluid rounded"
                                        alt="">
                                @else
                                    <img src="{{ asset('assets/dashboard/img/repair.png') }}" alt="Default"
                                        class="col-md-6 img-fluid">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Kode</th>
                                        <td>
                                            <span class="badge bg-secondary" style="font-size: 1rem;">
                                                {{ $perbaikan->kode_unik ?? '' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nama Perbaikan</th>
                                        <td>{{ $perbaikan->nama ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $perbaikan->keterangan ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Biaya</th>
                                        <td>Rp. {{ number_format($perbaikan->biaya, 2) ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            if ($perbaikan->durasi) {
                                                $durations = explode(' to ', $perbaikan->durasi);
                                                $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[0]);
                                                $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $durations[1]);

                                                $days = $startDate->diffInDays($endDate);
                                            }
                                        @endphp
                                        <th>Durasi</th>
                                        <td>
                                            @if ($perbaikan->durasi)
                                                {{ $days ?? '' }} Hari <br> {{ $perbaikan->durasi ?? '' }}
                                            @else
                                                {{ $perbaikan->durasi ?? '' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Masuk</th>
                                        <td>{{ $perbaikan->created_at ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Selesai</th>
                                        <td>{{ $perbaikan->tgl_selesai ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $badge_bg = null;
                                            $btn_color = null;

                                            switch ($perbaikan->status) {
                                                case 'Selesai':
                                                    $badge_bg = 'bg-success';
                                                    $btn_color = 'success';
                                                    break;
                                                case 'Baru':
                                                    $badge_bg = 'bg-info';
                                                    $btn_color = 'info';
                                                    break;
                                                case 'Antrian':
                                                    $badge_bg = 'bg-primary';
                                                    $btn_color = 'primary';
                                                    break;
                                                case 'Dalam Proses':
                                                    $badge_bg = 'bg-secondary';
                                                    $btn_color = 'secondary';
                                                    break;
                                                case 'Menunggu Bayar':
                                                    $badge_bg = 'bg-warning';
                                                    $btn_color = 'warning';
                                                    break;
                                                default:
                                                    $badge_bg = 'bg-dark';
                                                    $btn_color = 'dark';
                                                    break;
                                            }
                                        @endphp
                                        <th>Status</th>
                                        <td><span class="badge {{ $badge_bg }}"
                                                style="font-size: 1rem;">{{ $perbaikan->status ?? '' }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Transaksi</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Order Id</th>
                                        <td>{{ $perbaikan->transaksi->order_id ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th nowrap>Metode Pembayaran</th>
                                        <td>
                                            {{ $perbaikan->transaksi->chosen_payment ?? '' }} <br>
                                            @if ($perbaikan->transaksi->chosen_payment == null)
                                                <small class="text-danger">*Pelanggan belum memilih metode pembayaran
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>Rp. {{ number_format($perbaikan->transaksi->gross_amount) ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Transaksi</th>
                                        <td>
                                            {{ $perbaikan->transaksi->transaction_status ?? '' }}
                                            @if ($perbaikan->transaksi->transaction_status == null)
                                                <small class="text-danger">*Pelanggan belum memilih metode pembayaran
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if ($perbaikan->transaksi->chosen_payment == 'cash')
                            <form class="row g-3 justify-content-center"
                                action="{{ route('dashboard.admin.konfirmasi-pembayaran-cash') }}" method="POST"
                                id="form_konfirmasi_pembayaran" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="transaksi_id" value="{{ $perbaikan->transaksi->id }}">
                                <input type="hidden" name="perbaikan_id" value="{{ $perbaikan->id }}">

                                <div class="col-md-12" id="konfirmasiPembayaran">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" name="konfirmasi_sudah_bayar"
                                            id="konfirmasi_sudah_bayar">
                                        <label class="form-check-label" for="konfirmasi_sudah_bayar">Nyatakan pelanggan
                                            <strong>sudah bayar</strong></label>
                                    </div>
                                    @error('biaya')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary w-100" onclick="submitForm()">
                                            <i class="ri-save-line me-1"></i>Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Pelanggan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Nama Depan</th>
                                        <td>{{ $perbaikan->transaksi->first_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Belakang</th>
                                        <td>{{ $perbaikan->transaksi->last_name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $perbaikan->transaksi->email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $perbaikan->transaksi->phone ?? '' }}</td>
                                    </tr>
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
        function formatNumberInput(value) {
            let formatedValue = value.replace(/[^0-9]/g, '').slice(0, 8);
            formatedValue = formatedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return formatedValue;
        }

        function submitForm() {
            const konfirmasi_sudah_bayar = document.getElementById('konfirmasi_sudah_bayar');
            if (konfirmasi_sudah_bayar.checked) {
                Swal.fire({
                    title: 'Apakah anda yakin untuk menyelesaikan perbaikan ini?',
                    text: "Setelah dikonfirmasi, perbaikan dan transaksi akan dinyatakan selesai!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form_konfirmasi_pembayaran').submit()
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Anda harus melakukan konfirmasi pembayaran terlebih dahulu!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>
@endsection
