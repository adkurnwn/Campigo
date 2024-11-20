<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_sewa_id',
        'image_path'
    ];

    public function transaksiSewa()
    {
        return $this->belongsTo(TransaksiSewa::class);
    }
}
