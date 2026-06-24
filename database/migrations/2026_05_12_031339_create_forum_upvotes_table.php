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
        Schema::create('forum_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->constrained('forum_questions')->onDelete('cascade');
            $table->foreignId('reply_id')->nullable()->constrained('forum_replies')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate upvotes
            $table->unique(['user_id', 'question_id']);
            $table->unique(['user_id', 'reply_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_upvotes');
    }
};
