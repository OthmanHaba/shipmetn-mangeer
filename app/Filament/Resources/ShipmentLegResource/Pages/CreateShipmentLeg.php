<?php

namespace App\Filament\Resources\ShipmentLegResource\Pages;

use App\Filament\Resources\ShipmentLegResource;
use App\Models\ShipmentLeg;
use Filament\Resources\Pages\CreateRecord;

class CreateShipmentLeg extends CreateRecord
{
    protected static string $resource = ShipmentLegResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure we have a sequence order
        if (empty($data['sequence_order']) && !empty($data['shipment_id'])) {
            $data['sequence_order'] = $this->getNextSequenceOrder($data['shipment_id']);
        }

        return $data;
    }

    private function getNextSequenceOrder(int $shipmentId): int
    {
        $maxSequence = ShipmentLeg::where('shipment_id', $shipmentId)
            ->max('sequence_order');

        return $maxSequence ? $maxSequence + 1 : 1;
    }
}
