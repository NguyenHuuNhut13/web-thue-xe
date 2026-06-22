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

// FAQ routes
Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');
Route::post('/faq/ai-answer', [App\Http\Controllers\FaqController::class, 'getAiAnswer'])->name('faq.ai-answer');

// Chatbot routes
Route::post('/api/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'message'])->name('chatbot.message');
Route::post('/api/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearHistory'])->name('chatbot.clear');

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

// Diagnostic route for Gemini API
Route::get('/test-gemini-nks', function() {
    $apiKey = env('GEMINI_API_KEY');
    if (empty($apiKey)) {
        return 'Thất bại: Biến môi trường GEMINI_API_KEY chưa được cấu hình hoặc bị trống trong file .env hoặc trên Vercel.';
    }
    
    $len = strlen($apiKey);
    $maskedKey = substr($apiKey, 0, min(5, $len)) . '...' . substr($apiKey, max(0, $len - 5));
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . $apiKey;
    
    $systemInstruction = "You are a helpful car maintenance assistant. Speak in Vietnamese.";
    $prompt = "Tại sao nên thay nhớt định kỳ?";
    
    $payload = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'systemInstruction' => [
            'parts' => [
                ['text' => $systemInstruction]
            ]
        ],
        'generationConfig' => [
            'thinkingConfig' => [
                'thinkingLevel' => 'minimal'
            ]
        ]
    ];
    
    $statusWithInstruction = null;
    $responseBodyWithInstruction = null;
    
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(25)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);
        
        $statusWithInstruction = $res->status();
        $responseBodyWithInstruction = $res->json() ?: $res->body();
    } catch (\Exception $e) {
        $responseBodyWithInstruction = "Exception: " . $e->getMessage();
    }
    
    // Test the actual GeminiService method
    $serviceResponse = null;
    try {
        $serviceResponse = \App\Services\GeminiService::getAiAnswerForFaq($prompt, "Để bôi trơn động cơ.");
    } catch (\Exception $e) {
        $serviceResponse = "Exception in service: " . $e->getMessage();
    }
    
    return response()->json([
        'api_key_masked' => $maskedKey,
        'api_key_length' => $len,
        'system_instruction_test' => [
            'status_code' => $statusWithInstruction,
            'response' => $responseBodyWithInstruction
        ],
        'actual_service_test_response' => $serviceResponse
    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
});


