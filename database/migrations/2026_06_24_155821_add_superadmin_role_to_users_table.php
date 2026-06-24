<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bersihkan nilai role yang tidak valid (sisa percobaan tinker) sebelum ubah enum
        DB::statement("UPDATE users SET role = 'user' WHERE role NOT IN ('user', 'admin')");

        // Tambah 'superadmin' ke enum role, tanpa ubah ke VARCHAR
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'superadmin') NOT NULL DEFAULT 'user'");

        // Set user ID 2 sebagai superadmin
        DB::statement("UPDATE users SET role = 'superadmin' WHERE id = 2");
    }

    public function down(): void
    {
        // Kembalikan superadmin ke admin sebelum hapus enum value
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'superadmin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }
};
