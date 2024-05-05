<div class="modal fade" id="modalEdit{{ $tipe->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="{{ $tipe->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCreateLabel">Edit Tipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="{{ route('tipe.update', $tipe) }}" method="POST" id="form">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">
                        <label for="inpuNamaTipe" class="form-label">Nama</label>
                        <input required type="text" class="form-control @error('nama_tipe') is-invalid @enderror"
                            name="nama_tipe" id="inpuNamaTipe" value="{{ old('nama_tipe') ?? $tipe->nama_tipe }}">
                        @error('nama_tipe')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
