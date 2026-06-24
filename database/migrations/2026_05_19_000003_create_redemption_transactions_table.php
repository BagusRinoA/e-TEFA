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
        Schema::create('redemption_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('redemption_item_id')->constrained('redemption_items')->onDelete('cascade');
            $table->bigInteger('points_spent')->comment('Poin yang digunakan untuk penukar');
            $table->integer('quantity')->default(1)->comment('Jumlah item yang ditukar');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->comment('Status penukarannya');
            $table->text('notes')->nullable()->comment('Catatan untuk penukarannya');
            $table->timestamp('redeemed_at')->nullable()->comment('Waktu penukarannya selesai');
            $table->timestamps();

            // Index
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_transactions');
    }
};
