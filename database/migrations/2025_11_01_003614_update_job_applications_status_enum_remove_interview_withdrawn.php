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
            \DB::table('job_applications')
                ->whereIn('status', ['interview_scheduled', 'interviewed'])
                ->update(['status' => 'reviewed']);

            \DB::table('job_applications')
                ->where('status', 'withdrawn')
                ->update(['status' => 'rejected']);

            return;
        }

        Schema::table('job_applications', function (Blueprint $table) {
            // First, temporarily expand the enum to allow all values
            \DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM('pending', 'reviewed', 'interview_scheduled', 'interviewed', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending'");

            // Update existing records to map old statuses to new ones
            \DB::table('job_applications')
                ->where('status', 'interview_scheduled')
                ->orWhere('status', 'interviewed')
                ->update(['status' => 'reviewed']);

            \DB::table('job_applications')
                ->where('status', 'withdrawn')
                ->update(['status' => 'rejected']);

            // Now update the enum to only include the new values
            \DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Revert to old enum
            \DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM('pending', 'reviewed', 'interview_scheduled', 'interviewed', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending'");
        });
    }
};
