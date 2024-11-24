<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailOtp extends Notification
{
    use Queueable;

    protected $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email - Campigo')
            ->greeting('Halo!')
            ->line('Kode OTP untuk verifikasi email Anda adalah:')
            ->line($this->otp)
            ->line('Kode ini akan kadaluarsa dalam 10 menit.')
            ->line('Jika Anda tidak mendaftar di Campigo, abaikan email ini.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
