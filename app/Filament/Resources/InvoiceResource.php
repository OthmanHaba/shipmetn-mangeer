<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Accounting';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.invoices');
    }

    public static function getModelLabel(): string
    {
        return __('general.invoice.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.invoice.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->label(__('general.invoice.invoice_number'))
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->default(fn () => 'INV-'.strtoupper(uniqid()).'-'.date('Y'))
                            ->required(),

                        Forms\Components\Select::make('shipment_id')
                            ->label(__('general.invoice.shipment'))
                            ->relationship('shipment', 'reference_id')
                            ->searchable()
                            ->preload()
                            ->default(fn () => request()->get('shipment_id'))
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $shipment = \App\Models\Shipment::find($state);
                                    if ($shipment) {
                                        $set('customer_id', $shipment->shipper_customer_id);
                                        $set('total_amount', $shipment->shipment_price ?? 0);
                                    }
                                }
                            })
                            ->required(),

                        Forms\Components\Select::make('customer_id')
                            ->label(__('general.invoice.customer'))
                            ->relationship('customer', 'customer_name')
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                $shipmentId = request()->get('shipment_id');
                                if ($shipmentId) {
                                    $shipment = \App\Models\Shipment::find($shipmentId);

                                    return $shipment?->shipper_customer_id;
                                }

                                return null;
                            })
                            ->required(),

                        Forms\Components\DatePicker::make('issue_date')
                            ->label(__('general.invoice.issue_date'))
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('due_date')
                            ->label(__('general.invoice.due_date'))
                            ->required()
                            ->default(now()->addDays(30)),

                        Forms\Components\TextInput::make('total_amount')
                            ->label(__('general.invoice.total_amount'))
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->prefix('LYD ')
                            ->default(function () {
                                $shipmentId = request()->get('shipment_id');
                                if ($shipmentId) {
                                    $shipment = \App\Models\Shipment::find($shipmentId);

                                    return $shipment?->shipment_price ?? 0;
                                }

                                return 0;
                            }),

                        Forms\Components\Select::make('status')
                            ->label(__('general.invoice.status'))
                            ->options([
                                'DRAFT' => __('general.invoice.statuses.draft'),
                                'SENT' => __('general.invoice.statuses.sent'),
                                'PAID' => __('general.invoice.statuses.paid'),
                                'VOID' => __('general.invoice.statuses.void'),
                            ])
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('general.invoice.invoice_number'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipment.reference_id')
                    ->label(__('general.invoice.shipment'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.customer_name')
                    ->label(__('general.invoice.customer'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label(__('general.invoice.issue_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('general.invoice.due_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('general.invoice.total_amount'))
                    ->money('LYD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('general.invoice.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'DRAFT' => __('general.invoice.statuses.draft'),
                        'SENT' => __('general.invoice.statuses.sent'),
                        'PAID' => __('general.invoice.statuses.paid'),
                        'VOID' => __('general.invoice.statuses.void'),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'DRAFT' => 'gray',
                        'SENT' => 'warning',
                        'PAID' => 'success',
                        'VOID' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->label(__('general.invoice.print_invoice'))
                    ->url(fn (Invoice $record): string => route('invoices.print', ['invoice' => $record->id]))
                    ->openUrlInNewTab()
                    ->color('info'),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
