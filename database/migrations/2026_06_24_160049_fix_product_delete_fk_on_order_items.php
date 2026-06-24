<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus FK constraint lama (RESTRICT) dan ganti dengan SET NULL
        // agar produk bisa dihapus meskipun masih ada di order_items
        DB::statement('ALTER TABLE order_items DROP FOREIGN KEY fk_restrict_oi_product');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN product_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE order_items ADD CONSTRAINT fk_oi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL ON UPDATE SET NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE order_items DROP FOREIGN KEY fk_oi_product');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN product_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE order_items ADD CONSTRAINT fk_restrict_oi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
    }
};
