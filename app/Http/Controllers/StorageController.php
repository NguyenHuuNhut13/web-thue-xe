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
        return response()->json([
            'debug' => true,
            'path' => $path,
            'local_exists' => file_exists(storage_path('app/public/' . $path)),
            'db_exists' => DB::table('stored_files')->where('path', $path)->exists(),
            'db_first' => DB::table('stored_files')->where('path', $path)->select('path', 'mime_type', 'size')->first(),
        ]);

        // Prevent path traversal attacks
        if (str_contains($path, '..')) {
            abort(403);
        }

        // 1. First check if the file exists on the local physical public storage disk
        $localPath = storage_path('app/public/' . $path);
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

        // Return the file content with appropriate headers
        return response($content)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000, immutable')
            ->header('Content-Length', strlen($content));
    }
}
