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
        'tgl_kembali'
    ];

    public function itemsOrders()
    {
        return $this->hasMany(ItemsOrder::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class);
    }
}