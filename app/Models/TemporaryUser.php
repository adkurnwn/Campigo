<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'otp',
        'otp_expires_at'
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime'
    ];

    public function isOtpValid()
    {
        return $this->otp_expires_at->isFuture();
    }
}