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
            if (Schema::hasColumn('hackathons', 'sponsor_id')) {
                $table->dropForeign(['sponsor_id']);
                $table->dropColumn('sponsor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hackathons', function (Blueprint $table) {
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
