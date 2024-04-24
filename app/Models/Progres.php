<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    protected $fillable = [
        'perbaikan_id',
        'pekerja_id',
        'keterangan',
        'foto',
        'is_selesai',
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class);
    }

    public function pekerja()
    {
        return $this->belongsTo(Pekerja::class);
    }
}
