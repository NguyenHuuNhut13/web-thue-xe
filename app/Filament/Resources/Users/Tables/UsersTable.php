<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Ảnh đại diện')
                    ->circular()
                    ->state(fn ($record) => $record->avatar_url),

                TextColumn::make('name')
                    ->label('Họ tên')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Địa chỉ Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Điện thoại')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('zalo')
                    ->label('Zalo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('role')
                    ->label('Vai trò')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'member' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin',
                        'member' => 'Thành viên',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'blocked' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'blocked' => 'Đã khóa',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Quản lý')
                    ->icon('heroicon-o-user-circle')
                    ->modalHeading('Quản lý thành viên')
                    ->form([
                        TextInput::make('name')
                            ->label('Họ và tên')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Địa chỉ Email')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->disabled()
                            ->placeholder('Chưa cập nhật'),
                        TextInput::make('zalo')
                            ->label('Zalo')
                            ->disabled()
                            ->placeholder('Chưa cập nhật'),
                        Select::make('role')
                            ->label('Vai trò')
                            ->options([
                                'admin' => '🔐 Administrator (Quản trị viên)',
                                'member' => '👤 Member (Thành viên)',
                            ])
                            ->required()
                            ->helperText('Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập hệ thống.'),
                        Select::make('status')
                            ->label('Trạng thái tài khoản')
                            ->options([
                                'active'  => '✅ Hoạt động',
                                'blocked' => '🔒 Khóa tài khoản',
                            ])
                            ->required()
                            ->helperText('Tài khoản bị khóa sẽ không thể đăng nhập.'),
                    ])
                    ->modalSubmitActionLabel('Lưu thay đổi')
                    ->modalCancelActionLabel('Đóng')
                    ->mutateFormDataBeforeSave(fn (array $data, $record): array => [
                        'role'   => $data['role'],
                        'status' => $data['status'],
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
