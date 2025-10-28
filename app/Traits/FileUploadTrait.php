<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    /**
     * Upload file to the public folder (existing behavior)
     */
    public function uploadFilePublic(UploadedFile $file, string $folder = 'uploads', string $oldFile = null): string
    {
        if ($oldFile) {
            $this->deleteFilePublic($oldFile, $folder);
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $filename;
    }

    /**
     * Upload file to local storage (storage/app/public)
     */
    public function uploadFileLocal(UploadedFile $file, string $folder = 'uploads', string $oldFile = null): string
    {
        if ($oldFile) {
            $this->deleteFileLocal($oldFile, $folder);
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public'); // disk: public

        return $path; // e.g. uploads/abc123.jpg
    }

    /**
     * Upload file to Amazon S3
     */
    public function uploadFileS3(UploadedFile $file, string $folder = 'uploads', string $oldFile = null): string
    {
        if ($oldFile) {
            $this->deleteFileS3($oldFile);
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 's3'); // disk: s3

        // Get full URL from S3
        return Storage::disk('s3')->url($path);
    }

    /**
     * Delete file from public folder
     */
    protected function deleteFilePublic(string $file, string $folder = 'uploads'): void
    {
        $filePath = public_path($folder . '/' . $file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Delete file from local storage (storage/app/public)
     */
    protected function deleteFileLocal(string $file, string $folder = 'uploads'): void
    {
        $path = $folder . '/' . $file;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Delete file from Amazon S3
     */
    protected function deleteFileS3(string $filePath): void
    {
        if (Storage::disk('s3')->exists($filePath)) {
            Storage::disk('s3')->delete($filePath);
        }
    }
}
