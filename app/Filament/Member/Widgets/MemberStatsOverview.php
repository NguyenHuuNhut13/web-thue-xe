<?php

namespace App\Filament\Member\Widgets;

use App\Models\Car;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();
        
        // Count cars owned by this member
        $myCarsCount = Car::where('user_id', $userId)->count();
        
        // Count bookings received (as owner)
        $receivedBookingsCount = Booking::whereHas('car', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
        
        // Count bookings sent (as renter)
        $sentBookingsCount = Booking::where('user_id', $userId)->count();
        
        // Count total earnings from completed rents
        $earnings = Booking::where('status', 'completed')
            ->whereHas('car', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->sum('total_price');

        return [
            Stat::make('Xe của tôi', $myCarsCount)
                ->description('Số xe bạn đăng ký cho thuê')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Đơn đặt xe nhận được', $receivedBookingsCount)
                ->description('Khách hàng yêu cầu thuê xe của bạn')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('success'),

            Stat::make('Lịch sử thuê xe của tôi', $sentBookingsCount)
                ->description('Số chuyến xe bạn đã đặt thuê')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Doanh thu cho thuê xe', number_format($earnings, 0, ',', '.') . 'đ')
                ->description('Số tiền nhận từ các chuyến hoàn thành')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
