<?php

namespace App\Filament\Resources\Cars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Ảnh xe')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->state(function ($record) {
                        if (empty($record->images) || !is_array($record->images) || count($record->images) === 0) {
                            return 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
                        }
                        $img = $record->images[0];
                        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                            return $img;
                        }
                        return asset('storage/' . $img);
                    }),

                TextColumn::make('owner.name')
                    ->label('Chủ xe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Tên xe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand')
                    ->label('Hãng xe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('year')
                    ->label('Năm SX')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('fuel_type')
                    ->label('Nhiên liệu')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gasoline' => 'Xăng',
                        'diesel' => 'Dầu',
                        'electric' => 'Điện',
                        'hybrid' => 'Hybrid',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('transmission')
                    ->label('Hộp số')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'automatic' => 'Tự động',
                        'manual' => 'Số sàn',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('seats')
                    ->label('Số chỗ')
                    ->sortable(),

                TextColumn::make('price_per_day')
                    ->label('Giá thuê/ngày')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ duyệt',
                        'active' => 'Hiển thị',
                        'inactive' => 'Bị khóa',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày đăng')
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
