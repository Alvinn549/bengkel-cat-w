@extends('dashboard.layouts.main')

@section('css')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $clienKey }}"></script>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Lihat Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{ route('dashboard.pelanggan.current-perbaikan', auth()->user()->pelanggan->id) }}">Perbaikan
                        Saat Ini
                    </a>
                </li>
                <li class="breadcrumb-item active">Show</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="mb-4">
                <a href="{{ route('dashboard.pelanggan.current-perbaikan', auth()->user()->pelanggan->id) }}"
                    class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Kembali">
                    <i class="ri-arrow-go-back-line"></i>
                </a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="panel-body mt-4">
                            <div class="clearfix">
                                <div class="float-start">
                                    @php
                                        $profil_bengkel = \App\Models\Settings::first();
                                    @endphp
                                    <h3>{{ $profil_bengkel->master_nama ?? '' }}</h3>
                                </div>
                                <div class="float-end">
                                    <h4>Invoice # <br>
                                        <strong>{{ $transaksi->order_id }}</strong>
                                    </h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-start mt-3">
                                        <address>
                                            <strong>{{ $profil_bengkel->master_nama ?? '' }}</strong><br>
                                            {{ $profil_bengkel->alamat ?? '' }}<br>
                                            <abbr title="Phone">P:</abbr> {{ $profil_bengkel->telepon ?? '' }}
                                        </address>
                                    </div>
                                    <div class="float-end mt-3">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Order Date</th>
                                                <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Order Status</th>
                                                <td>: {{ $transaksi->transaction_status ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Method</th>
                                                <td>: {{ $transaksi->chosen_payment ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>By</th>
                                                <td>: {{ $transaksi->pay_by ?? '' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 ">
                                    <table class="table table-borderless" style="width: 100%">
                                        <tr>
                                            <th>Nama</th>
                                            <td>: {{ $transaksi->pelanggan->nama ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>No Telp</th>
                                            <td>: {{ $transaksi->pelanggan->no_telp ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>: {{ $transaksi->email ?? '' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4 ">
                                    <table class="table table-borderless" style="width: 100%">
                                        <tr>
                                            <th>No Plat</th>
                                            <td>: {{ $transaksi->perbaikan->kendaraan->no_plat ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Merek</th>
                                            <td>: {{ $transaksi->perbaikan->kendaraan->merek->nama_merek ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipe</th>
                                            <td>: {{ $transaksi->perbaikan->kendaraan->tipe->nama_tipe ?? '' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <table class="table table-borderless" style="width: 100%">
                                        <tr>
                                            <th>Nama Perbaikan</th>
                                            <td>: {{ $transaksi->perbaikan->nama ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Selesai Pada</th>
                                            <td>:
                                                {{ \Carbon\Carbon::parse($transaksi->perbaikan->tgl_selesai)->format('d-m-Y') ?? '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Unit Cost</th>
                                            <td>: Rp. {{ number_format($transaksi->gross_amount, 2) ?? '' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-end">
                                <div class="col-md-6" style="font-size: 12px">
                                    <div class="clearfix mt-4">
                                        <h5 class="small text-dark fw-normal">SYARAT DAN KETENTUAN PEMBAYARAN</h5>

                                        <small>
                                            Pembayaran harus dilakukan sesuai dengan waktu yang ditentukan.
                                            Pembayaran dapat dilakukan menggunakan kartu kredit, transfer bank,
                                            atau metode pembayaran lainnya yang didukung. Jika pembayaran tidak tepat
                                            waktu, maka transaksi harus dilakukan ulang.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6" style="text-align: right">
                                    <h2>Total</h2>
                                    <h4><strong>Rp. {{ number_format($transaksi->gross_amount, 2) ?? '' }}</strong></h4>
                                </div>
                            </div>
                            <hr>
                            @if ($transaksi->transaction_status == 'settlement')
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <span class="badge bg-success" style="font-size: 16px">Selesai</span>
                                    </div>
                                </div>
                            @else
                                @if ($transaksi->chosen_payment == null)
                                    {{-- Belum Memilih chosen_payment --}}
                                    <div class="d-print-none">
                                        <div class="float-end">
                                            <form id="formChosePayment" method="POST">
                                                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                                                <input type="hidden" name="pelanggan_id"
                                                    value="{{ $transaksi->pelanggan->id }}">

                                                <select class="form-select" name="chosen_payment" id="chosen_payment"
                                                    style="width: 100%">
                                                    <option value="">Pilih Metode Pembayaran</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="virtual">Virtual</option>
                                                </select>

                                                <button type="button" id="submitBtnFormChosePayment"
                                                    class="btn btn-primary w-100 mt-3">
                                                    <i class="bi bi-credit-card me-2"></i>Pilih
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif ($transaksi->chosen_payment == 'cash')
                                    <div class="d-print-none">
                                        <div class="float-end">
                                            <span class="badge bg-warning" style="font-size: 16px">Menunggu Konfirmasi
                                                Admin</span>
                                        </div>
                                    </div>
                                @elseif ($transaksi->chosen_payment == 'virtual')
                                    <div class="d-print-none">
                                        <div class="float-end">
                                            <form id="formTransaksiPayment" method="POST">
                                                @csrf
                                                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                                                <input type="hidden" name="snap_token"
                                                    value="{{ $transaksi->snap_token }}">

                                                <button type="button" id="submitFormTransaksiPayment"
                                                    class="btn btn-success w-100 mt-3">
                                                    <i class="bi bi-credit-card me-2"></i>Bayar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @if ($transaksi->chosen_payment == null)
        <script>
            document.getElementById('submitBtnFormChosePayment').addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin memilih metode pembayaran ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var paymentType = document.getElementById('chosen_payment').value;
                        if (paymentType === 'cash') {
                            var formData = new FormData(document.getElementById('formChosePayment'));

                            $.ajax({
                                url: '{{ route('dashboard.pelanggan.proses-my-transaksi-cash') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: formData,
                                processData: false,
                                contentType: false,

                                success: function(data) {
                                    if (data.status === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: data.message,
                                            icon: 'success',
                                            allowOutsideClick: false,
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.reload();
                                            }
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    var errorMessage = 'Something went wrong';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    Swal.fire('Error', errorMessage, 'error');
                                }
                            });
                        } else if (paymentType === 'virtual') {
                            var formData = new FormData(document.getElementById('formChosePayment'));

                            $.ajax({
                                url: '{{ route('dashboard.pelanggan.proses-my-transaksi-virtual') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                    if (data.status === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: data.message,
                                            icon: 'success',
                                            allowOutsideClick: false,
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Ok',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.reload();
                                            }
                                        });
                                    } else if (data.status === 'error') {
                                        Swal.fire('Error', data.message, 'error');
                                    }
                                },
                                error: function(xhr) {
                                    var errorMessage = 'Something went wrong';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    Swal.fire('Error', errorMessage, 'error');
                                }
                            });
                        } else {
                            Swal.fire('Error', 'Pilih metode pembayaran terlebih dahulu', 'error');
                        }
                    }
                });
            });
        </script>
    @else
        <script>
            document.getElementById('submitFormTransaksiPayment').addEventListener('click', function() {
                var snapToken = document.querySelector('input[name="snap_token"]').value;
                console.log(snapToken);
                snap.pay(snapToken);
            });
        </script>
    @endif
@endsection
