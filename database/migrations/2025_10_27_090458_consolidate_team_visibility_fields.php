<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hackathon_teams', function (Blueprint $table) {
            // Consolidate is_looking_for_members and accepts_join_requests into is_public
            $table->boolean('is_public')->default(true)->after('project_repository');
        });

        // Migrate existing data: is_public = true if either is_looking_for_members or accepts_join_requests is true
        DB::table('hackathon_teams')
            ->update([
                'is_public' => DB::raw('COALESCE(is_looking_for_members, false) OR COALESCE(accepts_join_requests, true)'),
            ]);

        Schema::table('hackathon_teams', function (Blueprint $table) {
            $table->dropColumn(['is_looking_for_members', 'accepts_join_requests']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hackathon_teams', function (Blueprint $table) {
            $table->boolean('is_looking_for_members')->default(true)->after('project_repository');
            $table->boolean('accepts_join_requests')->default(true)->after('is_looking_for_members');
        });

        // Migrate data back
        DB::table('hackathon_teams')
            ->update([
                'is_looking_for_members' => DB::raw('COALESCE(is_public, true)'),
                'accepts_join_requests' => DB::raw('COALESCE(is_public, true)'),
            ]);

        Schema::table('hackathon_teams', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};
