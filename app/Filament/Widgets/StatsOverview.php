<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Shipment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('general.widgets.stats_overview.total_shipments'), Shipment::count())
                ->description(__('general.widgets.stats_overview.total_shipments_description'))
                ->descriptionIcon('heroicon-o-truck')
                ->chart(Shipment::query()
                    ->selectRaw('DATE(created_at) as date, count(*) as count')
                    ->whereDate('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->pluck('count')->toArray())
                ->color('primary'),

            Stat::make(__('general.widgets.stats_overview.active_customers'), Customer::count())
                ->description(__('general.widgets.stats_overview.active_customers_description'))
                ->descriptionIcon('heroicon-o-user-group')
                ->chart(Customer::query()
                    ->selectRaw('DATE(created_at) as date, count(*) as count')
                    ->whereDate('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->pluck('count')->toArray())
                ->color('success'),

            Stat::make(__('general.widgets.stats_overview.revenue'), function () {
                return Invoice::sum('total_amount');
            })
                ->description(__('general.widgets.stats_overview.revenue_description'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->chart(Invoice::query()
                    ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                    ->whereDate('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->pluck('total')->toArray())
                ->color('warning'),

            Stat::make(__('general.widgets.stats_overview.expenses'), function () {
                return Expense::sum('amount');
            })
                ->description(__('general.widgets.stats_overview.expenses_description'))
                ->descriptionIcon('heroicon-o-receipt-percent')
                ->chart(Expense::query()
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                    ->whereDate('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->pluck('total')->toArray())
                ->color('danger'),
        ];
    }
}
