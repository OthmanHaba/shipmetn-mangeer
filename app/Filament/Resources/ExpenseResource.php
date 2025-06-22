<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Accounting';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.expenses');
    }

    public static function getModelLabel(): string
    {
        return __('general.expense.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.expense.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('shipment_id')
                            ->label(__('general.expense.shipment'))
                           ->relationship('shipment', 'reference_id')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('shipment_leg_id')
                            ->label(__('general.expense.shipment_leg'))
                           ->relationship('shipmentLeg', 'id')
                           ->getOptionLabelFromRecordUsing(function ($record) {
                               return 'Leg #' . $record->id . ': ' . $record->originNode->node_name . ' → ' . $record->destinationNode->node_name;
                           })
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('vendor_name')
                            ->label(__('general.expense.vendor_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('expense_date')
                            ->label(__('general.expense.expense_date'))
                            ->required()
                            ->default(now()),

                        Forms\Components\TextInput::make('amount')
                            ->label(__('general.expense.amount'))
                            ->required()
                            ->numeric()
                            ->step(0.01),

                        Forms\Components\Select::make('account_id')
                            ->label(__('general.expense.account'))
                            ->relationship('account', 'account_name', function ($query) {
                                return $query->where('account_type', 'EXPENSE');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label(__('general.expense.description'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shipment.reference_id')
                    ->label(__('general.expense.shipment'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vendor_name')
                    ->label(__('general.expense.vendor_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expense_date')
                    ->label(__('general.expense.expense_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('general.expense.amount'))
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('account.account_name')
                    ->label(__('general.expense.account'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('general.expense.description'))
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
