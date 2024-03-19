<div class="row justify-content-center mt-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">Lengkapi Data Anda Terlebih Dahulu</h5>

                <form class="row g-3" action="{{ route('profil.update', auth()->user()->id) }}" method="POST"
                    id="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label for="inputNama" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama"
                            id="inputNama" value="{{ old('nama') ?? auth()->user()->pelanggan->nama }}">
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            id="inputEmail" value="{{ old('email') ?? auth()->user()->email }}">
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
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" name="no_telp"
                            id="inpuNoTelp" pattern="\d+" oninput="this.value = formatNoTelp(this.value)"
                            inputmode="numeric" value="{{ old('no_telp') ?? auth()->user()->pelanggan->no_telp }}">
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
                            <option value="L" {{ auth()->user()->pelanggan->jenis_k == 'L' ? 'selected' : '' }}>
                                Laki Laki
                            </option>
                            <option value="P" {{ auth()->user()->pelanggan->jenis_k == 'P' ? 'selected' : '' }}>
                                Perempuan
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
                            id="inputAlamat" style="height: 100px;">{{ old('alamat') ?? auth()->user()->pelanggan->alamat }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="inputFoto" class="form-label">Foto</label>
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto"
                            name="foto" onchange="previewFoto()">
                        @error('foto')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12" id="container-preview">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                @if (auth()->user()->pelanggan->foto)
                                    <img src="{{ asset('storage/' . auth()->user()->pelanggan->foto) }}"
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
</div>
