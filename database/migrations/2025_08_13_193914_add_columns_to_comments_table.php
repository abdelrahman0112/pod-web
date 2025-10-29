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
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->onDelete('cascade')->after('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('post_id');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade')->after('user_id');
            $table->text('content')->after('parent_id');
            $table->boolean('is_approved')->default(true)->after('content');

            $table->index(['post_id', 'is_approved']);
            $table->index(['user_id', 'created_at']);
            $table->index(['parent_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['post_id', 'is_approved']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['parent_id', 'created_at']);
            $table->dropColumn(['post_id', 'user_id', 'parent_id', 'content', 'is_approved']);
        });
    }
};
