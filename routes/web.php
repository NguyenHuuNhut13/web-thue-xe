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

// Serve storage files from /tmp on Vercel
if (env('VERCEL')) {
    Route::get('/storage/{path}', function ($path) {
        if (str_contains($path, '..')) {
            abort(403);
        }
        
        $fullPath = '/tmp/storage/app/public/' . $path;
        
        if (!file_exists($fullPath)) {
            abort(404);
        }
        
        return response()->file($fullPath);
    })->where('path', '.*');
}

