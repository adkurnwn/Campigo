<?php

namespace App\Models;

use App\Models\ItemsOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiSewa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'total_harga',
        'metode_bayar',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
    ];

    public function itemsOrders()
    {
        return $this->hasMany(ItemsOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    public function jaminanKtp()
    {
        return $this->hasOne(JaminanKtp::class);
    }

    // Remove or comment out the pelanggan relationship since we'll use user instead
    // public function pelanggan()
    // {
    //     return $this->belongsTo(User::class);
    // }
}