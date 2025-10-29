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
        Schema::table('client_conversion_requests', function (Blueprint $table) {
            $table->dropColumn('business_registration_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_conversion_requests', function (Blueprint $table) {
            $table->text('business_registration_info')->nullable()->after('company_website');
        });
    }
};
