<?php

namespace App\Filament\Widgets;

use App\Models\Shipment;
use Filament\Widgets\ChartWidget;

class ShipmentChart extends ChartWidget
{
    // protected static ?string $heading;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';



    protected function getData(): array
    {
        $data = Shipment::query()
            ->selectRaw('shipping_mode, COUNT(*) as count')
            ->groupBy('shipping_mode')
            ->get();

        $labels = $data->pluck('type')->map(function ($type) {
            return ucfirst($type);
        })->toArray();

        $datasets = [
            [
                'label' => __('general.widgets.chart_labels.shipments_by_type'),
                'data' => $data->pluck('count')->toArray(),
                'backgroundColor' => [
                    'rgb(54, 162, 235)', // Air - blue
                    'rgb(75, 192, 192)', // Sea - teal
                    'rgb(255, 205, 86)', // Land - yellow
                ],
                'borderColor' => '#fff',
                'borderWidth' => 1,
            ],
        ];

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
