<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use Filament\Widgets\ChartWidget;

class CarBrandChart extends ChartWidget
{
    protected ?string $heading = 'Cơ cấu xe theo thương hiệu';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Get car count grouped by brand
        $brands = Car::select('brand', \DB::raw('count(*) as count'))
            ->groupBy('brand')
            ->get();

        $labels = $brands->pluck('brand')->toArray();
        $data = $brands->pluck('count')->toArray();

        // Harmonious colors for doughnut chart
        $colors = [
            '#0077bb', // Primary Blue
            '#10b981', // Emerald Green
            '#f59e0b', // Amber Warning
            '#ef4444', // Red Danger
            '#8b5cf6', // Violet
            '#ec4899', // Pink
            '#6b7280', // Gray
        ];

        // Slice colors to match number of brands
        $backgroundColors = array_slice($colors, 0, count($labels));
        // If there are more brands than colors, pad with random colors or repeat
        if (count($labels) > count($colors)) {
            for ($i = count($colors); $i < count($labels); $i++) {
                $backgroundColors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượng xe',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
