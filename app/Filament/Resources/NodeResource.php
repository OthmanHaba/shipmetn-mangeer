<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeResource\Pages;
use App\Models\Node;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Shipments';

    public static function getNavigationLabel(): string
    {
        return __('general.navigation.nodes');
    }

    public static function getModelLabel(): string
    {
        return __('general.node.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.node.title_plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
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
                                return self::getCountriesFromJson($search);
                            })
                            ->getOptionLabelUsing(function ($value) {
                                return self::getCountryLabel($value);
                            })
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('city', null))
                            ->required(),
                                                Forms\Components\Select::make('city')
                            ->label(__('general.node.city'))
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                $countryCode = $get('country');
                                if (!$countryCode) {
                                    return [];
                                }
                                return self::getCitiesFromJson($countryCode, $search);
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => $value)
                            ->disabled(fn (callable $get): bool => !$get('country'))
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->label(__('general.node.address'))
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
                Tables\Columns\TextColumn::make('node_name')
                    ->label(__('general.node.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('node_type')
                    ->label(__('general.node.type'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'WAREHOUSE' => __('general.node.types.warehouse'),
                        'CUSTOMER_ADDRESS' => __('general.node.types.customer_address'),
                        'PORT' => __('general.node.types.port'),
                        'AIRPORT' => __('general.node.types.airport'),
                        'LAND_DEPOT' => __('general.node.types.land_depot'),
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label(__('general.node.city'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label(__('general.node.country'))
                    ->searchable(),
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
            'index' => Pages\ListNodes::route('/'),
            'create' => Pages\CreateNode::route('/create'),
            'edit' => Pages\EditNode::route('/{record}/edit'),
        ];
    }

            public static function getCountriesFromJson(string $search): array
    {
        try {
            $countriesPath = storage_path('app/countries.json');
            if (!file_exists($countriesPath)) {
                return [];
            }

            $countriesData = json_decode(file_get_contents($countriesPath), true);
            if (!$countriesData) {
                return [];
            }

            $locale = app()->getLocale();
            $result = [];

            foreach ($countriesData as $code => $country) {
                $displayName = $locale === 'ar' && isset($country['name_ar'])
                    ? $country['name_ar'] . ' (' . $country['name_en'] . ')'
                    : $country['name_en'];

                // Filter by search term if provided
                if (empty($search) || stripos($displayName, $search) !== false || stripos($country['name_en'], $search) !== false || ($locale === 'ar' && isset($country['name_ar']) && stripos($country['name_ar'], $search) !== false)) {
                    $result[$code] = $displayName;
                }
            }

            return $result;
        } catch (Exception $e) {
            return [];
        }
    }

    public static function getCountryLabel(string $value): ?string
    {
        if (!$value) return null;

        try {
            $countriesPath = storage_path('app/countries.json');
            if (!file_exists($countriesPath)) {
                return $value;
            }

            $countriesData = json_decode(file_get_contents($countriesPath), true);
            if (!$countriesData || !isset($countriesData[$value])) {
                return $value;
            }

            $country = $countriesData[$value];
            $locale = app()->getLocale();

            return $locale === 'ar' && isset($country['name_ar'])
                ? $country['name_ar'] . ' (' . $country['name_en'] . ')'
                : $country['name_en'];
        } catch (Exception $e) {
            return $value;
        }
    }

    public static function getCitiesFromJson(string $countryCode, string $search): array
    {
        try {
            $citiesPath = storage_path('app/cities.json');
            if (!file_exists($citiesPath)) {
                return [];
            }

            $citiesData = json_decode(file_get_contents($citiesPath), true);
            if (!$citiesData || !isset($citiesData[$countryCode])) {
                return [];
            }

            $cities = $citiesData[$countryCode];
            $locale = app()->getLocale();
            $result = [];

            foreach ($cities as $city) {
                $displayName = $locale === 'ar' && isset($city['name_ar'])
                    ? $city['name_ar'] . ' (' . $city['name_en'] . ')'
                    : $city['name_en'];

                // Filter by search term if provided
                if (empty($search) || stripos($displayName, $search) !== false || stripos($city['name_en'], $search) !== false || ($locale === 'ar' && isset($city['name_ar']) && stripos($city['name_ar'], $search) !== false)) {
                    $result[$displayName] = $displayName;
                }
            }

            return array_slice($result, 0, 20); // Limit results to 20
        } catch (Exception $e) {
            return [];
        }
    }
}
