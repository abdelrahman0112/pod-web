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
        Schema::table('users', function (Blueprint $table) {
            // Split name into first_name and last_name
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');

            // Personal Information
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->string('country')->nullable()->after('city');
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable()->after('country');
            $table->text('bio')->nullable()->after('gender');
            $table->string('avatar')->nullable()->after('bio');
            $table->date('birthday')->nullable()->after('avatar');

            // Professional Information
            $table->json('skills')->nullable()->after('birthday');
            $table->enum('experience_level', ['entry', 'junior', 'mid', 'senior', 'expert'])->nullable()->after('skills');
            $table->json('education')->nullable()->after('experience_level');
            $table->json('portfolio_links')->nullable()->after('education');

            // Social Media Links
            $table->string('linkedin_url')->nullable()->after('portfolio_links');
            $table->string('github_url')->nullable()->after('linkedin_url');
            $table->string('twitter_url')->nullable()->after('github_url');
            $table->string('website_url')->nullable()->after('twitter_url');

            // OAuth Fields
            $table->string('google_id')->nullable()->after('website_url');
            $table->string('linkedin_id')->nullable()->after('google_id');
            $table->string('provider')->nullable()->after('linkedin_id');
            $table->string('provider_id')->nullable()->after('provider');

            // Profile Management
            $table->boolean('profile_completed')->default(false)->after('provider_id');
            $table->boolean('is_active')->default(true)->after('profile_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'city', 'country', 'gender',
                'bio', 'avatar', 'birthday', 'skills', 'experience_level',
                'education', 'portfolio_links', 'linkedin_url', 'github_url',
                'twitter_url', 'website_url', 'google_id', 'linkedin_id',
                'provider', 'provider_id', 'profile_completed', 'is_active',
            ]);
        });
    }
};
