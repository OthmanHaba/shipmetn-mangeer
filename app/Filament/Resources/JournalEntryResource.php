<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalEntryResource\Pages;
use App\Models\JournalEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Accounting';

    public static function canAccess(): bool
    {
        return false;
    }

    public static function getNavigationLabel(): string
    {
        return __('general.journal_entry.title_plural');
    }

    public static function getModelLabel(): string
    {
        return __('general.journal_entry.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.journal_entry.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DatePicker::make('entry_date')
                            ->label(__('general.journal_entry.entry_date'))
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('description')
                            ->label(__('general.journal_entry.description'))
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('reference_type')
                            ->label(__('general.journal_entry.reference_type'))
                            ->maxLength(50),

                        Forms\Components\TextInput::make('reference_id')
                            ->label(__('general.journal_entry.reference_id'))
                            ->numeric(),

                        Forms\Components\Select::make('shipment_id')
                            ->label(__('general.journal_entry.shipment'))
                            ->relationship('shipment', 'reference_id')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Journal Lines')
                    ->schema([
                        Forms\Components\Repeater::make('lines')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('account_id')
                                    ->label(__('general.journal_line.account'))
                                    ->relationship('account', 'account_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('entry_type')
                                    ->label(__('general.journal_line.entry_type'))
                                    ->options([
                                        'DEBIT' => __('general.journal_line.types.debit'),
                                        'CREDIT' => __('general.journal_line.types.credit'),
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('amount')
                                    ->label(__('general.journal_line.amount'))
                                    ->numeric()
                                    ->required()
                                    ->step(0.01),
                            ])
                            ->columns(3)
                            ->required()
                            ->minItems(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('entry_date')
                    ->label(__('general.journal_entry.entry_date'))
                    ->date()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('general.journal_entry.description'))
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('reference_type')
                    ->label(__('general.journal_entry.reference_type'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipment.reference_id')
                    ->label(__('general.journal_entry.shipment'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_by.name')
                    ->label(__('general.journal_entry.created_by'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
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
            'index' => Pages\ListJournalEntries::route('/'),
            'create' => Pages\CreateJournalEntry::route('/create'),
            'edit' => Pages\EditJournalEntry::route('/{record}/edit'),
        ];
    }
}
