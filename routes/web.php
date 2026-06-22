<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;

// Home Page Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Cars public routes
Route::get('/cars/suggest', [CarController::class, 'suggest'])->name('cars.suggest');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars-map', [CarController::class, 'map'])->name('cars.map');
Route::get('/yeu-thich', [CarController::class, 'favorites'])->name('cars.favorites');
Route::get('/cars/{slug}', [CarController::class, 'show'])->name('cars.show');
Route::post('/cars/{id}/favorite', [CarController::class, 'toggleFavorite'])->name('cars.favorite');
Route::post('/cars/{id}/book', [CarController::class, 'book'])->name('cars.book');

// Blogs routes
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Temporary route for Vercel database migration and seeding
Route::get('/run-migrations-nks', function () {
    try {
        \Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        return 'Tạo bảng và nạp dữ liệu Supabase thành công!<br><pre>' . \Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Lỗi: ' . $e->getMessage();
    }
});

// Run migrations only (safe, does not wipe database data)
Route::get('/run-migrate-only', function () {
    try {
        \Artisan::call('migrate', [
            '--force' => true,
        ]);
        return 'Chạy migrations thành công!<br><pre>' . \Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Lỗi: ' . $e->getMessage();
    }
});

// Test email sending directly to diagnose SMTP issues
Route::get('/test-email-send', function () {
    try {
        $adminEmail = env('TEST_ADMIN_EMAIL', env('ADMIN_EMAIL', 'admin@nks.vn'));
        \Illuminate\Support\Facades\Mail::raw('Đây là thư thử nghiệm cấu hình SMTP từ web Thuê xe NKS.', function ($message) use ($adminEmail) {
            $message->to($adminEmail)
                    ->subject('🧪 Test SMTP NKS');
        });
        return 'Gửi email test thành công tới: ' . $adminEmail . '! Cấu hình SMTP của bạn hoàn toàn chính xác.';
    } catch (\Exception $e) {
        return 'Gửi email thất bại. Lỗi SMTP: <br><pre>' . $e->getMessage() . '</pre>';
    }
});

// Serve storage files from the local disk or database fallback
Route::get('/storage/{path}', [App\Http\Controllers\StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');





// Debug: Test CCCD API trực tiếp (chỉ dùng khi debug, xóa sau)
Route::get('/test-cccd-api', function () {
    if (!auth()->check()) {
        return response()->json(['error' => 'Chưa đăng nhập'], 401);
    }
    $user  = auth()->user();
    $token = session('company_api_token') ?? $user->company_api_token ?? null;

    if (!$token) {
        return response()->json(['error' => 'Không có API token. Đăng nhập bằng tài khoản API trước.'], 403);
    }

    // Gọi updateCccd chỉ với text fields (không ảnh) để test
    $response = \Illuminate\Support\Facades\Http::asJson()
        ->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', [
            'access_token' => $token,
            'number'       => $user->cccd ?? '079195000000',
            'date'         => $user->issue_date ?? '',
            'place'        => $user->address ?? '',
            'cccd'         => $user->cccd ?? '079195000000',
            'front'        => '',
            'back'         => '',
        ]);

    return response()->json([
        'token_used'    => substr($token, 0, 20) . '...',
        'http_status'   => $response->status(),
        'api_response'  => $response->json(),
        'user_cccd'     => $user->cccd,
        'user_address'  => $user->address,
        'user_issue_date' => $user->issue_date,
    ]);
})->middleware('auth');
