<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kendaraan_id',
        'kode_unik',
        'nama',
        'keterangan',
        'foto',
        'biaya',
        'durasi',
        'tgl_selesai',
        'status',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function progres()
    {
        return $this->hasMany(Progres::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }
}
