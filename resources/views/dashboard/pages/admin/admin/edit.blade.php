@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Edit Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Admin</h5>
                        <form class="row g-3" action="{{ route('admin.update', $admin) }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-12">
                                <label for="inputNama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    name="nama" id="inputNama" value="{{ old('nama') ?? $admin->nama }}">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="inputEmail" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="inputEmail" value="{{ old('email') ?? $admin->user->email }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="inputPassword" class="form-label">Password</label>
                                <small class="d-block mb-2 text-warning">*Kosongkan jika tidak ingin mengganti
                                    password</small>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="inputPassword">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="inpuNoTelp" class="form-label">No Telp</label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                                    name="no_telp" id="inpuNoTelp" pattern="\d+"
                                    oninput="this.value = formatNoTelp(this.value)" inputmode="numeric"
                                    value="{{ old('no_telp') ?? $admin->no_telp }}">
                                @error('no_telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="inputJenisKelamin" class="form-label">Jenis Kelamin</label>
                                <select id="inputJenisKelamin" name="jenis_k"
                                    class="form-select @error('jenis_k') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    <option value="L" {{ $admin->jenis_k == 'L' ? 'selected' : '' }}>Laki Laki
                                    </option>
                                    <option value="P" {{ $admin->jenis_k == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('jenis_k')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="inputAlamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat" name="alamat"
                                    id="inputAlamat" style="height: 100px;">{{ old('alamat') ?? $admin->alamat }}</textarea>
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
                            <div class="col-md-12" id="container-preview">
                                <div class="row justify-content-center">
                                    <div class="col-6">
                                        @if ($admin->foto)
                                            <img src="{{ asset('storage/' . $admin->foto) }}"
                                                class="preview-foto img-fluid rounded" alt="">
                                        @else
                                            <img class="preview-foto img-fluid rounded">
                                        @endif
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
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Kembali</a></a>
        </div>
    </section>
@endsection

@section('js')
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
