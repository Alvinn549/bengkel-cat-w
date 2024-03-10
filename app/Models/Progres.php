<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progres extends Model
{
    use HasFactory;

    protected $fillable = [
        'perbaikan_id',
        'keterangan',
        'foto',
        'status',
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class);
    }
}
