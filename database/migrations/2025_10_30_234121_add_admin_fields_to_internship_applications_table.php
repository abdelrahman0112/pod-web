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
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->text('admin_response')->nullable()->after('status');
            $table->text('admin_notes')->nullable()->after('admin_response');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null')->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_response', 'admin_notes', 'admin_id']);
        });
    }
};
