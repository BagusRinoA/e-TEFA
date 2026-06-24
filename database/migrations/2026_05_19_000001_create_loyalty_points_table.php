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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->bigInteger('current_points')->default(0)->comment('Poin yang tersedia saat ini');
            $table->bigInteger('total_earned_points')->default(0)->comment('Total poin yang pernah diperoleh');
            $table->bigInteger('total_redeemed_points')->default(0)->comment('Total poin yang pernah ditukar');
            $table->timestamps();

            // Index untuk performa query
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
