<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('redemption_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama item yang dapat ditukar');
            $table->text('description')->nullable()->comment('Deskripsi item');
            $table->bigInteger('points_cost')->comment('Biaya poin untuk menukar item ini');
            $table->integer('stock')->default(0)->comment('Jumlah stok tersedia');
            $table->string('image_url')->nullable()->comment('URL gambar item');
            $table->integer('max_redemption_per_user')->default(1)->comment('Maksimal redemption per user');
            $table->boolean('is_active')->default(true)->comment('Status apakah item aktif untuk ditukar');
            $table->timestamps();

            // Index
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_items');
    }
};
