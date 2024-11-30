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
        'reminded',
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

    public function scopeDueSoon($query)
    {
        $targetTime = now()->setTime(22, 0, 0);
        $threeHoursBefore = $targetTime->copy()->subHours(3);
        
        return $query->where('status', 'berlangsung')
            ->whereDate('tgl_kembali', now())
            ->where('reminded', false);
    }

    public function scopeShouldBeCancelled($query)
    {
        return $query->where('status', 'pembayaran terkonfirmasi')
            ->where(function($q) {
                $q->whereDate('tgl_kembali', '<', now())
                    ->orWhere(function($q2) {
                        $q2->whereDate('tgl_kembali', '=', now())
                            ->whereRaw('TIME(NOW()) > ?', ['22:00:00']);
                    });
           });
    }

    public function calculateLatePenalty()
    {
        if ($this->status !== 'berlangsung') {
            return 0;
        }

        $now = now();
        $returnDate = \Carbon\Carbon::parse($this->tgl_kembali);
        
        if ($returnDate->isSameDay($now) && $now->format('H:i:s') > '22:00:00') {
            return $this->total_harga;
        }

        $daysLate = $returnDate->endOfDay()->lt($now) ? 
            $returnDate->startOfDay()->diffInDays($now->startOfDay()) : 0;
            
        return $daysLate * $this->total_harga;
    }
}