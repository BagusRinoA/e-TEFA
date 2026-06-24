<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete all existing users (instead of truncate)
        User::query()->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create or update admin user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'full_name' => 'Administrator',
                'profile_visibility' => true,
                'email_notifications' => true,
                'forum_notifications' => true,
            ]
        );

        // Create or update test customer user
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'email' => 'user@example.com',
                'password' => Hash::make('user'),
                'role' => 'customer',
                'full_name' => 'Test User',
                'profile_visibility' => true,
                'email_notifications' => true,
                'forum_notifications' => true,
            ]
        );
    }
}
