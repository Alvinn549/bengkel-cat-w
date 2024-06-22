@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('perbaikan.index') }}">Form Perbaikan</a></li>
                <li class="breadcrumb-item active">Tambah Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Perbaikan <small class="text-danger">*</small></h5>
                        <form class="row g-3" action="{{ route('perbaikan.store') }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="kendaraan_id" class="form-label">Kendaraan</label>
                                <select id="kendaraan_id" name="kendaraan_id"
                                    class="form-select @error('kendaraan_id') is-invalid @enderror" data-width="100%">
                                    <option value="">Choose...</option>
                                    @foreach ($pelanggans as $pelanggan)
                                        <optgroup label="Kendaraan Milik {{ $pelanggan->nama }}">
                                            @foreach ($pelanggan->kendaraans as $kendaraan)
                                                <option value="{{ $kendaraan->id }}"
                                                    {{ old('kendaraan_id') == $kendaraan->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $kendaraan->no_plat }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('kendaraan_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="inputNama" class="form-label">Nama Perbaikan</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="inputNama" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputKeterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan perbaikan."
                                    name="keterangan" id="inputKeterangan" style="height: 125px;">{{ old('keterangan') }}</textarea>
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
                                    placeholder="Masukkan perkiraan durasi" value="{{ old('durasi') }}">
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
            <a href="{{ route('perbaikan.index') }}" class="btn btn-secondary">Kembali</a></a>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var durasi = "{{ old('durasi') }}";
            var defaultDate = durasi.split(" to ");


            $('#durasi').flatpickr({
                mode: "range",
                minDate: "today",
                dateFormat: "d-m-Y",
                defaultDate: defaultDate,
            });
        });

        $(document).ready(function() {
            $('#kendaraan_id').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>

    <script>
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
