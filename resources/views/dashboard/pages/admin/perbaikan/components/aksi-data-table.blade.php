<div class="d-flex gap-1">
    <a class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat"
        href="{{ route('perbaikan.show', $id) }}">
        <i class="ri-eye-line"></i>
    </a>

    <a class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
        href="{{ route('perbaikan.edit', $id) }}">
        <i class="ri-edit-2-line"></i>
    </a>

    @if (in_array($status, ['Baru', 'Antrian']))
        <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"
            href="javascript:" onclick="deleteData({{ $id }})">
            <i class="ri-delete-bin-5-line"></i>
        </a>

        <form class="d-none" id="formDelete-{{ $id }}" action="{{ route('perbaikan.destroy', $id) }}"
            method="POST">
            @method('DELETE')
            @csrf
        </form>
    @endif
</div>
