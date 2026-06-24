<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageUploadService
{
    protected ImageManager $manager;
    protected array $supportedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    protected array $supportedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function __construct()
    {
        // Initialize dengan GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload dan optimasi gambar
     *
     * @param UploadedFile $file
     * @param string $folder - Folder destinasi (profiles, articles, products, dll)
     * @param array $options - Options untuk resize [width, height, quality, format]
     * @return string Path file yang disimpan (relative path dari public storage)
     * @throws Exception
     */
    public function upload(UploadedFile $file, string $folder, array $options = []): string
    {
        try {
            // 1. Validasi file
            $this->validateFile($file);

            // 2. Tentukan opsi default berdasarkan folder
            $options = $this->getDefaultOptions($folder, $options);

            // 3. Baca file content (lebih aman daripada file path)
            $fileContent = file_get_contents($file->getRealPath());
            if (!$fileContent) {
                throw new Exception('Gagal membaca file image');
            }

            // 4. Buat image dari content
            $image = $this->manager->read($fileContent);

            // 5. Validasi dimensi
            $this->validateDimensions($image, $options);

            // 6. Optimasi image
            $image = $this->optimizeImage($image, $options);

            // 7. Generate nama file
            $filename = $this->generateFilename($file, $options['format'] ?? 'jpg');

            // 8. Simpan file
            $path = $this->storeImage($image, $folder, $filename, $options);

            return $path;
        } catch (Exception $e) {
            \Log::error('Image upload failed', [
                'file' => $file->getClientOriginalName(),
                'folder' => $folder,
                'error' => $e->getMessage(),
            ]);
            throw new Exception('Gagal mengupload gambar: ' . $e->getMessage());
        }
    }

    /**
     * Delete image dari storage
     */
    public function delete(string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get URL untuk image
     */
    public function url(string $path): ?string
    {
        if ($path) {
            return Storage::disk('public')->url($path);
        }
        return null;
    }

    /**
     * Validasi file upload
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new Exception('File tidak valid atau error saat upload');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->supportedMimes)) {
            throw new Exception("Format gambar tidak didukung. Gunakan: JPG, PNG, GIF, atau WebP. (Received: {$mimeType})");
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->supportedExtensions)) {
            throw new Exception("Ekstensi file tidak didukung: {$extension}");
        }

        // Check file size (max 10MB default)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new Exception('Ukuran gambar terlalu besar. Maksimal 10MB');
        }
    }

    /**
     * Validasi dimensi gambar
     */
    protected function validateDimensions($image, array $options): void
    {
        $minWidth = $options['min_width'] ?? 100;
        $minHeight = $options['min_height'] ?? 100;

        if ($image->width() < $minWidth || $image->height() < $minHeight) {
            throw new Exception("Ukuran gambar terlalu kecil. Minimal {$minWidth}x{$minHeight}px");
        }
    }

    /**
     * Dapatkan opsi default berdasarkan folder
     */
    protected function getDefaultOptions(string $folder, array $customOptions): array
    {
        $defaults = [
            'profiles' => [
                'width' => 500,
                'height' => 500,
                'quality' => 85,
                'format' => 'jpg',
                'min_width' => 200,
                'min_height' => 200,
            ],
            'articles' => [
                'width' => 800,
                'height' => 400,
                'quality' => 80,
                'format' => 'jpg',
                'min_width' => 400,
                'min_height' => 200,
            ],
            'products' => [
                'width' => 600,
                'height' => 600,
                'quality' => 85,
                'format' => 'jpg',
                'min_width' => 300,
                'min_height' => 300,
            ],
            'forum' => [
                'width' => 800,
                'height' => 600,
                'quality' => 80,
                'format' => 'jpg',
                'min_width' => 400,
                'min_height' => 300,
            ],
            'redemption-items' => [
                'width' => 500,
                'height' => 500,
                'quality' => 85,
                'format' => 'jpg',
                'min_width' => 200,
                'min_height' => 200,
            ],
        ];

        $options = $defaults[$folder] ?? $defaults['products'];

        // Merge dengan custom options
        return array_merge($options, array_filter($customOptions));
    }

    /**
     * Optimasi gambar (resize, format, quality)
     */
    protected function optimizeImage($image, array $options)
    {
        $width = $options['width'];
        $height = $options['height'];

        // Resize dengan aspect ratio (fit)
        $image = $image->scaleDown(
            width: $width,
            height: $height
        );

        // Optimasi untuk format tertentu
        $format = $options['format'] ?? 'jpg';
        $quality = $options['quality'] ?? 80;

        switch ($format) {
            case 'webp':
                $image = $image->toWebp($quality);
                break;
            case 'png':
                $image = $image->toPng();
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $image = $image->toJpeg($quality);
                break;
        }

        return $image;
    }

    /**
     * Generate nama file yang unique dan aman
     */
    protected function generateFilename(UploadedFile $file, string $format): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitized = Str::slug($originalName);
        $random = Str::random(8);
        $timestamp = now()->timestamp;

        return "{$sanitized}_{$timestamp}_{$random}.{$format}";
    }

    /**
     * Simpan image ke storage
     */
    protected function storeImage($image, string $folder, string $filename, array $options): string
    {
        $disk = Storage::disk('public');

        // Ensure folder exists
        if (!$disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        // Save image - convert to string
        $path = "{$folder}/{$filename}";
        $disk->put($path, (string) $image);

        // Set proper permissions
        chmod(storage_path("app/public/{$path}"), 0644);

        return $path;
    }

    /**
     * Validate dan setup storage symlink
     */
    public static function ensureSymlink(): void
    {
        $target = storage_path('app/public');
        $link = public_path('storage');

        if (!is_link($link)) {
            if (is_dir($link)) {
                // Symlink sudah ada sebagai folder, hapus dulu
                rmdir($link);
            }
            symlink($target, $link);
            \Log::info('Storage symlink created successfully');
        }
    }

    /**
     * Get file info (untuk debugging)
     */
    public function getFileInfo(string $path): array
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            return [];
        }

        return [
            'path' => $path,
            'url' => $disk->url($path),
            'size' => $disk->size($path),
            'last_modified' => $disk->lastModified($path),
            'mime_type' => $disk->mimeType($path),
        ];
    }
}
