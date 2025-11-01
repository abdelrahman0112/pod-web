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
        // Skip enum modification for SQLite (testing)
        if (\DB::getDriverName() === 'sqlite') {
            // For SQLite, just update the data
            \DB::table('internship_applications')
                ->where('status', 'under_review')
                ->update(['status' => 'reviewed']);

            return;
        }

        Schema::table('internship_applications', function (Blueprint $table) {
            // First, temporarily add 'reviewed' to the enum
            \DB::statement("ALTER TABLE internship_applications MODIFY COLUMN status ENUM('pending', 'under_review', 'reviewed', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending'");

            // Update existing 'under_review' records to 'reviewed'
            \DB::table('internship_applications')
                ->where('status', 'under_review')
                ->update(['status' => 'reviewed']);

            // Now update the enum to only include the new values
            \DB::statement("ALTER TABLE internship_applications MODIFY COLUMN status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            // Revert 'reviewed' back to 'under_review' if needed
            \DB::table('internship_applications')
                ->where('status', 'reviewed')
                ->update(['status' => 'under_review']);

            // Revert to old enum
            \DB::statement("ALTER TABLE internship_applications MODIFY COLUMN status ENUM('pending', 'under_review', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending'");
        });
    }
};
