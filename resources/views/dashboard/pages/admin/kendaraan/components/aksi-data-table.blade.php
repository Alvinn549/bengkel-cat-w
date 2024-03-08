 <a class="btn btn-success btn-sm me-1 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat"
     href="{{ route('kendaraan.show', $id) }}">
     <i class="ri-eye-line"></i>
 </a>
 <a class="btn btn-primary btn-sm me-1 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
     href="{{ route('kendaraan.edit', $id) }}">
     <i class="ri-edit-2-line"></i>
 </a>
 <a class="btn btn-danger btn-sm me-1 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"
     href="javascript:" onclick="deleteData({{ $id }})">
     <i class="ri-delete-bin-5-line"></i>
 </a>
