<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'perbaikan_id',
        'pelanggan_id',
        'order_id',
        'gross_amount',
        'payment_type',
        'transaction_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'midtrans_response',
    ];

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
