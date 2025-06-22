<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestShipments extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Shipment::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference_id')
                    ->label(__('general.shipment.reference_id'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_mode')
                    ->label(__('general.shipment.shipping_mode'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'SEA' => __('general.shipment.sea'),
                        'AIR' => __('general.shipment.air'),
                        'LAND' => __('general.shipment.land'),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('general.shipment.status'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'PENDING' => __('general.shipment.statuses.pending'),
                        'IN_TRANSIT' => __('general.shipment.statuses.in_transit'),
                        'AT_WAREHOUSE' => __('general.shipment.statuses.at_warehouse'),
                        'DELIVERED' => __('general.shipment.statuses.delivered'),
                        'CANCELLED' => __('general.shipment.statuses.cancelled'),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'IN_TRANSIT' => 'info',
                        'AT_WAREHOUSE' => 'primary',
                        'DELIVERED' => 'success',
                        'CANCELLED' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipper.customer_name')
                    ->label(__('general.shipment.shipper'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('consignee.customer_name')
                    ->label(__('general.shipment.consignee'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimated_departure')
                    ->label(__('general.shipment.estimated_departure'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimated_arrival')
                    ->label(__('general.shipment.estimated_arrival'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Shipment $record): string => route('filament.admin.resources.shipments.edit', $record))
                    ->icon('heroicon-o-eye')
                    ->label(__('general.widgets.view')),

                Tables\Actions\Action::make('view_legs')
                    ->url(fn (Shipment $record): string => route('filament.admin.resources.shipment-legs.index') . '?tableFilters[shipment_id][value]=' . $record->id)
                    ->icon('heroicon-o-arrows-right-left')
                    ->label(__('general.widgets.view_legs'))
                    ->tooltip(__('general.widgets.view_shipment_legs')),
            ])
            ->heading(__('general.widgets.latest_shipments'));
    }
}
