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

// Diagnostic route to view Vercel errors
Route::get('/view-errors-nks', function () {
    try {
        if (!\Schema::hasTable('vercel_errors')) {
            return 'Table vercel_errors does not exist yet.';
        }
        $errors = \DB::select('SELECT * FROM vercel_errors ORDER BY created_at DESC LIMIT 50');
        if (empty($errors)) {
            return 'No errors logged in the database yet.';
        }
        
        $output = "";
        foreach ($errors as $err) {
            $output .= "=== ERROR #" . $err->id . " (" . $err->created_at . ") ===\n";
            $output .= "Class: " . $err->class . "\n";
            $output .= "Message: " . $err->message . "\n";
            $output .= "File: " . $err->file . ":" . $err->line . "\n";
            $output .= "Trace:\n" . $err->trace . "\n\n";
        }
        return response($output, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    } catch (\Throwable $e) {
        return 'Failed to query errors: ' . $e->getMessage();
    }
});

// Diagnostic route to test storage disk writes
Route::get('/test-write-nks', function () {
    $results = [];
    
    // Test 1: Write to local disk via Storage facade
    try {
        \Illuminate\Support\Facades\Storage::disk('local')->put('test.txt', 'Hello Local Disk');
        $results['storage_local'] = 'SUCCESS: ' . \Illuminate\Support\Facades\Storage::disk('local')->get('test.txt');
    } catch (\Throwable $e) {
        $results['storage_local'] = 'FAIL: ' . $e->getMessage();
    }
    
    // Test 2: Write to public disk via Storage facade
    try {
        \Illuminate\Support\Facades\Storage::disk('public')->put('test.txt', 'Hello Public Disk');
        $results['storage_public'] = 'SUCCESS: ' . \Illuminate\Support\Facades\Storage::disk('public')->get('test.txt');
    } catch (\Throwable $e) {
        $results['storage_public'] = 'FAIL: ' . $e->getMessage();
    }

    // Test 3: Raw file_put_contents to /tmp
    try {
        $path = '/tmp/raw_test.txt';
        file_put_contents($path, 'Hello Raw /tmp');
        $results['raw_tmp'] = 'SUCCESS: ' . file_get_contents($path);
    } catch (\Throwable $e) {
        $results['raw_tmp'] = 'FAIL: ' . $e->getMessage();
    }

    // Test 4: Check directory permissions and paths
    $results['paths'] = [
        'local_root' => config('filesystems.disks.local.root'),
        'public_root' => config('filesystems.disks.public.root'),
        'is_vercel' => env('VERCEL') ? 'yes' : 'no',
        'is_dir_local' => is_dir(config('filesystems.disks.local.root') ?: '') ? 'yes' : 'no',
        'is_writable_local' => is_writable(config('filesystems.disks.local.root') ?: '') ? 'yes' : 'no',
        'is_dir_public' => is_dir(config('filesystems.disks.public.root') ?: '') ? 'yes' : 'no',
        'is_writable_public' => is_writable(config('filesystems.disks.public.root') ?: '') ? 'yes' : 'no',
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'memory_limit' => ini_get('memory_limit'),
    ];

    return response()->json($results);
});

