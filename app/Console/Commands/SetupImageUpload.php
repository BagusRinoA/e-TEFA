<?php

namespace App\Console\Commands;

use App\Services\ImageUploadService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetupImageUpload extends Command
{
    protected $signature = 'image:setup';
    protected $description = 'Setup image upload directories dan symlink';

    public function handle(): int
    {
        $this->info('🚀 Setting up Image Upload System...');

        // 1. Create directories
        $this->info('📁 Creating image directories...');
        $directories = ['profiles', 'articles', 'products', 'forum', 'redemption-items'];

        foreach ($directories as $dir) {
            Storage::disk('public')->makeDirectory($dir, 0755, true);
            $this->line("  ✅ Created: {$dir}");
        }

        // 2. Setup symlink
        $this->info('🔗 Setting up storage symlink...');
        try {
            ImageUploadService::ensureSymlink();
            $this->line('  ✅ Symlink created successfully');
        } catch (\Exception $e) {
            $this->error("  ❌ Symlink error: {$e->getMessage()}");
            return 1;
        }

        // 3. Set permissions
        $this->info('🔐 Setting permissions...');
        try {
            $basePath = storage_path('app/public');
            chmod($basePath, 0755);

            foreach ($directories as $dir) {
                $dirPath = "{$basePath}/{$dir}";
                if (is_dir($dirPath)) {
                    chmod($dirPath, 0755);
                }
            }
            $this->line('  ✅ Permissions set correctly');
        } catch (\Exception $e) {
            $this->error("  ⚠️  Permission error: {$e->getMessage()}");
        }

        $this->info('✨ Image Upload System is ready!');
        $this->info('');
        $this->line('📝 Next steps:');
        $this->line('  1. Install Intervention/Image:');
        $this->line('     composer require intervention/image:^3.0');
        $this->line('  2. Update your controllers to use ImageUploadService');
        $this->line('  3. See IMAGE_UPLOAD_GUIDE.md for detailed instructions');

        return 0;
    }
}
