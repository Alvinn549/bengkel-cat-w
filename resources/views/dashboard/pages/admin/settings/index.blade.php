@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Settings</h5>

                        <div class="d-flex gap-2 justify-content-end">

                            <button type="button" class="btn btn-success" id="submitButton" onclick="confirmSubmit()"
                                style="display: none;">Submit</button>
                            <button type="button" id="editButton" class="btn btn-primary">Edit</button>
                            <button type="button" id="editBatalButton" class="btn btn-secondary"
                                style="display: none;">Batal</button>
                        </div>

                        <form class="mt-3" action="{{ route('settings.store') }}" method="POST" id="form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputMasterNama" class="col-sm-2 col-form-label">Master Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" name="master_nama"
                                        class="form-control @error('master_nama') is-invalid @enderror" id="inputMasterNama"
                                        value="{{ old('master_nama') ?? $settings->master_nama }}">
                                    @error('master_nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputDeskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                                <div class="col-sm-10">
                                    <div class="quill-editor-default" id="deskripsi" style="height: 120px;">
                                        {!! old('deskripsi') ?? $settings->deskripsi !!}
                                    </div>
                                    <textarea name="deskripsi" style="display:none" id="hiddenDeskripsi"></textarea>
                                    @error('alamat')
                                        <small>
                                            <div class="text-danger">{{ $message }}</div>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputAlamat" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-6">
                                    <input type="text" name="alamat"
                                        class="form-control @error('alamat') is-invalid @enderror" id="inputAlamat"
                                        value="{{ old('alamat') ?? $settings->alamat }}">
                                    @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 d-flex">
                                <label for="inputJamOperasional" class="col-sm-2 col-form-label">Jam Operasional</label>
                                @php
                                    $jam_operasional = explode(' to ', $settings->jam_operasional);
                                    $jam_operasional1 = $jam_operasional[0];
                                    $jam_operasional2 = $jam_operasional[1];
                                @endphp
                                <div class="col-sm-2" style="width: 218px;">
                                    <input type="text" id="jam_operasional1" name="jam_operasional1"
                                        class="form-control  @error('jam_operasional1') is-invalid @enderror"
                                        value="{{ old('jam_operasional1') ?? $jam_operasional1 }}">
                                    @error('jam_operasional1')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-1" style="width: 50px;">
                                    <p class="text-center mt-1">s/d</p>
                                </div>
                                <div class="col-sm-2" style="width: 218px;">
                                    <input type="text" id="jam_operasional2" name="jam_operasional2"
                                        class="form-control  @error('jam_operasional2') is-invalid @enderror"
                                        value="{{ old('jam_operasional2') ?? $jam_operasional2 }}">
                                    @error('jam_operasional2')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputHero" class="col-sm-2 col-form-label">Hero</label>
                                <div class="col-sm-6">
                                    <input class="form-control @error('hero') is-invalid @enderror" type="file"
                                        id="hero" name="hero" onchange="previewFoto()">

                                    <div class="row mt-3">
                                        <div class="col-sm-12">
                                            @if ($settings->hero)
                                                <img src="{{ asset('storage/' . $settings->hero) }}"
                                                    class="preview-foto img-fluid rounded" alt="">
                                            @else
                                                <img class="preview-foto img-fluid rounded">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputTelepon" class="col-sm-2 col-form-label">Telepon</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        name="telepon" id="inpuNoTelp" pattern="\d+"
                                        oninput="this.value = formatNoTelp(this.value)" inputmode="numeric"
                                        value="{{ old('telepon') ?? $settings->telepon }}">
                                    @error('telepon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" name="email"
                                        class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                        value="{{ old('email') ?? $settings->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputFacebook" class="col-sm-2 col-form-label">Facebook</label>
                                <div class="col-sm-6">
                                    <input type="url" name="facebook"
                                        class="form-control @error('facebook') is-invalid @enderror" id="inputFacebook"
                                        value="{{ old('facebook') ?? $settings->facebook }}">
                                    @error('facebook')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputInstagram" class="col-sm-2 col-form-label">Instagram</label>
                                <div class="col-sm-6">
                                    <input type="url" name="instagram"
                                        class="form-control @error('instagram') is-invalid @enderror" id="inputInstagram"
                                        value="{{ old('instagram') ?? $settings->instagram }}">
                                    @error('instagram')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputWhatsapp" class="col-sm-2 col-form-label">Whatsapp</label>
                                <div class="col-sm-6">
                                    <input type="text" name="whatsapp"
                                        class="form-control @error('whatsapp') is-invalid @enderror" id="inputWhatsapp"
                                        value="{{ old('whatsapp') ?? $settings->whatsapp }}">
                                    @error('whatsapp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
        const quill = new Quill('.quill-editor-default', {
            theme: 'snow'
        });

        quill.enable(false);

        const formInputs = document.querySelectorAll('#form input');
        const submitButton = document.getElementById('submitButton');
        const editButton = document.getElementById('editButton');
        const editBatalButton = document.getElementById('editBatalButton');

        formInputs.forEach(input => input.disabled = true);

        submitButton.style.display = 'none';
        editBatalButton.style.display = 'none';

        document.getElementById('editButton').addEventListener('click', function() {
            formInputs.forEach(input => input.disabled = false);
            submitButton.style.display = 'block';
            editButton.style.display = 'none';
            editBatalButton.style.display = 'block';

            quill.enable(true);
        });

        document.getElementById('editBatalButton').addEventListener('click', function() {
            formInputs.forEach(input => input.disabled = true);
            submitButton.style.display = 'none';
            editButton.style.display = 'block';
            editBatalButton.style.display = 'none';

            quill.enable(false);
        });

        $(document).ready(function() {
            $('#jam_operasional1').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",

            });

            $('#jam_operasional2').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",

            });
        });

        function previewFoto() {
            const image = document.querySelector('#hero');
            const imgPreview = document.querySelector('.preview-foto');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function confirmSubmit() {
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
                    const hiddenDeskripsi = document.getElementById('hiddenDeskripsi');
                    hiddenDeskripsi.value = quill.root.innerHTML;

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
