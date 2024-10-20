<?php

namespace App\Filament\Resources\TransaksiSewaResource\Pages;

use App\Filament\Resources\TransaksiSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiSewa extends EditRecord
{
    protected static string $resource = TransaksiSewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
