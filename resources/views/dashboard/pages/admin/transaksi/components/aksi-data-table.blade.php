 <a class="btn btn-success btn-sm me-1 mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat"
     href="{{ route('transaksi.show', $id) }}">
     <i class="ri-eye-line"></i>
 </a>
 <a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus" href="javascript:"
     onclick="deleteData({{ $id }})">
     <i class="ri-delete-bin-5-line"></i>
 </a>
