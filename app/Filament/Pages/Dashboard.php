<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialStatsChart;
use App\Filament\Widgets\LatestShipments;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            LatestShipments::class,
            FinancialStatsChart::class,
        ];
    }
}
