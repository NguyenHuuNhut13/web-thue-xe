<?php

namespace App\Filament\Member\Pages;

use Filament\Pages\Page;
use App\Services\CompanyApiService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use Livewire\Attributes\Url;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Hồ sơ';

    protected static ?string $title = 'Hồ sơ';

    protected static ?string $slug = 'profile';

    protected string $view = 'filament.member.pages.profile';

    #[Url]
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
            'name' => $user->name,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'address' => $user->address,
            'issue_date' => $user->issue_date,
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

                TextInput::make('name')
                    ->label('Họ và tên')
                    ->placeholder('Ví dụ: NGUYỄN VĂN A')
                    ->required(),

                Grid::make(2)
                    ->schema([
                        Select::make('gender')
                            ->label('Giới tính')
                            ->options([
                                'Nam' => 'Nam',
                                'Nữ' => 'Nữ',
                            ])
                            ->placeholder('Chọn giới tính'),

                        TextInput::make('dob')
                            ->label('Ngày sinh')
                            ->placeholder('Ví dụ: 15/08/1995'),
                    ]),

                TextInput::make('issue_date')
                    ->label('Ngày cấp')
                    ->placeholder('Ví dụ: 20/10/2021'),

                TextInput::make('address')
                    ->label('Nơi thường trú')
                    ->placeholder('Địa chỉ thường trú trên CCCD'),
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

    /**
     * Lấy API token của company: ưu tiên từ session, fallback sang DB.
     * Điều này tránh mất token trên môi trường serverless (Vercel).
     */
    protected function getApiToken(): ?string
    {
        $token = session('company_api_token');
        if (!$token) {
            $token = auth()->user()->company_api_token ?? null;
            // Đồng bộ lại session nếu lấy từ DB
            if ($token) {
                session(['company_api_token' => $token]);
            }
        }
        return $token ?: null;
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
        $token = $this->getApiToken();

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
        $token = $this->getApiToken();

        if ($token) {
            $frontBase64 = '';
            if ($user->cccd_front && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->cccd_front)) {
                $frontBase64 = base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($user->cccd_front));
            }
            $backBase64 = '';
            if ($user->cccd_back && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->cccd_back)) {
                $backBase64 = base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($user->cccd_back));
            }

            $cccdRes = CompanyApiService::updateCccd($token, [
                'cccd' => $data['cccd'],
                'issue_date' => $data['issue_date'] ?? '',
                'address' => $data['address'] ?? '',
                'front_base64' => $frontBase64,
                'back_base64' => $backBase64,
            ]);

            if (!$cccdRes['success']) {
                Notification::make()
                    ->title('Lỗi cập nhật CCCD lên hệ thống: ' . $cccdRes['message'])
                    ->danger()
                    ->send();
                return;
            }
        }

        if ($data['name'] !== $user->name) {
            if ($token) {
                CompanyApiService::updateProfile($token, [
                    'name' => $data['name'],
                    'phone' => $user->phone,
                    'zalo' => $user->zalo,
                ]);
            }
            $user->name = $data['name'];
            $this->profileData['name'] = $data['name'];
            $this->profileForm->fill($this->profileData);
        }

        $user->cccd = $data['cccd'];
        $user->gender = $data['gender'];
        $user->dob = $data['dob'];
        $user->address = $data['address'];
        $user->issue_date = $data['issue_date'];
        $user->save();

        Notification::make()
            ->title('Cập nhật CCCD và thông tin liên quan thành công!')
            ->success()
            ->send();
    }

    public function saveAvatar(): void
    {
        $user = auth()->user();
        $data = $this->avatarForm->getState();
        $token = $this->getApiToken();

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
        $token = $this->getApiToken();

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

    public function updateCccdFromScan(
        string $cccd,
        ?string $issueDate = null,
        ?string $name = null,
        ?string $gender = null,
        ?string $dob = null,
        ?string $address = null,
        ?string $frontBase64 = null,
        ?string $backBase64 = null
    ): void {
        $user = auth()->user();
        $token = $this->getApiToken();

        // Lưu ảnh mặt trước và mặt sau cục bộ vào storage
        $frontPath = $user->cccd_front;
        $backPath = $user->cccd_back;

        // Tạo thư mục nếu chưa tồn tại
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('cccds')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('cccds');
        }

        if ($frontBase64) {
            $frontData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $frontBase64));
            $frontPath = 'cccds/' . $user->id . '_front.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($frontPath, $frontData);
        }
        if ($backBase64) {
            $backData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $backBase64));
            $backPath = 'cccds/' . $user->id . '_back.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($backPath, $backData);
        }

        // Cập nhật CCCD form state
        $this->cccdData = [
            'cccd' => $cccd,
            'name' => $name,
            'gender' => $gender,
            'dob' => $dob,
            'address' => $address,
            'issue_date' => $issueDate,
        ];
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

        // Cập nhật CCCD lên API
        if ($token) {
            CompanyApiService::updateCccd($token, [
                'cccd' => $cccd,
                'issue_date' => $issueDate,
                'address' => $address,
                'front_base64' => $frontBase64 ? preg_replace('#^data:image/\w+;base64,#i', '', $frontBase64) : '',
                'back_base64' => $backBase64 ? preg_replace('#^data:image/\w+;base64,#i', '', $backBase64) : '',
            ]);
        }

        $user->cccd = $cccd;
        $user->gender = $gender;
        $user->dob = $dob;
        $user->address = $address;
        $user->issue_date = $issueDate;
        $user->cccd_front = $frontPath;
        $user->cccd_back = $backPath;
        $user->save();

        Notification::make()
            ->title('Nhận diện và cập nhật thông tin CCCD thành công!')
            ->success()
            ->send();
    }
}
