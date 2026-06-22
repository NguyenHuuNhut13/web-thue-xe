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

    // Convert date sang YYYY-MM-DD
    $rawDate    = $user->issue_date ?? '';
    $parsedDate = '';
    if ($rawDate) {
        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y'] as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, $rawDate);
            if ($dt && $dt->format($fmt) === $rawDate) {
                $parsedDate = $dt->format('Y-m-d');
                break;
            }
        }
        if (!$parsedDate) $parsedDate = $rawDate;
    }

    $base = ['access_token' => $token];

    // Test A: JSON (như cũ - luôn 500)
    $rA = \Illuminate\Support\Facades\Http::asJson()->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', array_merge($base, [
            'number' => $user->cccd,
            'date'   => $parsedDate,
            'place'  => $user->address,
        ]));

    // Test B: Form data (asForm thay vì asJson)
    $rB = \Illuminate\Support\Facades\Http::asForm()->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', array_merge($base, [
            'number' => $user->cccd,
            'date'   => $parsedDate,
            'place'  => $user->address,
        ]));

    // Test C: Chỉ access_token (không data gì khác) - kiểm tra auth có OK không
    $rC = \Illuminate\Support\Facades\Http::asJson()->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', ['access_token' => $token]);

    // Test D: Form data - chỉ access_token
    $rD = \Illuminate\Support\Facades\Http::asForm()->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', ['access_token' => $token]);

    // Test E: Form data với field "cccd" thay vì "number"
    $rE = \Illuminate\Support\Facades\Http::asForm()->withToken($token)
        ->post('https://account.nks.vn/api/nks/user/updateCccd', array_merge($base, [
            'cccd'   => $user->cccd,
            'date'   => $parsedDate,
            'place'  => $user->address,
        ]));

    return response()->json([
        'testA_json_with_data'       => ['status' => $rA->status(), 'response' => $rA->json()],
        'testB_form_with_data'       => ['status' => $rB->status(), 'response' => $rB->json()],
        'testC_json_only_token'      => ['status' => $rC->status(), 'response' => $rC->json()],
        'testD_form_only_token'      => ['status' => $rD->status(), 'response' => $rD->json()],
        'testE_form_cccd_field'      => ['status' => $rE->status(), 'response' => $rE->json()],
        'date_raw'                   => $rawDate,
        'date_converted'             => $parsedDate,
    ]);
})->middleware('auth');

