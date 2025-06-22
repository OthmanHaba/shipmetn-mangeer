<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Shipments';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.customers');
    }

    public static function getModelLabel(): string
    {
        return __('general.customer.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.customer.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label(__('general.customer.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_person')
                            ->label(__('general.customer.contact_person'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_number')
                            ->label(__('general.customer.phone_number'))
                            ->tel()
                            ->maxLength(50),
                        Forms\Components\Textarea::make('address')
                            ->label(__('general.customer.address'))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('general.customer.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label(__('general.customer.contact_person'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(__('general.customer.phone_number'))
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
