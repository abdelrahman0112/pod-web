<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support modifying ENUM types directly
        // We'll use DB facade to handle this for compatibility
        // Gender: Update to only allow 'male' and 'female'
        DB::statement("
            UPDATE users 
            SET gender = NULL 
            WHERE gender NOT IN ('male', 'female')
        ");

        // Experience Level: Ensure values match the enum
        DB::statement("
            UPDATE users 
            SET experience_level = NULL 
            WHERE experience_level NOT IN ('entry', 'junior', 'mid', 'senior', 'expert')
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for data cleaning
    }
};
