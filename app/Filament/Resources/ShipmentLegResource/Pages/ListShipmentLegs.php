<?php

namespace App\Filament\Resources\ShipmentLegResource\Pages;

use App\Filament\Resources\ShipmentLegResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShipmentLegs extends ListRecords
{
    protected static string $resource = ShipmentLegResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
