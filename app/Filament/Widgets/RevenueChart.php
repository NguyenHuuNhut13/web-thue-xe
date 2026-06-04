<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 3;

    public string $viewLevel = 'months';
    public ?int $selectedMonth = null;
    public ?int $selectedWeek = null;

    public function getHeading(): string | Htmlable | null
    {
        if ($this->viewLevel === 'weeks') {
            return new HtmlString(
                'Doanh thu theo tuần - Tháng ' . $this->selectedMonth . 
                ' <button wire:click="goBack" class="px-2.5 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 ml-3 transition inline-flex items-center gap-1 cursor-pointer">← Quay lại</button>'
            );
        }
        if ($this->viewLevel === 'days') {
            return new HtmlString(
                'Doanh thu theo ngày - Tuần ' . $this->selectedWeek . ' (Tháng ' . $this->selectedMonth . ')' . 
                ' <button wire:click="goBack" class="px-2.5 py-1 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700 ml-3 transition inline-flex items-center gap-1 cursor-pointer">← Quay lại</button>'
            );
        }
        return 'Doanh thu hoàn thành theo tháng (VND)';
    }

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        if ($this->viewLevel === 'months') {
            // Fetch completed bookings for current year, group by month, and sum total price
            $revenue = Booking::where('status', 'completed')
                ->whereYear('created_at', $year)
                ->get()
                ->groupBy(fn ($booking) => $booking->created_at->format('m'))
                ->map(fn ($group) => $group->sum('total_price'))
                ->toArray();

            // Populate array for 12 months
            $data = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthKey = str_pad($m, 2, '0', STR_PAD_LEFT);
                $data[] = $revenue[$monthKey] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Doanh thu (VND)',
                        'data' => $data,
                        'backgroundColor' => '#10b981', // Emerald 500
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            ];
        }

        if ($this->viewLevel === 'weeks') {
            // Fetch completed bookings for the selected month of the current year
            $bookings = Booking::where('status', 'completed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $this->selectedMonth)
                ->get();

            // Group by week of the month (Week 1: Day 1-7, Week 2: Day 8-14, Week 3: Day 15-21, Week 4: Day 22-28, Week 5: Day 29+)
            $weeklyRevenue = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            foreach ($bookings as $booking) {
                $day = $booking->created_at->day;
                if ($day <= 7) $week = 1;
                elseif ($day <= 14) $week = 2;
                elseif ($day <= 21) $week = 3;
                elseif ($day <= 28) $week = 4;
                else $week = 5;

                $weeklyRevenue[$week] += $booking->total_price;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Doanh thu (VND)',
                        'data' => array_values($weeklyRevenue),
                        'backgroundColor' => '#3b82f6', // Blue 500
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => ['Tuần 1 (Ngày 1-7)', 'Tuần 2 (Ngày 8-14)', 'Tuần 3 (Ngày 15-21)', 'Tuần 4 (Ngày 22-28)', 'Tuần 5 (Ngày 29+)'],
            ];
        }

        if ($this->viewLevel === 'days') {
            // Fetch completed bookings for the selected week & month of the current year
            $bookings = Booking::where('status', 'completed')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $this->selectedMonth)
                ->get();

            // Filter bookings belonging to the selected week
            $startDay = ($this->selectedWeek - 1) * 7 + 1;
            if ($this->selectedWeek == 5) {
                $endDay = Carbon::create($year, $this->selectedMonth, 1)->endOfMonth()->day;
            } else {
                $endDay = $this->selectedWeek * 7;
            }

            $dailyRevenue = [];
            $labels = [];

            for ($day = $startDay; $day <= $endDay; $day++) {
                $date = Carbon::create($year, $this->selectedMonth, $day);
                $dateString = $date->format('Y-m-d');
                $dailyRevenue[$dateString] = 0;
                $labels[$dateString] = $date->translatedFormat('D d/m');
            }

            foreach ($bookings as $booking) {
                $day = $booking->created_at->day;
                if ($day >= $startDay && $day <= $endDay) {
                    $dateString = $booking->created_at->format('Y-m-d');
                    if (isset($dailyRevenue[$dateString])) {
                        $dailyRevenue[$dateString] += $booking->total_price;
                    }
                }
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Doanh thu (VND)',
                        'data' => array_values($dailyRevenue),
                        'backgroundColor' => '#f59e0b', // Amber 500
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => array_values($labels),
            ];
        }

        return [];
    }

    public function handleChartClick(int $index): void
    {
        if ($this->viewLevel === 'months') {
            $this->selectedMonth = $index + 1;
            $this->viewLevel = 'weeks';
        } elseif ($this->viewLevel === 'weeks') {
            $this->selectedWeek = $index + 1;
            $this->viewLevel = 'days';
        }
    }

    public function goBack(): void
    {
        if ($this->viewLevel === 'days') {
            $this->viewLevel = 'weeks';
            $this->selectedWeek = null;
        } elseif ($this->viewLevel === 'weeks') {
            $this->viewLevel = 'months';
            $this->selectedMonth = null;
        }
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                onClick: (event, activeElements) => {
                    if (activeElements && activeElements.length > 0) {
                        const index = activeElements[0].index;
                        \$wire.handleChartClick(index);
                    }
                },
                onHover: (event, activeElements) => {
                    if (event && event.native && event.native.target) {
                        event.native.target.style.cursor = activeElements.length > 0 ? 'pointer' : 'default';
                    }
                }
            }
        JS);
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
