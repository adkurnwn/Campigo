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
        'kapasitas',
        'kategori',
        'count_disewa',
        'updated_at',
    ];

    protected static function booted()
    {
        static::retrieved(function ($model) {
            $media = $model->getFirstMedia();
            $model->image = $media ? $media->id . '/' . $media->file_name : null;
        });
    }

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class);
    }

    public function decrement($column, $amount = 1, array $extra = [])
    {
        // Ensure stock doesn't go below 0
        if ($column === 'stok') {
            if ($this->stok < $amount) {
                throw new \Exception("Stok tidak mencukupi untuk {$this->nama}");
            }
        }
        return parent::decrement($column, $amount, $extra);
    }
}
