<?php

namespace App\Filament\Resources\ShipmentResource\Pages;

use App\Filament\Resources\ShipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShipment extends EditRecord
{
    protected static string $resource = ShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure new legs have proper sequence orders
        if (isset($data['legs']) && is_array($data['legs'])) {
            $existingSequences = collect($this->record->legs)->pluck('sequence_order')->toArray();
            $maxSequence = !empty($existingSequences) ? max($existingSequences) : 0;

            foreach ($data['legs'] as $index => &$leg) {
                if (empty($leg['sequence_order'])) {
                    $maxSequence++;
                    $leg['sequence_order'] = $maxSequence;
                }
            }
        }

        return $data;
    }
}
