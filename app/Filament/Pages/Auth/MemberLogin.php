<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use App\Services\CompanyApiService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class MemberLogin extends BaseLogin
{
    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\Filament\Exceptions\RateLimitExceededException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        // 1. Gọi API công ty để xác thực thông tin đăng nhập
        $apiResult = CompanyApiService::login($data['email'], $data['password']);

        if (!$apiResult['success']) {
            throw ValidationException::withMessages([
                'data.email' => $apiResult['message'],
            ]);
        }

        $token = $apiResult['token'];
        $apiUser = $apiResult['user'];

        // Nếu API đăng nhập thành công nhưng chưa có thông tin user, gọi API lấy Profile
        if (empty($apiUser)) {
            $profileResult = CompanyApiService::getProfile($token);
            if ($profileResult['success']) {
                $apiUser = $profileResult['user'];
            }
        }

        // Cần đảm bảo có email để đối chiếu tài khoản cục bộ
        $email = $apiUser['email'] ?? $data['email'];

        // 2. Tìm hoặc tạo user cục bộ và đồng bộ thông tin từ API
        $user = User::where('email', $email)->first();

        $userData = [
            'name' => $apiUser['name'] ?? $apiUser['username'] ?? strtok($email, '@'),
            'phone' => $apiUser['phone'] ?? null,
            'zalo' => $apiUser['zalo'] ?? null,
            'avatar' => $apiUser['avatar'] ?? $apiUser['avatar_url'] ?? null,
            'cccd' => $apiUser['cccd'] ?? $apiUser['cccd_number'] ?? null,
            // Lưu hash password cục bộ để tương thích với auth của Laravel
            'password' => Hash::make($data['password']), 
        ];

        if ($user) {
            // Kiểm tra trạng thái khóa tài khoản cục bộ
            if ($user->status === 'blocked') {
                throw ValidationException::withMessages([
                    'data.email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ]);
            }
            $user->update($userData);
        } else {
            $userData['email'] = $email;
            $userData['role'] = 'member'; // Mặc định gán vai trò member
            $userData['status'] = 'active';
            $user = User::create($userData);
        }

        // 3. Lưu Access Token vào session
        session(['company_api_token' => $token]);

        // 4. Đăng nhập cục bộ trong Laravel
        Auth::login($user, $data['remember'] ?? false);

        session()->regenerate();

        return app(\Filament\Auth\Http\Responses\Contracts\LoginResponse::class);
    }
}
