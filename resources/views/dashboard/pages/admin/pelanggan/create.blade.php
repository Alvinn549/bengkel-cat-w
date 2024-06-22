@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Pelanggan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Pelanggan</h5>
                        <form class="row g-3" action="{{ route('pelanggan.store') }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="inputNama" class="form-label">Nama<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="inputNama" value="{{ old('nama') }}">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="inputEmail" class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="inputEmail" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-warning mt-1">
                                    <ul>
                                        <li>Email harus valid</li>
                                        <li>Email harus unik</li>
                                    </ul>
                                </small>
                            </div>

                            <div class="col-md-12">
                                <label for="inputPassword" class="form-label">Password<span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="inputPassword">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-warning mt-1">
                                    <ul>
                                        <li>Password harus minimal 8 karakter</li>
                                    </ul>
                                </small>
                            </div>
                            <div class="col-md-8">
                                <label for="inpuNoTelp" class="form-label">No Telp<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                                    name="no_telp" id="inpuNoTelp" pattern="\d+"
                                    oninput="this.value = formatNoTelp(this.value)" inputmode="numeric"
                                    value="{{ old('no_telp') }}">
                                @error('no_telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-warning mt-1">
                                    <ul>
                                        <li>No Telp minimal 11 Digit</li>
                                        <li>No Telp maximal 16 Digit</li>
                                    </ul>
                                </small>
                            </div>
                            <div class="col-md-4">
                                <label for="inputJenisKelamin" class="form-label">Jenis Kelamin<span
                                        class="text-danger">*</span></label>
                                <select id="inputJenisKelamin" name="jenis_k"
                                    class="form-select @error('jenis_k') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    <option value="L" {{ old('jenis_k') == 'L' ? 'selected' : '' }}>Laki Laki</option>
                                    <option value="P" {{ old('jenis_k') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_k')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputAlamat" class="form-label">Alamat<span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat" name="alamat"
                                    id="inputAlamat" style="height: 100px;">{{ old('alamat') }}</textarea>
                                @error('alamat')
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
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a></a>
        </div>
    </section>
@endsection

@section('js')
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

        function formatNoTelp(value) {
            let formatedValue = value.replace(/[^0-9]/g, '').slice(0, 16);

            return formatedValue;
        }
    </script>
@endsection
