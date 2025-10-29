<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'color' => '#3B82F6',
                'description' => 'Tech events, coding workshops, and digital innovation',
            ],
            [
                'name' => 'Business',
                'color' => '#10B981',
                'description' => 'Business networking, entrepreneurship, and professional development',
            ],
            [
                'name' => 'Education',
                'color' => '#F59E0B',
                'description' => 'Educational workshops, training sessions, and learning opportunities',
            ],
            [
                'name' => 'Healthcare',
                'color' => '#EF4444',
                'description' => 'Medical conferences, health workshops, and wellness events',
            ],
            [
                'name' => 'Creative',
                'color' => '#8B5CF6',
                'description' => 'Design, arts, creative workshops, and cultural events',
            ],
            [
                'name' => 'Sports',
                'color' => '#06B6D4',
                'description' => 'Sports events, fitness workshops, and athletic activities',
            ],
        ];

        foreach ($categories as $category) {
            EventCategory::create($category);
        }
    }
}
