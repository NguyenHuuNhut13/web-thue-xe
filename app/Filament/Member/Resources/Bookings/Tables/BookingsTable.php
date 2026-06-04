<?php

namespace App\Filament\Member\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role')
                    ->label('Vai trò của bạn')
                    ->badge()
                    ->state(fn ($record): string => $record->user_id === auth()->id() ? 'Khách thuê' : 'Chủ xe')
                    ->color(fn ($state): string => $state === 'Khách thuê' ? 'primary' : 'success')
                    ->sortable(),

                TextColumn::make('car.title')
                    ->label('Xe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Khách thuê')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Từ ngày')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Đến ngày')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Tổng tiền')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        'completed' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ xác nhận',
                        'approved' => 'Đã xác nhận',
                        'rejected' => 'Từ chối',
                        'cancelled' => 'Đã hủy',
                        'completed' => 'Hoàn thành',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
