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
        Schema::create('hackathons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('theme')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_deadline');
            $table->integer('max_participants')->nullable();
            $table->integer('max_team_size')->default(6);
            $table->integer('min_team_size')->default(2);
            $table->decimal('entry_fee', 10, 2)->default(0.00);
            $table->decimal('prize_pool', 12, 2)->nullable();
            $table->string('location')->nullable();
            $table->enum('format', ['online', 'on-site', 'hybrid'])->default('hybrid');
            $table->json('skill_requirements')->nullable();
            $table->json('technologies')->nullable();
            $table->text('rules')->nullable();
            $table->json('judging_criteria')->nullable();
            $table->json('timeline')->nullable();
            $table->json('sponsors')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['is_active', 'start_date']);
            $table->index(['registration_deadline', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hackathons');
    }
};
