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
        Schema::table('hackathons', function (Blueprint $table) {
            // Remove unwanted fields
            $table->dropColumn(['theme', 'judging_criteria', 'timeline', 'sponsors']);

            // Add sponsor field
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hackathons', function (Blueprint $table) {
            // Add back the removed fields
            $table->string('theme')->nullable();
            $table->json('judging_criteria')->nullable();
            $table->json('timeline')->nullable();
            $table->json('sponsors')->nullable();

            // Remove sponsor field
            $table->dropForeign(['sponsor_id']);
            $table->dropColumn('sponsor_id');
        });
    }
};
