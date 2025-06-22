<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use App\Models\Shipment;
use Filament\Resources\Pages\CreateRecord;

class CreateShipment extends CreateRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure we have a unique reference ID
        if (empty($data['reference_id'])) {
            $data['reference_id'] = $this->generateUniqueReferenceId();
        }

        // Ensure legs have proper sequence orders
        if (isset($data['legs']) && is_array($data['legs'])) {
            foreach ($data['legs'] as $index => &$leg) {
                if (empty($leg['sequence_order'])) {
                    $leg['sequence_order'] = $index + 1;
                }
            }
        }

        return $data;
    }

    private function generateUniqueReferenceId(): string
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $year = date('Y');
            $month = date('m');

            // Get the last shipment for this month to determine the next sequence
            $lastShipment = Shipment::where('reference_id', 'LIKE', "SH{$year}{$month}%")
                ->orderBy('reference_id', 'desc')
                ->first();

            if ($lastShipment) {
                // Extract the sequence number from the last reference ID
                $lastSequence = (int) substr($lastShipment->reference_id, 8);
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            // Format: SH + Year + Month + 4-digit sequence (e.g., SH2025010001)
            $referenceId = sprintf('SH%s%s%04d', $year, $month, $nextSequence);

            // Check if this reference ID already exists
            $exists = Shipment::where('reference_id', $referenceId)->exists();

            if (!$exists) {
                return $referenceId;
            }

            $attempt++;
            // If conflict, wait a tiny bit and try again
            usleep(1000); // 1ms delay

        } while ($attempt < $maxAttempts);

        // Fallback with timestamp if we can't generate unique ID
        return sprintf('SH%s%s%s', $year, $month, time());
    }
}
