<?php

namespace App\Filament\Member\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use App\Services\CompanyApiService;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Hồ sơ';

    protected static ?string $title = 'Hồ sơ';

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
                    ->unique(
                        table: 'users',
                        column: 'email',
                        ignorable: fn () => auth()->user()
                    )
                    ->placeholder('Ví dụ: nhut@example.com'),

                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel()
                    ->placeholder('Ví dụ: 0932030958'),

                TextInput::make('zalo')
                    ->label('Số Zalo / Link Zalo')
                    ->placeholder('Ví dụ: 0932030958'),

                TextInput::make('cccd')
                    ->label('Số Căn cước công dân (CCCD)')
                    ->placeholder('Ví dụ: 079195000123')
                    ->maxLength(15),

                FileUpload::make('avatar')
                    ->label('Ảnh đại diện (Avatar)')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull(),

                TextInput::make('old_password')
                    ->label('Mật khẩu hiện tại')
                    ->password()
                    ->placeholder('Nhập mật khẩu hiện tại nếu muốn đổi mật khẩu')
                    ->requiredWith('new_password'),

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
        $token = session('company_api_token');

        // 1. Nếu có token API, thực hiện cập nhật qua API trước
        if ($token) {
            // Cập nhật profile (name, phone, zalo)
            $profileRes = CompanyApiService::updateProfile($token, [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'zalo' => $data['zalo'],
            ]);
            
            if (!$profileRes['success']) {
                Notification::make()
                    ->title('Lỗi cập nhật thông tin lên hệ thống: ' . $profileRes['message'])
                    ->danger()
                    ->send();
                return;
            }

            // Cập nhật CCCD
            if ($data['cccd'] !== $user->cccd) {
                $cccdRes = CompanyApiService::updateCccd($token, $data['cccd']);
                if (!$cccdRes['success']) {
                    Notification::make()
                        ->title('Lỗi cập nhật CCCD lên hệ thống: ' . $cccdRes['message'])
                        ->danger()
                        ->send();
                    return;
                }
            }

            // Cập nhật mật khẩu
            if (!empty($data['new_password'])) {
                if (empty($data['old_password'])) {
                    Notification::make()
                        ->title('Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu.')
                        ->danger()
                        ->send();
                    return;
                }

                $passRes = CompanyApiService::updatePassword($token, $data['old_password'], $data['new_password']);
                if (!$passRes['success']) {
                    Notification::make()
                        ->title('Lỗi đổi mật khẩu: ' . $passRes['message'])
                        ->danger()
                        ->send();
                    return;
                }
            }

            // Cập nhật avatar
            if (!empty($data['avatar']) && $data['avatar'] !== $user->avatar) {
                $avatarPath = storage_path('app/public/' . $data['avatar']);
                if (file_exists($avatarPath)) {
                    $avatarRes = CompanyApiService::updateAvatar($token, $avatarPath);
                    if (!$avatarRes['success']) {
                        Notification::make()
                            ->title('Lỗi cập nhật ảnh đại diện: ' . $avatarRes['message'])
                            ->danger()
                            ->send();
                        return;
                    }
                }
            }
        }

        // 2. Đồng bộ lưu lại cục bộ
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->zalo = $data['zalo'];
        $user->cccd = $data['cccd'];
        
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
        $userArray['old_password'] = null;
        $userArray['new_password'] = null;
        $userArray['new_password_confirmation'] = null;
        $this->form->fill($userArray);
    }
}
