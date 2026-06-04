<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Ảnh bài viết')
                    ->circular()
                    ->state(function ($record) {
                        if (!$record->image) {
                            return 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80';
                        }
                        if (str_starts_with($record->image, 'http://') || str_starts_with($record->image, 'https://')) {
                            return $record->image;
                        }
                        return asset('storage/' . $record->image);
                    }),

                TextColumn::make('author.name')
                    ->label('Tác giả')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Bản nháp',
                        'published' => 'Đã xuất bản',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
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
