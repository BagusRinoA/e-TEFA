<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ForumQuestion;
use App\Models\ForumReply;
use App\Policies\ForumQuestionPolicy;
use App\Policies\ForumReplyPolicy;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register ImageUploadService sebagai singleton
        $this->app->singleton(ImageUploadService::class, function ($app) {
            return new ImageUploadService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(ForumQuestion::class, ForumQuestionPolicy::class);
        Gate::policy(ForumReply::class, ForumReplyPolicy::class);

        // Pastikan storage symlink ada
        try {
            if (!is_link(public_path('storage'))) {
                ImageUploadService::ensureSymlink();
            }
        } catch (\Exception $e) {
            \Log::warning('Storage symlink check failed: ' . $e->getMessage());
        }
    }
}
