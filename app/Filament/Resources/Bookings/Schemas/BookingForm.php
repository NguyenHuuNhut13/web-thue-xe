<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_id')
                    ->label('Xe du lịch')
                    ->relationship('car', 'title')
                    ->searchable()
                    ->required(),

                Select::make('user_id')
                    ->label('Khách thuê')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->required(),

                TextInput::make('total_price')
                    ->label('Tổng số tiền')
                    ->numeric()
                    ->prefix('VNĐ')
                    ->required(),

                Select::make('status')
                    ->label('Trạng thái đơn hàng')
                    ->options([
                        'pending' => 'Chờ xác nhận (Pending)',
                        'approved' => 'Đã xác nhận (Approved)',
                        'rejected' => 'Bị từ chối (Rejected)',
                        'cancelled' => 'Đã hủy đơn (Cancelled)',
                        'completed' => 'Hoàn thành (Completed)',
                    ])
                    ->required()
                    ->default('pending'),

                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
