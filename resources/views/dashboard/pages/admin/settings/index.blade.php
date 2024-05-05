@extends('dashboard.layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Settings</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengaturan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Modal Bantuan Map -->
    <div class="modal fade" id="modalBantuanMap" tabindex="-1" aria-labelledby="modalBantuanMapLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="label_warning">Bagaimana mendapatkan link Google map ?</h5>
                    <button type="button" class="btn-close"data-bs-target="#ubah-kontak-bengkel"
                        data-bs-toggle="modal"></button>
                </div>
                <div class="modal-body">
                    <p>langkah untuk mendapatkan link map dari google maps</p>
                    <li>Buka alamat <a href="https://www.google.com/maps" target="_blank"
                            class="text-decoration-none">google maps</a>.</li>
                    <li>Klik tempat yang akan disimpan.</li>
                    <li>Pilih berbagi/share.</li>
                    <li>Pilih tab embed a map.</li>
                    <li>Pilih size <strong>Small / Kecil</strong>.</li>
                    <li>Salin link yang disediakan lalu masukkan ke dalam form.</li>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pengaturan Landing Page</h5>

                        <div class="d-flex gap-2 justify-content-end">

                            <button type="button" class="btn btn-success" id="submitButton" onclick="confirmSubmit()"
                                style="display: none;">
                                <i class="bi bi-save me-1"></i>Submit
                            </button>
                            <button type="button" id="editButton" class="btn btn-primary">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </button>
                            <button type="button" id="editBatalButton" class="btn btn-secondary" style="display: none;">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Batal
                            </button>
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
                            <div class="row mb-3">
                                <label for="inputMap" class="col-sm-2 col-form-label">Lokasi Google</label>
                                <div class="col-sm-6">
                                    <input type="text" name="map_google"
                                        class="form-control @error('map_google') is-invalid @enderror" id="inputMap"
                                        value="{{ old('map_google') ?? $settings->map_google }}"
                                        placeholder="Masukkan link yang didapat dari google map">
                                    <button class="btn btn-outline-info mt-2" type="button" data-bs-toggle="modal"
                                        data-bs-target="#modalBantuanMap" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Bantuan" id="btnBantuanMap">
                                        <i class="ri-map-pin-2-line"></i>
                                    </button>
                                    @error('map_google')
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
                                <div class="col-sm-2">
                                    <input type="time" id="jam_operasional1" name="jam_operasional1"
                                        class="form-control  @error('jam_operasional1') is-invalid @enderror"
                                        value="{{ old('jam_operasional1') ?? $jam_operasional1 }}">
                                    @error('jam_operasional1')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <p class="text-center mt-2">------ s/d ------</p>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" id="jam_operasional2" name="jam_operasional2"
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
                                <label for="inputGallery" class="col-sm-2 col-form-label">Gallery</label>
                                <div class="col-sm-6">
                                    @forelse ($galleries as $gallery)
                                        <div class=" d-flex mb-2">
                                            <input type="hidden" name="old_foto[]" value="{{ $gallery->foto }}">
                                            @php
                                                $filename = explode('foto/', $gallery->foto);
                                                $old_foto = end($filename);
                                            @endphp
                                            <input readonly type="text" class="form-control"
                                                value="{{ $old_foto }}">

                                            <button disabled id="btnShowOldFoto{{ $gallery->id }}" type="button"
                                                class="btn btn-success ms-2"
                                                onclick="openImage('{{ asset('storage/' . $gallery->foto) }}')">
                                                <i class="ri-eye-line"></i>
                                            </button>

                                            <button disabled id="btnHapusOldFoto{{ $gallery->id }}" type="button"
                                                class="btn btn-danger ms-2" onclick="this.parentNode.remove()">
                                                <i class="ri-delete-bin-5-line"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-danger">Belum ada asset tersimpan!</p>
                                    @endforelse

                                    <div id="fileInputs">
                                        <!-- File input elements will be added here -->
                                    </div>
                                    <button style="display:none;" type="button" class="btn btn-outline-primary"
                                        id="addFileInput">
                                        <i class="ri-image-add-line"></i>
                                    </button>
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
        function openImage(imageUrl) {
            Swal.fire({
                imageUrl: imageUrl,
                imageWidth: 450,
                imageAlt: 'Foto Progres',
                showConfirmButton: false,
            });
        }

        function addFileInputRow() {
            const fileInputsContainer = document.getElementById('fileInputs');

            const fileInputContainer = `
                <div class="form-group d-flex mb-2">
                    <input type="file" class="form-control " name="foto[]">
                    <button type="button" class="btn btn-danger ms-2" onclick="this.parentNode.remove()">
                        <i class="ri-delete-bin-5-line"></i>
                    </button>
                </div>
            `;

            fileInputsContainer.insertAdjacentHTML('beforeend', fileInputContainer);
        }

        document.getElementById('addFileInput').addEventListener('click', addFileInputRow);
    </script>

    <script>
        const quill = new Quill('.quill-editor-default', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        ['bold', 'italic', 'underline', 'strike', 'link'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        [{
                            'direction': 'rtl'
                        }],
                        [{
                            'size': ['small', false, 'large', 'huge']
                        }],
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'align': []
                        }],
                        ['clean']
                    ],
                },
            },
        });

        quill.enable(false);

        const formInputs = document.querySelectorAll('#form input');
        const submitButton = document.getElementById('submitButton');
        const editButton = document.getElementById('editButton');
        const editBatalButton = document.getElementById('editBatalButton');
        const btnBantuanMap = document.getElementById('btnBantuanMap');
        const addFileInput = document.getElementById('addFileInput');

        formInputs.forEach(input => input.disabled = true);

        submitButton.style.display = 'none';
        editBatalButton.style.display = 'none';
        btnBantuanMap.disabled = true;


        document.getElementById('editButton').addEventListener('click', function() {
            formInputs.forEach(input => input.disabled = false);
            submitButton.style.display = 'block';
            btnBantuanMap.disabled = false;
            editButton.style.display = 'none';
            editBatalButton.style.display = 'block';
            addFileInput.style.display = 'block';

            @foreach ($galleries as $gallery)
                document.getElementById('btnHapusOldFoto{{ $gallery->id }}').disabled = false;
                document.getElementById('btnShowOldFoto{{ $gallery->id }}').disabled = false;
            @endforeach

            quill.enable(true);
        });

        document.getElementById('editBatalButton').addEventListener('click', function() {
            formInputs.forEach(input => input.disabled = true);
            submitButton.style.display = 'none';
            btnBantuanMap.disabled = true;
            editButton.style.display = 'block';
            editBatalButton.style.display = 'none';
            addFileInput.style.display = 'none';

            @foreach ($galleries as $gallery)
                document.getElementById('btnHapusOldFoto{{ $gallery->id }}').disabled = true;
                document.getElementById('btnShowOldFoto{{ $gallery->id }}').disabled = true;
            @endforeach

            quill.enable(false);
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
