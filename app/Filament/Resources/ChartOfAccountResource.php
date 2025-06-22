<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChartOfAccountResource\Pages;
use App\Models\ChartOfAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Accounting';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.chart_of_accounts');
    }

    public static function getModelLabel(): string
    {
        return __('general.chart_of_account.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.chart_of_account.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('account_number')
                            ->label(__('general.chart_of_account.account_number'))
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('account_name')
                            ->label(__('general.chart_of_account.account_name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('account_type')
                            ->label(__('general.chart_of_account.account_type'))
                            ->options([
                                'ASSET' => __('general.chart_of_account.types.asset'),
                                'LIABILITY' => __('general.chart_of_account.types.liability'),
                                'EQUITY' => __('general.chart_of_account.types.equity'),
                                'REVENUE' => __('general.chart_of_account.types.revenue'),
                                'EXPENSE' => __('general.chart_of_account.types.expense'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('parent_account_id')
                            ->label(__('general.chart_of_account.parent_account'))
                            ->relationship('parentAccount', 'account_name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_number')
                    ->label(__('general.chart_of_account.account_number'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('account_name')
                    ->label(__('general.chart_of_account.account_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('account_type')
                    ->label(__('general.chart_of_account.account_type'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ASSET' => __('general.chart_of_account.types.asset'),
                        'LIABILITY' => __('general.chart_of_account.types.liability'),
                        'EQUITY' => __('general.chart_of_account.types.equity'),
                        'REVENUE' => __('general.chart_of_account.types.revenue'),
                        'EXPENSE' => __('general.chart_of_account.types.expense'),
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('parentAccount.account_name')
                    ->label(__('general.chart_of_account.parent_account'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListChartOfAccounts::route('/'),
            'create' => Pages\CreateChartOfAccount::route('/create'),
            'edit' => Pages\EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
