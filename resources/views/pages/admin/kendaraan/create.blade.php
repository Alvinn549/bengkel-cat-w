@extends('layouts.main')

@section('css')
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Tambah Kendaraan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kendaraan.index') }}">Kendaraan</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Kendaraan</h5>
                        <form class="row g-3" action="{{ route('kendaraan.store') }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="inputPelangganId" class="form-label">Pelanggan</label>
                                <select id="inputPelangganId" name="pelanggan_id"
                                    class="form-select select2 @error('pelanggan_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @foreach ($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id }}"
                                            {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                            {{ $pelanggan->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelanggan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="inputMerek" class="form-label">Merek</label>
                                <input type="text" class="form-control @error('merek') is-invalid @enderror"
                                    name="merek" id="inputMerek" value="{{ old('merek') }}">
                                @error('merek')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputnoPlat" class="form-label">No Plat</label>
                                <input type="text" class="form-control @error('no_plat') is-invalid @enderror"
                                    name="no_plat" id="inputnoPlat" maxlength="12" value="{{ old('no_plat') }}">
                                @error('no_plat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputTipe" class="form-label">Tipe</label>
                                <select id="inputTipe" name="tipe"
                                    class="form-select @error('tipe') is-invalid @enderror">
                                    <option>Choose...</option>
                                    <option value="Mobil" {{ old('tipe') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="Sepeda Motor" {{ old('tipe') == 'sepeda_motor' ? 'selected' : '' }}>
                                        Sepeda
                                        Motor</option>
                                    <option value="Truk" {{ old('tipe') == 'truk' ? 'selected' : '' }}>Truk</option>
                                    <option value="Bus" {{ old('tipe') == 'bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="Mobil Listrik" {{ old('tipe') == 'mobil_listrik' ? 'selected' : '' }}>
                                        Mobil Listrik</option>
                                    <option value="Motor Listrik" {{ old('tipe') == 'motor_listrik' ? 'selected' : '' }}>
                                        Sepeda Motor Listrik</option>
                                    <option value="Lainnya" {{ old('tipe') == 'lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputKeterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Masukkan keterangan kendaraan jika perlu." name="keterangan" id="inputKeterangan"
                                    style="height: 100px;">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
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
                            <div class="col-md-12 d-none" id="container-preview">
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        <img class="preview-foto img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary" onclick="submit()">Submit</button>
            <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Kembali</a></a>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ url('assets/vendor/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function previewFoto() {
            const image = document.querySelector('#foto');
            const imageContainer = document.querySelector('#container-preview');
            const imgPreview = document.querySelector('.preview-foto');

            imageContainer.classList.remove('d-none');
            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function submit() {
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
    </script>
@endsection
