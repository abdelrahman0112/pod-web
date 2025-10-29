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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('cover_letter')->nullable();
            $table->json('additional_info')->nullable(); // Flexible field for job-specific questions
            $table->enum('status', [
                'pending', 'reviewed', 'interview_scheduled',
                'interviewed', 'accepted', 'rejected', 'withdrawn',
            ])->default('pending');
            $table->text('admin_notes')->nullable(); // Internal notes for hiring managers
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();

            // Ensure one application per user per job
            $table->unique(['job_listing_id', 'user_id']);
            $table->index(['job_listing_id', 'status']);
            $table->index('user_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
