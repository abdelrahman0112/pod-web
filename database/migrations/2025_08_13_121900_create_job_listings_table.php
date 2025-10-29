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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('company_name');
            $table->text('company_description')->nullable();
            $table->enum('location_type', ['remote', 'on-site', 'hybrid'])->default('on-site');
            $table->string('location')->nullable(); // City/Country for on-site/hybrid
            $table->string('salary_min')->nullable();
            $table->string('salary_max')->nullable();
            $table->string('salary_currency', 3)->default('EGP');
            $table->json('required_skills'); // Array of skills
            $table->enum('experience_level', ['entry', 'junior', 'mid', 'senior', 'expert']);
            $table->date('application_deadline');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'closed', 'archived'])->default('active');
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'application_deadline']);
            $table->index(['category_id', 'status']);
            $table->index('posted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
