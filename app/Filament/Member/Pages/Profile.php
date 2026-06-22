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

    protected string $view = 'filament.member.pages.profile';

    public string $activeTab = 'personal';

    public bool $isEditing = false;

    public ?array $profileData = [];
    public ?array $cccdData = [];
    public ?array $avatarData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'zalo' => $user->zalo,
        ];

        $this->cccdData = [
            'cccd' => $user->cccd,
        ];

        $this->avatarData = [
            'avatar' => $user->avatar,
        ];

        $this->passwordData = [
            'old_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ];

        $this->profileForm->fill($this->profileData);
        $this->cccdForm->fill($this->cccdData);
        $this->avatarForm->fill($this->avatarData);
        $this->passwordForm->fill($this->passwordData);
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'cccdForm',
            'avatarForm',
            'passwordForm',
        ];
    }

    public function profileForm(Schema $schema): Schema
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
            ])
            ->statePath('profileData');
    }

    public function cccdForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cccd')
                    ->label('Số Căn cước công dân (CCCD)')
                    ->placeholder('Ví dụ: 079195000123')
                    ->maxLength(15)
                    ->required(),
            ])
            ->statePath('cccdData');
    }

    public function avatarForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->label('Ảnh đại diện (Avatar)')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('avatars')
                    ->columnSpanFull()
                    ->required(),
            ])
            ->statePath('avatarData');
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_password')
                    ->label('Mật khẩu hiện tại')
                    ->password()
                    ->required()
                    ->placeholder('Nhập mật khẩu hiện tại'),

                TextInput::make('new_password')
                    ->label('Mật khẩu mới')
                    ->password()
                    ->required()
                    ->placeholder('Nhập mật khẩu mới'),

                TextInput::make('new_password_confirmation')
                    ->label('Xác nhận mật khẩu mới')
                    ->password()
                    ->same('new_password')
                    ->required()
                    ->placeholder('Nhập lại mật khẩu mới'),
            ])
            ->statePath('passwordData');
    }

    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    public function cancelEdit(): void
    {
        $user = auth()->user();
        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'zalo' => $user->zalo,
        ];
        $this->profileForm->fill($this->profileData);
        $this->isEditing = false;
    }

    public function saveProfile(): void
    {
        $user = auth()->user();
        $data = $this->profileForm->getState();
        $token = session('company_api_token');

        if ($token) {
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
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->zalo = $data['zalo'];
        $user->save();

        Notification::make()
            ->title('Đã cập nhật thông tin cá nhân thành công!')
            ->success()
            ->send();

        $this->isEditing = false;
    }

    public function saveCccd(): void
    {
        $user = auth()->user();
        $data = $this->cccdForm->getState();
        $token = session('company_api_token');

        if ($data['cccd'] !== $user->cccd) {
            if ($token) {
                $cccdRes = CompanyApiService::updateCccd($token, $data['cccd']);
                if (!$cccdRes['success']) {
                    Notification::make()
                        ->title('Lỗi cập nhật CCCD lên hệ thống: ' . $cccdRes['message'])
                        ->danger()
                        ->send();
                    return;
                }
            }

            $user->cccd = $data['cccd'];
            $user->save();

            Notification::make()
                ->title('Cập nhật CCCD thành công!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Số CCCD không thay đổi.')
                ->warning()
                ->send();
        }
    }

    public function saveAvatar(): void
    {
        $user = auth()->user();
        $data = $this->avatarForm->getState();
        $token = session('company_api_token');

        if (!empty($data['avatar']) && $data['avatar'] !== $user->avatar) {
            if ($token) {
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

            $user->avatar = $data['avatar'];
            $user->save();

            Notification::make()
                ->title('Cập nhật ảnh đại diện thành công!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Ảnh đại diện không thay đổi hoặc không hợp lệ.')
                ->warning()
                ->send();
        }
    }

    public function savePassword(): void
    {
        $user = auth()->user();
        $data = $this->passwordForm->getState();
        $token = session('company_api_token');

        if ($token) {
            $passRes = CompanyApiService::updatePassword($token, $data['old_password'], $data['new_password']);
            if (!$passRes['success']) {
                Notification::make()
                    ->title('Lỗi đổi mật khẩu: ' . $passRes['message'])
                    ->danger()
                    ->send();
                return;
            }
        } else {
            if (!Hash::check($data['old_password'], $user->password)) {
                Notification::make()
                    ->title('Mật khẩu hiện tại không chính xác.')
                    ->danger()
                    ->send();
                return;
            }
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        Notification::make()
            ->title('Đổi mật khẩu thành công!')
            ->success()
            ->send();

        $this->passwordData = [
            'old_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ];
        $this->passwordForm->fill($this->passwordData);
    }

    public function updateCccdFromScan(string $cccd, ?string $name = null): void
    {
        $user = auth()->user();
        $token = session('company_api_token');

        // Cập nhật CCCD form state
        $this->cccdData['cccd'] = $cccd;
        $this->cccdForm->fill($this->cccdData);

        // Cập nhật tên nếu có
        if ($name && $name !== $user->name) {
            $this->profileData['name'] = $name;
            $this->profileForm->fill($this->profileData);
            
            if ($token) {
                CompanyApiService::updateProfile($token, [
                    'name' => $name,
                    'phone' => $user->phone,
                    'zalo' => $user->zalo,
                ]);
            }
            $user->name = $name;
        }

        // Cập nhật CCCD
        if ($cccd !== $user->cccd) {
            if ($token) {
                CompanyApiService::updateCccd($token, $cccd);
            }
            $user->cccd = $cccd;
        }

        $user->save();

        Notification::make()
            ->title('Nhận diện và cập nhật thông tin CCCD thành công!')
            ->success()
            ->send();
    }
}
