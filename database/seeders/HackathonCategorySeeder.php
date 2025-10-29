<?php

namespace Database\Seeders;

use App\Models\HackathonCategory;
use Illuminate\Database\Seeder;

class HackathonCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'AI & Machine Learning',
                'description' => 'Hackathons focused on artificial intelligence, machine learning, and neural networks',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Competitions involving data analysis, visualization, and insights',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Building web applications, APIs, and full-stack solutions',
                'color' => '#10B981',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Creating mobile apps for iOS, Android, or cross-platform',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Blockchain & Crypto',
                'description' => 'Decentralized applications, smart contracts, and cryptocurrency projects',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Security challenges, ethical hacking, and defensive solutions',
                'color' => '#EC4899',
            ],
            [
                'name' => 'IoT & Hardware',
                'description' => 'Internet of Things, embedded systems, and hardware projects',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Open Innovation',
                'description' => 'General tech challenges with open themes',
                'color' => '#6366F1',
            ],
        ];

        foreach ($categories as $category) {
            HackathonCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
