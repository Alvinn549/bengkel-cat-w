@extends('dashboard.layouts.main')

@section('css')
    <style>
        .foto-profil-index {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-block;
            vertical-align: middle;
        }

        .foto-profil-index img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <div class="foto-profil-index">
                            @if (auth()->user()->pekerja->foto)
                                <img src="{{ asset('storage/' . auth()->user()->pekerja->foto) }}" class="img-fluid"
                                    alt="">
                            @else
                                <img src="{{ asset('assets/dashboard/img/man.png') }}" alt="Profile"
                                    class="rounded-circle">
                            @endif
                        </div>
                        <h2>{{ auth()->user()->pekerja->nama }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Tinjauan</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Ubah Profil
                                    Saya</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <h5 class="card-title">Detail Profil</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Nama</div>
                                    <div class="col-lg-9 col-md-8">{{ auth()->user()->pekerja->nama }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ auth()->user()->email }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">No Telp</div>
                                    <div class="col-lg-9 col-md-8">{{ auth()->user()->pekerja->no_telp }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Jenis Kelamin</div>
                                    <div class="col-lg-9 col-md-8">{{ auth()->user()->pekerja->jenis_k }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Alamat</div>
                                    <div class="col-lg-9 col-md-8">{{ auth()->user()->pekerja->alamat }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                <form class="" action="{{ route('profil.update', auth()->user()->id) }}"
                                    method="POST" id="form" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto
                                            Profil</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if (auth()->user()->pekerja->foto)
                                                <img src="{{ asset('storage/' . auth()->user()->pekerja->foto) }}"
                                                    class="preview-foto img-fluid rounded" alt="">
                                            @else
                                                <img class="preview-foto img-fluid rounded">
                                            @endif
                                            <div class="pt-2">
                                                <label for="foto" class="btn btn-primary btn-sm text-white"
                                                    title="Upload new profile image">
                                                    <i class="bi bi-upload"></i>
                                                    <input type="file" id="foto" name="foto"
                                                        onchange="previewFoto()" style="display:none;">
                                                    @error('foto')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputNama" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                        <div class="col-md-8 col-lg-9">

                                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                                name="nama" id="inputNama"
                                                value="{{ old('nama') ?? auth()->user()->pekerja->nama }}">
                                            @error('nama')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputEmail" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="inputEmail"
                                                value="{{ old('email') ?? auth()->user()->email }}">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputPassword" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="inputPassword">
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="d-block mb-2 text-warning">*Kosongkan jika tidak ingin mengganti
                                                password</small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inpuNoTelp" class="col-md-4 col-lg-3 col-form-label">No Telp</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="text"
                                                class="form-control @error('no_telp') is-invalid @enderror" name="no_telp"
                                                id="inpuNoTelp" pattern="\d+"
                                                oninput="this.value = formatNoTelp(this.value)" inputmode="numeric"
                                                value="{{ old('no_telp') ?? auth()->user()->pekerja->no_telp }}">
                                            @error('no_telp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputJenisKelamin" class="col-md-4 col-lg-3 col-form-label">Jenis
                                            Kelamin</label>
                                        <div class="col-md-8 col-lg-9">
                                            <select id="inputJenisKelamin" name="jenis_k"
                                                class="form-select @error('jenis_k') is-invalid @enderror">
                                                <option value="">Choose...</option>
                                                <option value="L"
                                                    {{ auth()->user()->pekerja->jenis_k == 'L' ? 'selected' : '' }}>Laki
                                                    Laki
                                                </option>
                                                <option value="P"
                                                    {{ auth()->user()->pekerja->jenis_k == 'P' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                            @error('jenis_k')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputAlamat" class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                                        <div class="col-md-8 col-lg-9">
                                            <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat" name="alamat"
                                                id="inputAlamat" style="height: 100px;">{{ old('alamat') ?? auth()->user()->pekerja->alamat }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" onclick="submit()">Submit</button>
                                </div>
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
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
