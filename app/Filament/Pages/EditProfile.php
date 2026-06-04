<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->placeholder('Ví dụ: 0932030958')
                    ->maxLength(20),

                TextInput::make('zalo')
                    ->label('Số Zalo / Link Zalo')
                    ->placeholder('Ví dụ: 0932030958')
                    ->maxLength(255),

                FileUpload::make('avatar')
                    ->label('Ảnh đại diện (Avatar)')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars'),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}
