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
        'updated_at',
        'payment_method',
        'metode_bayar_lunas',
        'image_path_lunas',
        'total_denda',
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

    public function scopeExpiredUnpaid($query)
    {
        return $query->where('status', 'belum bayar')
            ->where('created_at', '<=', now()->subHour());
            //->where('created_at', '<=', now()->subMinutes(2));
    }

    public function scopeLateReturns($query)
    {
        return $query->where('status', 'berlangsung')
            ->where('tgl_kembali', '<', now())
            ->where(function($q) {
                $q->whereTime('created_at', '<=', '22:00:00')
                    ->orWhere('created_at', '<', now()->format('Y-m-d'));
            });
    }

    public function calculateLatePenalty()
    {
        if ($this->status !== 'berlangsung') {
            return 0;
        }

        $now = now();
        $returnDate = \Carbon\Carbon::parse($this->tgl_kembali);
        
        // If it's not past 22:00 today, no penalty
        if ($now->format('Y-m-d') === $returnDate->format('Y-m-d') && $now->format('H:i:s') <= '22:00:00') {
            return 0;
        }

        // Calculate days late
        $daysLate = $returnDate->startOfDay()->diffInDays($now->startOfDay());
        if ($daysLate <= 0) return 0;

        // Return penalty amount (one day's rental fee per day late)
        return $this->total_harga * $daysLate;
    }
}