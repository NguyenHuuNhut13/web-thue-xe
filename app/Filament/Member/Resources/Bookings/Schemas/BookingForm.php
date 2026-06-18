<?php

namespace App\Filament\Member\Resources\Bookings\Schemas;

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
                    ->label('Xe')
                    ->relationship('car', 'title')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                Select::make('user_id')
                    ->label('Tài khoản đặt')
                    ->relationship('user', 'name')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('customer_name')
                    ->label('Họ tên khách thuê')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('customer_phone')
                    ->label('Số điện thoại liên hệ')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('customer_email')
                    ->label('Email liên hệ')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('pickup_location')
                    ->label('Điểm đi (Đón)')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('dropoff_location')
                    ->label('Điểm đến (Trả)')
                    ->required()
                    ->disabled()
                    ->dehydrated(),

                Toggle::make('has_driver')
                    ->label('Có tài xế kèm theo')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('total_price')
                    ->label('Tổng tiền thuê')
                    ->required()
                    ->numeric()
                    ->prefix('VNĐ')
                    ->disabled()
                    ->dehydrated(),

                Select::make('status')
                    ->label('Trạng thái đơn')
                    ->options([
                        'pending' => 'Chờ xác nhận',
                        'approved' => 'Đã xác nhận',
                        'rejected' => 'Từ chối',
                        'cancelled' => 'Khách đã hủy',
                        'completed' => 'Đã hoàn thành',
                    ])
                    ->required()
                    ->disabled(fn ($record) => !$record || $record->car->user_id !== auth()->id())
                    ->helperText(fn ($record) => $record && $record->car->user_id === auth()->id() 
                        ? 'Bạn là chủ xe. Hãy xác nhận hoặc từ chối đơn đặt này.' 
                        : 'Bạn là khách thuê. Chỉ chủ xe mới có quyền thay đổi trạng thái đơn đặt.'),

                Textarea::make('notes')
                    ->label('Ghi chú từ khách hàng')
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(),
            ]);
    }
}
