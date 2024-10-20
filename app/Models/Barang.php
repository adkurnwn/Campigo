<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'id',
        'kode',
        'nama',
        'merk',
        'stok',
        'harga',
        'berat',
        'deskripsi',
        'image',
        'kategori',
        'updated_at',
    ];

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class);
    }
}
