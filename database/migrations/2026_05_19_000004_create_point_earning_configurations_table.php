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
        Schema::create('point_earning_configurations', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_purchase_amount', 15, 2)->comment('Minimum nominal pembelanjaan');
            $table->decimal('max_purchase_amount', 15, 2)->nullable()->comment('Maximum nominal pembelanjaan (null = unlimited)');
            $table->bigInteger('points_earned')->comment('Poin yang diperoleh untuk range pembelanjaan ini');
            $table->text('description')->nullable()->comment('Deskripsi konfigurasi');
            $table->boolean('is_active')->default(true)->comment('Status apakah konfigurasi aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_earning_configurations');
    }
};
