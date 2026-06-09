<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Hồ sơ & Ảnh đại diện';

    protected static ?string $title = 'Hồ sơ & Ảnh đại diện';

    protected static ?string $slug = 'profile';

    protected string $view = 'filament.pages.profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Schema $schema): Schema
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
                    ->unique(table: 'users', column: 'email', ignoreRecord: true)
                    ->placeholder('Ví dụ: nhut@example.com'),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->placeholder('Ví dụ: 0932030958'),

                TextInput::make('zalo')
                    ->label('Số Zalo / Link Zalo')
                    ->placeholder('Ví dụ: 0932030958'),

                FileUpload::make('avatar')
                    ->label('Ảnh đại diện (Avatar)')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull(),

                TextInput::make('new_password')
                    ->label('Mật khẩu mới')
                    ->password()
                    ->placeholder('Chỉ điền nếu muốn đổi mật khẩu'),

                TextInput::make('new_password_confirmation')
                    ->label('Xác nhận mật khẩu mới')
                    ->password()
                    ->same('new_password')
                    ->requiredWith('new_password')
                    ->placeholder('Nhập lại mật khẩu mới'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $user = auth()->user();
        $data = $this->form->getState();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->zalo = $data['zalo'];
        
        if (array_key_exists('avatar', $data)) {
            $user->avatar = $data['avatar'];
        }

        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        Notification::make()
            ->title('Đã cập nhật hồ sơ thành công!')
            ->success()
            ->send();
            
        // Reset password fields in state and update form
        $userArray = $user->toArray();
        $userArray['new_password'] = null;
        $userArray['new_password_confirmation'] = null;
        $this->form->fill($userArray);
    }
}
