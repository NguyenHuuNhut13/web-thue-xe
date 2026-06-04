<?php

namespace App\Filament\Member\Resources\Bookings;

use App\Filament\Member\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Member\Resources\Bookings\Pages\EditBooking;
use App\Filament\Member\Resources\Bookings\Pages\ListBookings;
use App\Filament\Member\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Member\Resources\Bookings\Tables\BookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationLabel = 'Đơn đặt xe';
    protected static ?string $pluralModelLabel = 'Lịch sử đặt xe';
    protected static ?string $modelLabel = 'Đơn đặt xe';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string|\UnitEnum|null $navigationGroup = 'Hoạt động';

    public static function form(Schema $schema): Schema
    {
        return BookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where(function ($query) {
            $query->where('user_id', auth()->id())
                ->orWhereHas('car', function ($carQuery) {
                    $carQuery->where('user_id', auth()->id());
                });
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
