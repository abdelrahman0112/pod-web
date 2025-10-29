<?php

namespace Database\Seeders;

use App\Models\Hackathon;
use App\Models\User;
use Illuminate\Database\Seeder;

class HackathonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users who can create hackathons (superadmin, admin, client)
        $creators = User::whereIn('role', ['superadmin', 'admin', 'client'])->get();

        if ($creators->isEmpty()) {
            // If no eligible users exist, create some
            $creators = collect([
                User::factory()->create(['role' => 'superadmin']),
                User::factory()->create(['role' => 'admin']),
                User::factory()->create(['role' => 'client']),
            ]);
        }

        // Create 10-15 hackathons with random creators
        $hackathonCount = rand(10, 15);

        foreach (range(1, $hackathonCount) as $index) {
            Hackathon::factory()->create([
                'created_by' => $creators->random()->id,
            ]);
        }
    }
}
