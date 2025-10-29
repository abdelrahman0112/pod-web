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
        Schema::create('hackathon_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('project_name')->nullable();
            $table->text('project_description')->nullable();
            $table->string('project_repository')->nullable();
            $table->boolean('is_looking_for_members')->default(true);
            $table->timestamps();

            $table->unique(['hackathon_id', 'name']); // Unique team names per hackathon
            $table->index(['hackathon_id', 'is_looking_for_members']);
            $table->index('leader_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hackathon_teams');
    }
};
