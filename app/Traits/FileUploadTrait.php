<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait FileUploadTrait
{
    public function uploadFilePublic(UploadedFile $file, string $folder = 'uploads', string $oldFile = null): string
    {
        if ($oldFile) {
            $oldFilePath = public_path($folder . '/' . $oldFile);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $filename);

        return $filename;
    }

    protected function deleteFile(string $file, string $folder = 'uploads'): void
    {
        $filePath = public_path($folder . '/' . $file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
