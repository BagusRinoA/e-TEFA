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
        Schema::table('redemption_transactions', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('quantity');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->text('shipping_address')->nullable()->after('recipient_phone');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code')->nullable()->after('shipping_city');
        });

        // Alter enum status
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE redemption_transactions MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum status
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE redemption_transactions MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");

        Schema::table('redemption_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code'
            ]);
        });
    }
};
