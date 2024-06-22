@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Edit Perbaikan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('perbaikan.index') }}">Form Perbaikan</a></li>
                <li class="breadcrumb-item active">Edit Perbaikan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <form class="row g-3 justify-content-center" action="{{ route('perbaikan.update', $perbaikan) }}" method="POST"
            id="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-lg-12">
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
                                <div class="row justify-content-center">
                                    @if ($perbaikan->foto)
                                        <div class="col-6">
                                            <img src="{{ asset('storage/' . $perbaikan->foto) }}"
                                                class="preview-foto img-fluid rounded" alt="">
                                        </div>
                                    @else
                                        <div class="col-6">
                                            <img class="preview-foto img-fluid rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
        <div class="text-center">
            <button type="button" class="btn btn-primary" onclick="submit_form()">Submit</button>
            <a href="{{ route('perbaikan.index') }}" class="btn btn-secondary">Kembali</a></a>
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
    </script>

    <script>
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

        function submit_form() {
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
