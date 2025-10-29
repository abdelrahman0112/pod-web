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
        Schema::table('hackathon_teams', function (Blueprint $table) {
            $table->boolean('accepts_join_requests')->default(true)->after('is_looking_for_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hackathon_teams', function (Blueprint $table) {
            $table->dropColumn('accepts_join_requests');
        });
    }
};
