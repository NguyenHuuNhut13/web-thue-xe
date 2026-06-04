<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class BookingsChart extends ChartWidget
{
    protected ?string $heading = 'Lượt đặt xe theo tháng (Năm nay)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        // Fetch bookings for current year, group by month, and count
        $bookings = Booking::whereYear('created_at', $year)
            ->get()
            ->groupBy(fn ($booking) => $booking->created_at->format('m'))
            ->map(fn ($group) => $group->count())
            ->toArray();

        // Populate array for 12 months
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
            $data[] = $bookings[$monthKey] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số lượt đặt xe',
                    'data' => $data,
                    'backgroundColor' => 'rgba(0, 119, 187, 0.1)',
                    'borderColor' => '#0077bb',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
