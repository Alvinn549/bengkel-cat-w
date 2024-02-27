<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'no_plat',
        'merek',
        'tipe',
        'fotos',
        'keterangan',
    ];

    public function pelanggans()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
