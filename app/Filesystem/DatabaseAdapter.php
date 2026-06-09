<?php

namespace App\Filesystem;

use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\UnableToReadFile;
use Illuminate\Support\Facades\DB;

class DatabaseAdapter implements FilesystemAdapter
{
    public function fileExists(string $path): bool
    {
        return DB::table('stored_files')->where('path', $path)->exists();
    }

    public function directoryExists(string $path): bool
    {
        return DB::table('stored_files')->where('path', 'like', $path . '/%')->exists();
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $mimeType = $this->detectMimeType($path, $contents);
        
        DB::table('stored_files')->updateOrInsert(
            ['path' => $path],
            [
                'content' => base64_encode($contents),
                'mime_type' => $mimeType,
                'size' => strlen($contents),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $data = stream_get_contents($contents);
        $this->write($path, $data, $config);
    }

    public function read(string $path): string
    {
        $file = DB::table('stored_files')->where('path', $path)->first();
        if (!$file) {
            throw new UnableToReadFile("File not found at path: " . $path);
        }
        return base64_decode($file->content);
    }

    public function readStream(string $path)
    {
        $file = DB::table('stored_files')->where('path', $path)->first();
        if (!$file) {
            throw new UnableToReadFile("File not found at path: " . $path);
        }
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, base64_decode($file->content));
        rewind($stream);
        return $stream;
    }

    public function delete(string $path): void
    {
        DB::table('stored_files')->where('path', $path)->delete();
    }

    public function deleteDirectory(string $path): void
    {
        DB::table('stored_files')->where('path', 'like', $path . '/%')->delete();
    }

    public function createDirectory(string $path, Config $config): void
    {
        // No-op for database storage
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // No-op
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        $file = DB::table('stored_files')->where('path', $path)->first();
        $mime = $file ? $file->mime_type : 'application/octet-stream';
        return new FileAttributes($path, null, null, null, $mime);
    }

    public function lastModified(string $path): FileAttributes
    {
        $file = DB::table('stored_files')->where('path', $path)->first();
        $timestamp = $file ? strtotime($file->updated_at) : time();
        return new FileAttributes($path, null, null, $timestamp);
    }

    public function fileSize(string $path): FileAttributes
    {
        $file = DB::table('stored_files')->where('path', $path)->first();
        $size = $file ? $file->size : 0;
        return new FileAttributes($path, $size);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $query = DB::table('stored_files');
        if ($path !== '') {
            $query->where('path', 'like', $path . '/%');
        }
        $files = $query->get();
        
        $attributes = [];
        foreach ($files as $file) {
            $attributes[] = new FileAttributes($file->path, $file->size);
        }
        return $attributes;
    }

    public function move(string $source, string $destination, Config $config): void
    {
        DB::table('stored_files')->where('path', $source)->update([
            'path' => $destination,
            'updated_at' => now(),
        ]);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $file = DB::table('stored_files')->where('path', $source)->first();
        if ($file) {
            DB::table('stored_files')->updateOrInsert(
                ['path' => $destination],
                [
                    'content' => $file->content,
                    'mime_type' => $file->mime_type,
                    'size' => $file->size,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    protected function detectMimeType(string $path, string $contents): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
        ];
        return $mimes[$extension] ?? 'application/octet-stream';
    }
}
