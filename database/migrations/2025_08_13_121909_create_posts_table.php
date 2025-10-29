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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['text', 'image', 'url', 'poll'])->default('text');
            $table->text('content')->nullable();
            $table->json('images')->nullable(); // Array of image URLs
            $table->string('url')->nullable(); // For URL posts
            $table->string('url_title')->nullable(); // URL preview title
            $table->text('url_description')->nullable(); // URL preview description
            $table->string('url_image')->nullable(); // URL preview image
            $table->json('poll_options')->nullable(); // Poll options with vote counts
            $table->timestamp('poll_ends_at')->nullable();
            $table->json('hashtags')->nullable(); // Array of hashtags
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_published']);
            $table->index(['is_published', 'created_at']);
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
