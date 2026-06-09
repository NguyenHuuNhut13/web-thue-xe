<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    /**
     * Serve storage files from the local disk or database.
     *
     * @param string $path
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function serve(string $path)
    {
        // Prevent path traversal attacks
        if (str_contains($path, '..')) {
            abort(403);
        }

        // 1. First check if the file exists on the local physical public storage disk
        $localRoot = config('filesystems.disks.public.root');
        $localPath = ($localRoot ? rtrim($localRoot, '/') : storage_path('app/public')) . '/' . $path;
        
        if (file_exists($localPath)) {
            return response()->file($localPath);
        }

        // 2. Fall back to checking the database
        $file = DB::table('stored_files')->where('path', $path)->first();
        if (!$file) {
            abort(404);
        }

        $content = base64_decode($file->content);
        $mimeType = $file->mime_type ?? 'application/octet-stream';

        // 3. Cache the file locally for future high-speed requests
        try {
            $dir = dirname($localPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($localPath, $content);
        } catch (\Throwable $e) {
            // Silently ignore local write cache failures
        }

        // Return the file content with appropriate headers
        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('Content-Length', strlen($content));
    }
}
