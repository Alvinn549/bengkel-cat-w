@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Edit Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kendaraan.index') }}">Kendaraan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kendaraan.show', $perbaikan->kendaraan_id) }}">Show</a></li>
                <li class="breadcrumb-item active">Edit Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <form class="row g-3" action="{{ route('perbaikan.update', $perbaikan) }}" method="POST" id="form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan <small class="text-danger">*</small></h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="inputNama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="inputNama" value="{{ old('nama') ?? $perbaikan->nama }}">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputKeterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan perbaikan."
                                    name="keterangan" id="inputKeterangan" style="height: 125px;">{{ old('keterangan') ?? $perbaikan->keterangan }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="inputDurasi" class="form-label">Durasi</label>
                                <input type="text" id="durasi" name="durasi"
                                    class="form-control  @error('durasi') is-invalid @enderror"
                                    placeholder="{{ $perbaikan->durasi ?? 'Masukkan perkiraan durasi' }}"
                                    value="{{ old('durasi') }}">
                                @error('durasi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="inputFoto" class="form-label">Foto</label>
                                <input class="form-control @error('foto') is-invalid @enderror" type="file"
                                    id="foto" name="foto" onchange="previewFoto()">
                                @error('foto')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                @if ($perbaikan->foto)
                                    <img src="{{ asset('storage/' . $perbaikan->foto) }}"
                                        class="preview-foto img-fluid rounded" alt="">
                                @else
                                    <img class="preview-foto img-fluid rounded">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Status</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="inputStatus" class="form-label">Status</label>
                                <select id="inputStatus" name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    <option value="Selesai" {{ $perbaikan->status == 'Selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="Dalam Proses"
                                        {{ $perbaikan->status == 'Dalam Proses' ? 'selected' : '' }}>
                                        Dalam Proses</option>
                                    <option value="Ditunda" {{ $perbaikan->status == 'Ditunda' ? 'selected' : '' }}>Ditunda
                                    </option>
                                    <option value="Dibatalkan" {{ $perbaikan->status == 'Dibatalkan' ? 'selected' : '' }}>
                                        Dibatalkan</option>
                                    <option value="Tidak Dapat Diperbaiki"
                                        {{ $perbaikan->status == 'Tidak Dapat Diperbaiki' ? 'selected' : '' }}>
                                        Tidak Dapat Diperbaiki</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12" id="inputBiayaContainer" style="display: none">
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
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="text-center">
            <button type="submit" class="btn btn-primary" onclick="submit()">Submit</button>
            <a href="{{ route('kendaraan.show', $perbaikan->kendaraan_id) }}" class="btn btn-secondary">Kembali</a></a>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var durasi = "{{ $perbaikan->durasi }}";
            var defaultDate = durasi.split(" to ");


            $('#durasi').flatpickr({
                mode: "range",
                dateFormat: "d-m-Y",
                defaultDate: defaultDate,
            });
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        function formatNumberInput(value) {
            let formatedValue = value.replace(/[^0-9]/g, '').slice(0, 8);

            formatedValue = formatedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return formatedValue;
        }

        function previewFoto() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.preview-foto');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var inputStatus = document.getElementById('inputStatus');
            var inputBiayaContainer = document.getElementById('inputBiayaContainer');
            var inputBiaya = document.getElementById('inputBiaya');

            function toggleFields() {
                if (inputStatus.value === 'Selesai') {
                    inputBiayaContainer.style.display = 'block';
                    inputBiaya.readOnly = false;
                } else {
                    inputBiayaContainer.style.display = 'none';
                    inputBiaya.readOnly = true;
                    inputBiaya.value = '';
                }
            }

            toggleFields();

            inputStatus.addEventListener('change', toggleFields);
        });

        function submit() {
            var inputStatus = document.getElementById('inputStatus');
            var inputBiaya = document.getElementById('inputBiaya');

            if (inputStatus.value === 'Selesai' && inputBiaya.value.trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Biaya harus diisi jika status Selesai!',
                });
            } else {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data akan disimpan !",
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
        }
    </script>
@endsection
