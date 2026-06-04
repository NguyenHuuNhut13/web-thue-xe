<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingStatusChart extends ChartWidget
{
    protected ?string $heading = 'Tỷ lệ trạng thái đơn đặt xe';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Get bookings grouped by status
        $statuses = Booking::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Translate status values to Vietnamese
        $statusLabels = [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'completed' => 'Hoàn thành',
            'rejected' => 'Bị từ chối',
            'cancelled' => 'Đã hủy',
        ];

        $labels = [];
        $data = [];
        $backgroundColors = [];

        // Harmonious colors corresponding to status
        $statusColors = [
            'pending' => '#f59e0b',    // Amber
            'approved' => '#3b82f6',   // Blue
            'completed' => '#10b981',  // Emerald
            'rejected' => '#ef4444',   // Red
            'cancelled' => '#6b7280',  // Gray
        ];

        foreach ($statuses as $s) {
            $labels[] = $statusLabels[$s->status] ?? $s->status;
            $data[] = $s->count;
            $backgroundColors[] = $statusColors[$s->status] ?? '#cbd5e1';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Đơn hàng',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
