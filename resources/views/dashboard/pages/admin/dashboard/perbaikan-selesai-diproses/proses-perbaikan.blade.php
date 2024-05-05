@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Proses perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard.admin.list-perbaikan-selesai-di-proses') }}">List Perbaikan Selesai Di
                        Proses Pekerja</a>
                </li>
                <li class="breadcrumb-item active">Proses perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="mb-4">
            <a href="{{ route('dashboard.admin.list-perbaikan-selesai-di-proses') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-go-back-line"></i>
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan</h5>
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
                                        <td>Rp. {{ number_format($perbaikan->biaya, 2) ?? '-' }}</td>
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
                                                {{ $days ?? '-' }} Hari <br> {{ $perbaikan->durasi ?? '-' }}
                                            @else
                                                {{ $perbaikan->durasi ?? '-' }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Masuk</th>
                                        <td>{{ $perbaikan->created_at ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Selesai</th>
                                        <td>{{ $perbaikan->tgl_selesai ?? '-' }}</td>
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
                                                style="font-size: 1rem;">{{ $perbaikan->status ?? '-' }}</span>
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
                        <h5 class="card-title">Biaya</h5>
                        <form class="row g-3 justify-content-center"
                            action="{{ route('dashboard.admin.proses-perbaikan-selesai-put', $perbaikan) }}" method="POST"
                            id="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12" id="inputBiayaContainer">
                                <label for="inputBiaya" class="form-label">Biaya</label>
                                <input type="text" class="form-control @error('biaya') is-invalid @enderror"
                                    name="biaya" id="inputBiaya" placeholder="Rp. "
                                    value="{{ old('biaya') ?? $perbaikan->biaya }}" pattern="\d+"
                                    oninput="this.value = formatNumberInput(this.value)" inputmode="numeric">
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
            // Check if the biaya field is empty
            const biayaValue = document.getElementById('inputBiaya').value.trim();
            if (biayaValue === '') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Biaya tidak boleh kosong!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah anda yakin untuk menyelesaikan perbaikan ini ?',
                text: "Setelah dikonfirmasi, maka status perbaikan akan diupdate dan transaksi akan di buat !",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form').submit()
                }
            });
        }
    </script>
@endsection
