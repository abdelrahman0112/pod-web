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
            $table->string('avatar_color')->nullable()->after('avatar');
        });

        // Assign random colors to existing users using enum
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if (! $user->avatar_color) {
                $colorIndex = crc32($user->name) % count(\App\AvatarColor::cases());
                $color = \App\AvatarColor::byIndex($colorIndex);
                $user->avatar_color = $color->value;
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar_color');
        });
    }
};
