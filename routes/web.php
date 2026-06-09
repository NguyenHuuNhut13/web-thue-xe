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

// Serve storage files from the local disk or database fallback
Route::get('/storage/{path}', [App\Http\Controllers\StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// Test route for the custom database storage driver
Route::get('/test-storage', function () {
    try {
        $path = 'test-' . time() . '.txt';
        $content = 'Hello World at ' . now()->toDateTimeString();
        
        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $content);
        
        $existsInStorage = \Illuminate\Support\Facades\Storage::disk('public')->exists($path);
        $readFromStorage = \Illuminate\Support\Facades\Storage::disk('public')->get($path);
        
        $dbRecord = \Illuminate\Support\Facades\DB::table('stored_files')->where('path', $path)->first();
        
        \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        
        $existsAfterDelete = \Illuminate\Support\Facades\Storage::disk('public')->exists($path);
        $dbRecordAfterDelete = \Illuminate\Support\Facades\DB::table('stored_files')->where('path', $path)->first();
        
        return [
            'success' => true,
            'written_path' => $path,
            'exists_in_storage' => $existsInStorage,
            'read_content' => $readFromStorage,
            'db_record' => $dbRecord,
            'exists_after_delete' => $existsAfterDelete,
            'db_record_after_delete' => $dbRecordAfterDelete,
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }
});



