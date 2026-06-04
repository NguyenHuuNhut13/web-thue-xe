<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Họ tên')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('email')
                    ->label('Địa chỉ Email')
                    ->email()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('subject')
                    ->label('Tiêu đề')
                    ->disabled()
                    ->dehydrated(),

                Textarea::make('message')
                    ->label('Nội dung tin nhắn')
                    ->required()
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(),

                Select::make('status')
                    ->label('Trạng thái xử lý')
                    ->options([
                        'new' => 'Mới gửi (New)',
                        'read' => 'Đã đọc (Read)',
                        'replied' => 'Đã phản hồi (Replied)',
                    ])
                    ->required()
                    ->default('new'),
            ]);
    }
}
