<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CompanyApiService
{
    protected static $baseUrl = 'https://account.nks.vn/api';

    /**
     * Gửi request POST tới API công ty kèm theo Logging chi tiết.
     */
    protected static function post($endpoint, array $data = [], $token = null)
    {
        $url = self::$baseUrl . '/' . ltrim($endpoint, '/');
        
        $request = Http::asJson();
        if ($token) {
            $request = $request->withToken($token);
        }

        Log::info("Company API Request: POST {$url}", [
            'payload' => collect($data)->except(['password', 'old_password', 'new_password', 'password_confirmation'])->toArray(),
            'has_token' => !empty($token)
        ]);

        try {
            $response = $request->post($url, $data);
            
            Log::info("Company API Response: {$response->status()}", [
                'body' => $response->json(),
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error("Company API Exception: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Đăng nhập thành viên
     */
    public static function login($email, $password)
    {
        try {
            // Thử gửi cả email và username để tương thích tối đa với API công ty
            $response = self::post('nks/user/login', [
                'email' => $email,
                'username' => $email, 
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Đọc chính xác token và user từ JSON phản hồi
                $token = $data['data']['access_token'] ?? $data['token'] ?? $data['access_token'] ?? $data['data']['token'] ?? null;
                $user = $data['data']['user'] ?? $data['user'] ?? $data['data'] ?? null;

                if ($token) {
                    return [
                        'success' => true,
                        'token' => $token,
                        'user' => $user
                    ];
                }
            }

            $message = $response->json()['error'] ?? $response->json()['message'] ?? 'Đăng nhập không thành công. Vui lòng kiểm tra lại tài khoản.';
            return [
                'success' => false,
                'message' => $message
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến máy chủ xác thực: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy thông tin thành viên (POST /api/nks/user)
     */
    public static function getProfile($token)
    {
        try {
            $response = self::post('nks/user', [], $token);

            if ($response->successful()) {
                $data = $response->json();
                $user = $data['data']['user'] ?? $data['user'] ?? $data['data'] ?? $data;
                return [
                    'success' => true,
                    'user' => $user
                ];
            }

            return [
                'success' => false,
                'message' => 'Không thể lấy thông tin thành viên từ API.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API lấy profile: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật thông tin thành viên (POST /api/nks/user)
     */
    public static function updateProfile($token, array $data)
    {
        try {
            $response = self::post('nks/user', $data, $token);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Cập nhật thông tin thành công!'
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Cập nhật thông tin thất bại.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API cập nhật thông tin: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật mật khẩu (POST /api/nks/user/updatePass)
     */
    public static function updatePassword($token, $oldPassword, $newPassword)
    {
        try {
            $response = self::post('nks/user/updatePass', [
                'old_password' => $oldPassword,
                'password' => $newPassword,
                'password_confirmation' => $newPassword, // Điền cả 2 định dạng phổ biến
                'new_password' => $newPassword
            ], $token);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Đổi mật khẩu thành công!'
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Đổi mật khẩu thất bại. Vui lòng kiểm tra lại mật khẩu cũ.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API đổi mật khẩu: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật ảnh đại diện (POST /api/nks/user/updateAvatar)
     */
    public static function updateAvatar($token, $avatarPath)
    {
        try {
            $url = self::$baseUrl . '/nks/user/updateAvatar';
            
            Log::info("Company API Request: Upload Avatar POST {$url}", ['path' => $avatarPath]);

            // Sử dụng Attach multipart để tải file lên API công ty
            $response = Http::withToken($token)
                ->attach('avatar', file_get_contents($avatarPath), basename($avatarPath))
                ->post($url);

            Log::info("Company API Response Upload Avatar: {$response->status()}", [
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $avatarUrl = $data['avatar'] ?? $data['avatar_url'] ?? $data['data']['avatar_url'] ?? null;
                return [
                    'success' => true,
                    'avatar_url' => $avatarUrl,
                    'message' => 'Cập nhật ảnh đại diện thành công!'
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Cập nhật ảnh đại diện thất bại.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API upload avatar: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật CCCD (POST /api/nks/user/updateCccd)
     */
    public static function updateCccd($token, $cccd)
    {
        try {
            $response = self::post('nks/user/updateCccd', [
                'cccd' => $cccd,
                'cccd_number' => $cccd // Dự phòng key khác nhau
            ], $token);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Cập nhật Căn cước công dân thành công!'
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Cập nhật CCCD thất bại.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối API cập nhật CCCD: ' . $e->getMessage()
            ];
        }
    }
}
