<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalCars = Car::count();
        $pendingCars = Car::where('status', 'pending')->count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_price');

        return [
            Stat::make('Tổng thành viên', $totalUsers)
                ->description('Người dùng đăng ký trên hệ thống')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Xe cho thuê', $totalCars)
                ->description("{$pendingCars} xe đang chờ duyệt")
                ->descriptionIcon('heroicon-m-truck')
                ->color($pendingCars > 0 ? 'warning' : 'success'),

            Stat::make('Tổng lượt đặt xe', $totalBookings)
                ->description('Các giao dịch kết nối thành công')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Tổng doanh thu', number_format($totalRevenue, 0, ',', '.') . 'đ')
                ->description('Doanh thu từ các chuyến đi hoàn thành')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
