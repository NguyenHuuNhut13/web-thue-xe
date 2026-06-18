<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
                    ->label('Khách thuê (Tài khoản)')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('customer_name')
                    ->label('Họ tên khách hàng')
                    ->required(),

                TextInput::make('customer_phone')
                    ->label('Số điện thoại liên hệ')
                    ->required(),

                TextInput::make('customer_email')
                    ->label('Email liên hệ')
                    ->email()
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->required(),

                TextInput::make('pickup_location')
                    ->label('Điểm đi (Đón)')
                    ->required(),

                TextInput::make('dropoff_location')
                    ->label('Điểm đến (Trả)')
                    ->required(),

                Toggle::make('has_driver')
                    ->label('Có kèm tài xế')
                    ->default(false),

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
