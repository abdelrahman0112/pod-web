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
        // Backup existing data
        $hackathons = DB::table('hackathons')->get();

        // Add temporary columns for migration
        Schema::table('hackathons', function (Blueprint $table) {
            $table->string('skill_requirements_temp')->nullable();
            $table->string('format_temp')->nullable();
        });

        // Copy and fix data from json/enum columns to temp columns
        foreach ($hackathons as $hackathon) {
            $skillReq = $hackathon->skill_requirements;
            $format = $hackathon->format;

            // Remove JSON encoding if present
            if ($skillReq) {
                $skillReq = trim($skillReq, '"');
                $skillReq = json_decode($skillReq, true) ?? $skillReq;
            }

            DB::table('hackathons')
                ->where('id', $hackathon->id)
                ->update([
                    'skill_requirements_temp' => $skillReq,
                    'format_temp' => $format,
                ]);
        }

        // Drop old columns
        Schema::table('hackathons', function (Blueprint $table) {
            $table->dropColumn(['skill_requirements', 'format']);
        });

        // Rename temp columns to original names
        Schema::table('hackathons', function (Blueprint $table) {
            $table->renameColumn('skill_requirements_temp', 'skill_requirements');
            $table->renameColumn('format_temp', 'format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Backup existing data
        $hackathons = DB::table('hackathons')->get();

        // Add temporary columns
        Schema::table('hackathons', function (Blueprint $table) {
            $table->json('skill_requirements_temp')->nullable();
            $table->string('format_temp')->nullable();
        });

        // Copy data to temp columns
        foreach ($hackathons as $hackathon) {
            DB::table('hackathons')
                ->where('id', $hackathon->id)
                ->update([
                    'skill_requirements_temp' => $hackathon->skill_requirements,
                    'format_temp' => $hackathon->format,
                ]);
        }

        // Drop current columns
        Schema::table('hackathons', function (Blueprint $table) {
            $table->dropColumn(['skill_requirements', 'format']);
        });

        // Rename temp columns back
        Schema::table('hackathons', function (Blueprint $table) {
            $table->renameColumn('skill_requirements_temp', 'skill_requirements');
        });

        // Recreate format as enum
        Schema::table('hackathons', function (Blueprint $table) {
            $table->enum('format', ['online', 'on-site', 'hybrid'])->default('hybrid');
        });

        // Copy format data back
        foreach ($hackathons as $hackathon) {
            DB::table('hackathons')
                ->where('id', $hackathon->id)
                ->update(['format' => $hackathon->format_temp ?? 'hybrid']);
        }

        // Drop temp format column
        Schema::table('hackathons', function (Blueprint $table) {
            $table->dropColumn('format_temp');
        });
    }
};
