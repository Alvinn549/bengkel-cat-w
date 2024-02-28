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
        'foto',
        'keterangan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function perbaikans()
    {
        return $this->hasMany(Perbaikan::class);
    }
}
