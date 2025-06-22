<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentLegResource\Pages;
use App\Models\ShipmentLeg;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentLegResource extends Resource
{
    protected static ?string $model = ShipmentLeg::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Shipments';

    public static function getNavigationLabel(): string
    {
        return __('general.shipment_leg.title_plural');
    }

    public static function getModelLabel(): string
    {
        return __('general.shipment_leg.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.shipment_leg.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('shipment_id')
                            ->label(__('general.shipment.title'))
                            ->relationship('shipment', 'reference_id')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $nextSequence = self::getNextSequenceOrder($state);
                                    $set('sequence_order', $nextSequence);
                                }
                            })
                            ->required(),

                        Forms\Components\TextInput::make('sequence_order')
                            ->label(__('general.shipment_leg.sequence_order'))
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->default(function (callable $get) {
                                $shipmentId = $get('shipment_id');
                                if (!$shipmentId) {
                                    return 1;
                                }
                                return self::getNextSequenceOrder($shipmentId);
                            }),

                        Forms\Components\Select::make('origin_node_id')
                            ->label(__('general.shipment_leg.from_node'))
                            ->relationship('originNode', 'node_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('destination_node_id')
                            ->label(__('general.shipment_leg.to_node'))
                            ->relationship('destinationNode', 'node_name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label(__('general.shipment_leg.status'))
                            ->options([
                                'PENDING' => __('general.shipment_leg.statuses.pending'),
                                'IN_PROGRESS' => __('general.shipment_leg.statuses.in_progress'),
                                'COMPLETED' => __('general.shipment_leg.statuses.completed'),
                            ])
                            ->required(),

                        Forms\Components\DateTimePicker::make('departure_timestamp')
                            ->label(__('general.shipment_leg.departure_timestamp')),

                        Forms\Components\DateTimePicker::make('arrival_timestamp')
                            ->label(__('general.shipment_leg.arrival_timestamp')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shipment.reference_id')
                    ->label(__('general.shipment.reference_id'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sequence_order')
                    ->label(__('general.shipment_leg.sequence_order'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('originNode.node_name')
                    ->label(__('general.shipment_leg.from_node'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('destinationNode.node_name')
                    ->label(__('general.shipment_leg.to_node'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label(__('general.shipment_leg.status'))
                    ->options([
                        'PENDING' => __('general.shipment_leg.statuses.pending'),
                        'IN_PROGRESS' => __('general.shipment_leg.statuses.in_progress'),
                        'COMPLETED' => __('general.shipment_leg.statuses.completed'),
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('departure_timestamp')
                    ->label(__('general.shipment_leg.departure_timestamp'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('arrival_timestamp')
                    ->label(__('general.shipment_leg.arrival_timestamp'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('shipment_id')
                    ->label(__('general.shipment.title'))
                    ->relationship('shipment', 'reference_id')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShipmentLegs::route('/'),
            'create' => Pages\CreateShipmentLeg::route('/create'),
            'edit' => Pages\EditShipmentLeg::route('/{record}/edit'),
        ];
    }

    private static function getNextSequenceOrder(int $shipmentId): int
    {
        $maxSequence = ShipmentLeg::where('shipment_id', $shipmentId)
            ->max('sequence_order');

        return $maxSequence ? $maxSequence + 1 : 1;
    }
}
