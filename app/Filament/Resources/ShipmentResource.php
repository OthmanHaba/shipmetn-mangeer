<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipmentResource\Pages;
use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Shipments';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.shipments');
    }

    public static function getModelLabel(): string
    {
        return __('general.shipment.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.shipment.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('general.shipment.title'))
                    ->schema([
                        Forms\Components\TextInput::make('reference_id')
                            ->label(__('general.shipment.reference_id'))
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(50)
                            ->default(fn () => self::generateReferenceId())
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('shipping_mode')
                            ->label(__('general.shipment.shipping_mode'))
                            ->options([
                                'SEA' => __('general.shipment.sea'),
                                'AIR' => __('general.shipment.air'),
                                'LAND' => __('general.shipment.land'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label(__('general.shipment.status'))
                            ->options([
                                'PENDING' => __('general.shipment.statuses.pending'),
                                'IN_TRANSIT' => __('general.shipment.statuses.in_transit'),
                                'AT_WAREHOUSE' => __('general.shipment.statuses.at_warehouse'),
                                'DELIVERED' => __('general.shipment.statuses.delivered'),
                                'CANCELLED' => __('general.shipment.statuses.cancelled'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('shipper_customer_id')
                            ->label(__('general.shipment.shipper'))
                            ->relationship('shipper', 'customer_name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label(__('general.customer.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label(__('general.customer.email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('general.customer.phone'))
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\Textarea::make('address')
                                    ->label(__('general.customer.address'))
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('country')
                                    ->label(__('general.node.country'))
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return \App\Filament\Resources\NodeResource::getCountriesFromJson($search);
                                    })
                                    ->getOptionLabelUsing(function ($value) {
                                        return \App\Filament\Resources\NodeResource::getCountryLabel($value);
                                    })
                                    ->live()
                                    ->afterStateUpdated(fn (callable $set) => $set('city', null)),
                                Forms\Components\Select::make('city')
                                    ->label(__('general.node.city'))
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search, callable $get): array {
                                        $countryCode = $get('country');
                                        if (! $countryCode) {
                                            return [];
                                        }

                                        return \App\Filament\Resources\NodeResource::getCitiesFromJson($countryCode, $search);
                                    })
                                    ->getOptionLabelUsing(fn ($value): ?string => $value)
                                    ->disabled(fn (callable $get): bool => ! $get('country')),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $customer = \App\Models\Customer::create($data);

                                return $customer->id;
                            })
                            ->required(),

                        Forms\Components\Select::make('consignee_customer_id')
                            ->label(__('general.shipment.consignee'))
                            ->relationship('consignee', 'customer_name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('customer_name')
                                    ->label(__('general.customer.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label(__('general.customer.email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('general.customer.phone'))
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\Textarea::make('address')
                                    ->label(__('general.customer.address'))
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('country')
                                    ->label(__('general.node.country'))
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search): array {
                                        return \App\Filament\Resources\NodeResource::getCountriesFromJson($search);
                                    })
                                    ->getOptionLabelUsing(function ($value) {
                                        return \App\Filament\Resources\NodeResource::getCountryLabel($value);
                                    })
                                    ->live()
                                    ->afterStateUpdated(fn (callable $set) => $set('city', null)),
                                Forms\Components\Select::make('city')
                                    ->label(__('general.node.city'))
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search, callable $get): array {
                                        $countryCode = $get('country');
                                        if (! $countryCode) {
                                            return [];
                                        }

                                        return \App\Filament\Resources\NodeResource::getCitiesFromJson($countryCode, $search);
                                    })
                                    ->getOptionLabelUsing(fn ($value): ?string => $value)
                                    ->disabled(fn (callable $get): bool => ! $get('country')),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $customer = \App\Models\Customer::create($data);

                                return $customer->id;
                            })
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('general.shipment_leg.title_plural'))
                    ->schema([
                        Forms\Components\Repeater::make('legs')
                            ->label(__('general.shipment_leg.title_plural'))
                            ->relationship('legs')
                            ->schema([
                                Forms\Components\TextInput::make('sequence_order')
                                    ->label(__('general.shipment_leg.sequence_order'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->default(function ($state, $get, $livewire) {
                                        // Get existing legs from the current form data
                                        $existingLegs = collect($get('../../legs') ?? [])->filter(fn ($leg) => ! empty($leg));

                                        // If we're editing an existing shipment, also check database
                                        if ($livewire instanceof \Filament\Resources\Pages\EditRecord && $livewire->record) {
                                            $dbLegs = $livewire->record->legs()->pluck('sequence_order');
                                            $allSequences = $existingLegs->pluck('sequence_order')->merge($dbLegs)->filter();
                                        } else {
                                            $allSequences = $existingLegs->pluck('sequence_order')->filter();
                                        }

                                        return $allSequences->max() + 1 ?: 1;
                                    }),

                                Forms\Components\Select::make('origin_node_id')
                                    ->label(__('general.shipment_leg.from_node'))
                                    ->relationship('originNode', 'node_name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('node_name')
                                            ->label(__('general.node.name'))
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('node_type')
                                            ->label(__('general.node.type'))
                                            ->options([
                                                'WAREHOUSE' => __('general.node.types.warehouse'),
                                                'CUSTOMER_ADDRESS' => __('general.node.types.customer_address'),
                                                'PORT' => __('general.node.types.port'),
                                                'AIRPORT' => __('general.node.types.airport'),
                                                'LAND_DEPOT' => __('general.node.types.land_depot'),
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('country')
                                            ->label(__('general.node.country'))
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search): array {
                                                return \App\Filament\Resources\NodeResource::getCountriesFromJson($search);
                                            })
                                            ->getOptionLabelUsing(function ($value) {
                                                return \App\Filament\Resources\NodeResource::getCountryLabel($value);
                                            })
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('city', null))
                                            ->required(),
                                        Forms\Components\Select::make('city')
                                            ->label(__('general.node.city'))
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                                $countryCode = $get('country');
                                                if (! $countryCode) {
                                                    return [];
                                                }

                                                return \App\Filament\Resources\NodeResource::getCitiesFromJson($countryCode, $search);
                                            })
                                            ->getOptionLabelUsing(fn ($value): ?string => $value)
                                            ->disabled(fn (callable $get): bool => ! $get('country'))
                                            ->required(),
                                        Forms\Components\Textarea::make('address')
                                            ->label(__('general.node.address'))
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        $node = \App\Models\Node::create($data);

                                        return $node->id;
                                    })
                                    ->required(),

                                Forms\Components\Select::make('destination_node_id')
                                    ->label(__('general.shipment_leg.to_node'))
                                    ->relationship('destinationNode', 'node_name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('node_name')
                                            ->label(__('general.node.name'))
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('node_type')
                                            ->label(__('general.node.type'))
                                            ->options([
                                                'WAREHOUSE' => __('general.node.types.warehouse'),
                                                'CUSTOMER_ADDRESS' => __('general.node.types.customer_address'),
                                                'PORT' => __('general.node.types.port'),
                                                'AIRPORT' => __('general.node.types.airport'),
                                                'LAND_DEPOT' => __('general.node.types.land_depot'),
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('country')
                                            ->label(__('general.node.country'))
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search): array {
                                                return \App\Filament\Resources\NodeResource::getCountriesFromJson($search);
                                            })
                                            ->getOptionLabelUsing(function ($value) {
                                                return \App\Filament\Resources\NodeResource::getCountryLabel($value);
                                            })
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('city', null))
                                            ->required(),
                                        Forms\Components\Select::make('city')
                                            ->label(__('general.node.city'))
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                                $countryCode = $get('country');
                                                if (! $countryCode) {
                                                    return [];
                                                }

                                                return \App\Filament\Resources\NodeResource::getCitiesFromJson($countryCode, $search);
                                            })
                                            ->getOptionLabelUsing(fn ($value): ?string => $value)
                                            ->disabled(fn (callable $get): bool => ! $get('country'))
                                            ->required(),
                                        Forms\Components\Textarea::make('address')
                                            ->label(__('general.node.address'))
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        $node = \App\Models\Node::create($data);

                                        return $node->id;
                                    })
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label(__('general.shipment_leg.status'))
                                    ->options([
                                        'PENDING' => __('general.shipment_leg.statuses.pending'),
                                        'IN_PROGRESS' => __('general.shipment_leg.statuses.in_progress'),
                                        'COMPLETED' => __('general.shipment_leg.statuses.completed'),
                                    ])
                                    ->default('PENDING')
                                    ->required(),

                                Forms\Components\DateTimePicker::make('departure_timestamp')
                                    ->label(__('general.shipment_leg.departure_timestamp')),

                                Forms\Components\DateTimePicker::make('arrival_timestamp')
                                    ->label(__('general.shipment_leg.arrival_timestamp')),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->orderColumn('sequence_order')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                $sequence = $state['sequence_order'] ?? '#';
                                $fromNodeName = '';
                                $toNodeName = '';

                                if (! empty($state['origin_node_id'])) {
                                    $fromNode = \App\Models\Node::find($state['origin_node_id']);
                                    $fromNodeName = $fromNode?->node_name ?? "Node {$state['origin_node_id']}";
                                }

                                if (! empty($state['destination_node_id'])) {
                                    $toNode = \App\Models\Node::find($state['destination_node_id']);
                                    $toNodeName = $toNode?->node_name ?? "Node {$state['destination_node_id']}";
                                }

                                if ($fromNodeName && $toNodeName) {
                                    return "Leg {$sequence}: {$fromNodeName} → {$toNodeName}";
                                } else {
                                    return "Leg {$sequence}";
                                }
                            }),
                    ]),

                Forms\Components\Section::make(__('general.date'))
                    ->schema([
                        Forms\Components\DateTimePicker::make('estimated_departure')
                            ->label(__('general.shipment.estimated_departure')),

                        Forms\Components\DateTimePicker::make('estimated_arrival')
                            ->label(__('general.shipment.estimated_arrival')),

                        Forms\Components\DateTimePicker::make('actual_departure')
                            ->label(__('general.shipment.actual_departure')),

                        Forms\Components\DateTimePicker::make('actual_arrival')
                            ->label(__('general.shipment.actual_arrival')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_id')
                    ->label(__('general.shipment.reference_id'))
                    ->copyable()
                    ->copyMessage('Copied!')
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
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->actions([
                Tables\Actions\Action::make('view_legs')
                    ->url(fn (Shipment $record): string => route('filament.admin.resources.shipment-legs.index').'?tableFilters[shipment_id][value]='.$record->id)
                    ->icon('heroicon-o-arrows-right-left')
                    ->label(__('general.widgets.view_legs'))
                    ->tooltip(__('general.widgets.view_shipment_legs')),
                Tables\Actions\Action::make('generate_invoice')
                    ->url(fn (Shipment $record): string => route('filament.admin.resources.invoices.create').'?shipment_id='.$record->id)
                    ->icon('heroicon-o-document-text')
                    ->label(__('general.invoice.generate_invoice'))
                    ->tooltip(__('general.invoice.generate_invoice_tooltip'))
                    ->color('success'),
                Tables\Actions\Action::make('change_status')
                    ->icon('heroicon-o-arrow-path')
                    ->label(__('general.shipment.change_status'))
                    ->tooltip(__('general.shipment.change_status_tooltip'))
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('general.shipment.status'))
                            ->options([
                                'PENDING' => __('general.shipment.statuses.pending'),
                                'IN_TRANSIT' => __('general.shipment.statuses.in_transit'),
                                'AT_WAREHOUSE' => __('general.shipment.statuses.at_warehouse'),
                                'DELIVERED' => __('general.shipment.statuses.delivered'),
                                'CANCELLED' => __('general.shipment.statuses.cancelled'),
                            ])
                            ->default(fn (Shipment $record) => $record->status)
                            ->required(),
                    ])
                    ->action(function (Shipment $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                        ]);

                        $statusLabel = match ($data['status']) {
                            'PENDING' => __('general.shipment.statuses.pending'),
                            'IN_TRANSIT' => __('general.shipment.statuses.in_transit'),
                            'AT_WAREHOUSE' => __('general.shipment.statuses.at_warehouse'),
                            'DELIVERED' => __('general.shipment.statuses.delivered'),
                            'CANCELLED' => __('general.shipment.statuses.cancelled'),
                            default => $data['status'],
                        };

                        \Filament\Notifications\Notification::make()
                            ->title(__('general.shipment.status_updated'))
                            ->body(__('general.shipment.status_changed_to').' '.$statusLabel)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('general.shipment.title'))
                    ->schema([
                        Infolists\Components\TextEntry::make('reference_id')
                            ->label(__('general.shipment.reference_id')),

                        Infolists\Components\TextEntry::make('shipping_mode')
                            ->label(__('general.shipment.shipping_mode'))
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'SEA' => __('general.shipment.sea'),
                                'AIR' => __('general.shipment.air'),
                                'LAND' => __('general.shipment.land'),
                            }),

                        Infolists\Components\TextEntry::make('status')
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
                            }),

                        Infolists\Components\TextEntry::make('shipper.customer_name')
                            ->label(__('general.shipment.shipper')),

                        Infolists\Components\TextEntry::make('consignee.customer_name')
                            ->label(__('general.shipment.consignee')),

                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('general.date'))
                    ->schema([
                        Infolists\Components\TextEntry::make('estimated_departure')
                            ->label(__('general.shipment.estimated_departure'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('estimated_arrival')
                            ->label(__('general.shipment.estimated_arrival'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('actual_departure')
                            ->label(__('general.shipment.actual_departure'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('actual_arrival')
                            ->label(__('general.shipment.actual_arrival'))
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('general.shipment_leg.title_plural'))
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('legs')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('sequence_order')
                                    ->label(__('general.shipment_leg.sequence_order')),

                                Infolists\Components\TextEntry::make('originNode.node_name')
                                    ->label(__('general.shipment_leg.from_node')),

                                Infolists\Components\TextEntry::make('destinationNode.node_name')
                                    ->label(__('general.shipment_leg.to_node')),

                                Infolists\Components\TextEntry::make('status')
                                    ->label(__('general.shipment_leg.status'))
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'PENDING' => __('general.shipment_leg.statuses.pending'),
                                        'IN_PROGRESS' => __('general.shipment_leg.statuses.in_progress'),
                                        'COMPLETED' => __('general.shipment_leg.statuses.completed'),
                                    })
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'PENDING' => 'warning',
                                        'IN_PROGRESS' => 'info',
                                        'COMPLETED' => 'success',
                                        default => 'secondary',
                                    }),

                                Infolists\Components\TextEntry::make('departure_timestamp')
                                    ->label(__('general.shipment_leg.departure_timestamp'))
                                    ->dateTime(),

                                Infolists\Components\TextEntry::make('arrival_timestamp')
                                    ->label(__('general.shipment_leg.arrival_timestamp'))
                                    ->dateTime(),
                            ])
                            ->columns(6),
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
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'view' => Pages\ViewShipment::route('/{record}'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }

    private static function generateReferenceId(): string
    {
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
        return sprintf('SH%s%s%04d', $year, $month, $nextSequence);
    }
}
