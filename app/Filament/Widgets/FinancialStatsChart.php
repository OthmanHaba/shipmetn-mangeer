<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Invoice;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FinancialStatsChart extends ChartWidget
{
    // protected static ?string $heading;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get last 6 months for the chart
        $months = collect();
        $today = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $months->push($today->copy()->subMonths($i)->format('M Y'));
        }

        // Get revenue data by month
        $revenueData = $this->getMonthlyRevenue($today);

        // Get expense data by month
        $expenseData = $this->getMonthlyExpenses($today);

        return [
            'datasets' => [
                [
                    'label' => __('general.widgets.chart_labels.revenue'),
                    'data' => $revenueData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('general.widgets.chart_labels.expenses'),
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getMonthlyRevenue(Carbon $today)
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $revenue = Invoice::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total_amount');

            $data[] = $revenue;
        }

        return $data;
    }

    private function getMonthlyExpenses(Carbon $today)
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $expenses = Expense::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $data[] = $expenses;
        }

        return $data;
    }
}
