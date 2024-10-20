<?php

namespace App\Filament\Resources\TransaksiSewaResource\Pages;

use App\Filament\Resources\TransaksiSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiSewas extends ListRecords
{
    protected static string $resource = TransaksiSewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
