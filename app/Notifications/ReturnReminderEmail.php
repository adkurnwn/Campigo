<?php

namespace App\Notifications;

use App\Models\TransaksiSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReturnReminderEmail extends Notification
{
    use Queueable;

    protected $transaksi;

    public function __construct(TransaksiSewa $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Pengingat Pengembalian Barang - Campigo')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ini adalah pengingat bahwa barang yang Anda sewa harus dikembalikan hari ini sebelum pukul 22:00.')
            ->line('Detail Transaksi:')
            ->line('ID Transaksi: #' . $this->transaksi->id)
            ->line('Tanggal Pinjam: ' . $this->transaksi->tgl_pinjam)
            ->line('Tanggal Kembali: ' . $this->transaksi->tgl_kembali)
            ->line('Total Subtotal: Rp ' . number_format($this->transaksi->total_harga, 0, ',', '.'))
            ->line('Barang yang dipinjam:');

        foreach ($this->transaksi->itemsOrders as $item) {
            $message->line("- {$item->barang->nama} ({$item->barang->merk}) ({$item->quantity} unit)");
        }

        return $message
            ->line('Mohon kembalikan barang tepat waktu untuk menghindari denda.')
            ->line('Terima kasih telah menggunakan layanan Campigo.');
    }
}