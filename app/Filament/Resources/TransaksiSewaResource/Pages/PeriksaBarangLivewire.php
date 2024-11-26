<?php

namespace App\Filament\Resources\TransaksiSewaResource\Pages;

use App\Filament\Resources\TransaksiSewaResource;
use App\Models\TransaksiSewa;
use Filament\Resources\Pages\Page;
use App\Models\ItemsOrder;
use Livewire\Component;

class PeriksaBarangLivewire extends Page
{
    protected static string $resource = TransaksiSewaResource::class;
    
    protected static string $view = 'filament.resources.transaksi-sewa.periksa-barang';
    
    public TransaksiSewa $record;
    public $kondisiKembali = [];

    public function mount($record): void
    {
        if (is_string($record)) {
            $this->record = TransaksiSewa::findOrFail($record);
        } else {
            $this->record = $record;
        }

        foreach ($this->record->itemsOrders as $item) {
            $this->kondisiKembali[$item->id] = $item->kondisi_kembali ?? 'normal';
        }
    }

    public function updateKondisi()
    {
        $totalDenda = 0;
        foreach ($this->kondisiKembali as $itemId => $kondisi) {
            $item = ItemsOrder::find($itemId);
            if ($item) {
                $denda = $this->calculateDenda($kondisi, $item->subtotal);
                $item->update([
                    'kondisi_kembali' => $kondisi,
                    'denda' => $denda
                ]);
                $totalDenda += $denda;
            }
        }

        // Update transaction status and total_denda
        $this->record->update([
            'status' => 'pelunasan',
            'total_denda' => $totalDenda
        ]);

        return redirect()->to(TransaksiSewaResource::getUrl());
    }

    public function hitungDenda($itemId)
    {
        $item = ItemsOrder::find($itemId);
        if (!$item || !isset($this->kondisiKembali[$itemId])) {
            return 0;
        }
        
        return $this->calculateDenda($this->kondisiKembali[$itemId], $item->subtotal);
    }

    public function getTotalDendaProperty()
    {
        $total = 0;
        foreach ($this->record->itemsOrders as $item) {
            $total += $this->hitungDenda($item->id);
        }
        return $total;
    }

    private function calculateDenda($kondisi, $price_per_day)
    {
        return match($kondisi) {
            'rusak ringan' => $price_per_day * 0.25,
            'rusak berat' => $price_per_day,
            'hilang' => $price_per_day * 5,
            default => 0,
        };
    }
}