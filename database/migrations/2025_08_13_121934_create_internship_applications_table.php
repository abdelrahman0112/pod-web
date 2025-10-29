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
        Schema::create('internship_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->text('experience')->nullable();
            $table->text('skills');
            $table->text('interests');
            $table->date('availability_start');
            $table->date('availability_end');
            $table->text('motivation');
            $table->json('portfolio_links')->nullable();
            $table->enum('status', ['pending', 'under_review', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
    }
};
