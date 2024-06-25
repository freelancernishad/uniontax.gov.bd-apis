<?php
// FileHelper.php

namespace App\helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Upload a file and return the file path.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $allowedMimeTypes
     * @param int $maxFileSize
     * @param string $disk
     * @return string|null
     */
    public static function uploadFile(UploadedFile $file, $directory, $allowedMimeTypes = [], $maxFileSize = 10240, $disk = 'protected')
    {
        // Check if a file is provided
        if (!$file) {
            return null;
        }

        // Validate file mime types
        if (!empty($allowedMimeTypes) && !in_array($file->getClientMimeType(), $allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type.');
        }

        // Validate file size
        if ($file->getSize() > $maxFileSize) {
            throw new \InvalidArgumentException('File size exceeds the limit.');
        }

        // Generate unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Store the file and get the file path
        $filePath = $file->storeAs($directory, $fileName, $disk);

        return $filePath;
    }
}
