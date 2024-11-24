<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\TransaksiSewa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemsOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_sewa_id',
        'barang_id',
        'quantity',
        'price_per_day',
        'subtotal',
        'kondisi',
        'denda',
    ];

    protected $casts = [
        'kondisi' => 'string'
    ];

    public const KONDISI_OPTIONS = [
        'normal' => 'Normal',
        'rusak ringan' => 'Rusak Ringan',
        'rusak berat' => 'Rusak Berat',
        'hilang' => 'Hilang'
    ];

    public function transaksiSewa()
    {
        return $this->belongsTo(TransaksiSewa::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}