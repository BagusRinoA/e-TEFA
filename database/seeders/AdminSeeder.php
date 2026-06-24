<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed admin users to the database.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (User::where('username', 'admin')->exists()) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'full_name' => 'Administrator',
            'profile_visibility' => true,
            'email_notifications' => true,
            'forum_notifications' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin');
    }
}
