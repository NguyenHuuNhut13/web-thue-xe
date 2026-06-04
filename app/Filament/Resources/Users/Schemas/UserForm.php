<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Họ và tên')
                    ->required()
                    ->placeholder('Ví dụ: Nguyễn Văn A'),

                TextInput::make('email')
                    ->label('Địa chỉ Email')
                    ->email()
                    ->required()
                    ->placeholder('Ví dụ: admin@nks.vn')
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Mật khẩu')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->placeholder('Chỉ điền khi muốn đặt mới/thay đổi'),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->placeholder('Ví dụ: 0932030958'),

                TextInput::make('zalo')
                    ->label('Số Zalo / Link Zalo')
                    ->placeholder('Ví dụ: 0932030958'),

                Select::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Administrator (Quản trị viên)',
                        'member' => 'Member (Chủ xe / Khách thuê)',
                    ])
                    ->required()
                    ->default('member'),

                Select::make('status')
                    ->label('Trạng thái tài khoản')
                    ->options([
                        'active' => 'Hoạt động (Active)',
                        'blocked' => 'Bị khóa (Blocked)',
                    ])
                    ->required()
                    ->default('active'),

                FileUpload::make('avatar')
                    ->label('Ảnh đại diện')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull(),
            ]);
    }
}
