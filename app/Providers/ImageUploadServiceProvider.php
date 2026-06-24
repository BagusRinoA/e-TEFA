<?php

namespace App\Providers;

use App\Services\ImageUploadService;
use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageUploadService::class, function ($app) {
            return new ImageUploadService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure storage symlink exists
        if ($this->app->environment('production')) {
            try {
                ImageUploadService::ensureSymlink();
            } catch (\Exception $e) {
                \Log::warning('Storage symlink setup failed: ' . $e->getMessage());
            }
        }
    }
}
