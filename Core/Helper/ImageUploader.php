<?php

namespace Core\Helper;
use Core\Helper\Slug;

class ImageUploader {
    private static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private static array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private static int $maxSize = 10 * 1024 * 1024; // 10MB

    /**
     * Upload một hoặc nhiều ảnh (Tự động convert sang WebP và tạo nhiều kích thước)
     *
     * @param array $files $_FILES['image'] hoặc danh sách ảnh
     * @param array $sizes Mảng kích thước ảnh, ví dụ: ["200x300", "400x500"]
     * @return string|array|null Tên file (nếu 1 ảnh), mảng tên file (nếu nhiều ảnh), hoặc null nếu lỗi
     */
    public static function upload(array $files, array $sizes = []): string|array|null {
        $uploadedFiles = [];

        if (!is_array($files['name'])) {
            return self::processSingleUpload($files, $sizes);
        }

        foreach ($files['name'] as $key => $name) {
            $fileData = [
                'name' => $files['name'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'size' => $files['size'][$key],
                'error' => $files['error'][$key]
            ];
            $fileName = self::processSingleUpload($fileData, $sizes);
            if ($fileName) {
                $uploadedFiles[] = $fileName;
            }
        }

        return !empty($uploadedFiles) ? (count($uploadedFiles) === 1 ? $uploadedFiles[0] : $uploadedFiles) : null;
    }

    private static function processSingleUpload(array $file, array $sizes): ?string {
        if (!isset($file['name']) || !isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > self::$maxSize) {
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowedExtensions)) {
            return null;
        }

        $mimeType = mime_content_type($file['tmp_name']);
        if (!in_array($mimeType, self::$allowedMimeTypes)) {
            return null;
        }

        $uploadDir = "uploads/" . date("Y/m/d/");
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $sluggedName = Slug::createSlug($originalName);
        $timestamp = time();
        $baseFileName = "$timestamp-$sluggedName.webp";
        $originalFile = "$uploadDir$baseFileName";

        if (!self::convertToWebp($file['tmp_name'], $originalFile, $extension)) {
            return null;
        }

        foreach ($sizes as $size) {
            [$width, $height] = explode('x', $size);
            $width = (int)$width;
            $height = (int)$height;
            $resizedFile = "$uploadDir{$size}-$baseFileName";
            self::convertToWebp($file['tmp_name'], $resizedFile, $extension, $width, $height);
        }

        return $baseFileName;
    }

    private static function convertToWebp(string $sourceFile, string $targetFile, string $extension, int $newWidth = null, int $newHeight = null): bool {
        list($width, $height) = getimagesize($sourceFile);
        
        if (!$newWidth || !$newHeight) {
            $newWidth = $width;
            $newHeight = $height;
        }

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($sourceFile);
                break;
            case 'png':
                $image = imagecreatefrompng($sourceFile);
                break;
            case 'gif':
                $image = imagecreatefromgif($sourceFile);
                break;
            case 'webp':
                $image = imagecreatefromwebp($sourceFile);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $saveSuccess = imagewebp($newImage, $targetFile, 100);

        imagedestroy($image);
        imagedestroy($newImage);

        return $saveSuccess;
    }
}
