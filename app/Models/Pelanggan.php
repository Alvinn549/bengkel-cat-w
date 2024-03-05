<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'no_telp',
        'alamat',
        'jenis_k',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
