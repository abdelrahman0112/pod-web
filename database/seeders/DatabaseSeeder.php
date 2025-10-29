<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create 5 test users
        User::firstOrCreate(
            ['email' => 'sarah.johnson@example.com'],
            [
                'name' => 'Sarah Johnson',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'password' => bcrypt('password'),
                'bio' => 'Data Scientist passionate about machine learning and data analytics. Love solving complex problems with data.',
                'city' => 'San Francisco',
                'country' => 'USA',
                'skills' => ['Python', 'Machine Learning', 'Data Analysis', 'TensorFlow'],
                'experience_level' => 'senior',
            ]
        );

        User::firstOrCreate(
            ['email' => 'michael.chen@example.com'],
            [
                'name' => 'Michael Chen',
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'password' => bcrypt('password'),
                'bio' => 'Senior Data Engineer building robust data pipelines and infrastructure. Open source enthusiast.',
                'city' => 'New York',
                'country' => 'USA',
                'skills' => ['Python', 'Apache Spark', 'SQL', 'AWS', 'Data Engineering'],
                'experience_level' => 'senior',
            ]
        );

        User::firstOrCreate(
            ['email' => 'emma.williams@example.com'],
            [
                'name' => 'Emma Williams',
                'first_name' => 'Emma',
                'last_name' => 'Williams',
                'password' => bcrypt('password'),
                'bio' => 'Machine Learning Engineer creating AI solutions that make a difference. Python and TensorFlow expert.',
                'city' => 'Austin',
                'country' => 'USA',
                'skills' => ['Python', 'TensorFlow', 'Deep Learning', 'AI', 'PyTorch'],
                'experience_level' => 'mid',
            ]
        );

        User::firstOrCreate(
            ['email' => 'james.brown@example.com'],
            [
                'name' => 'James Brown',
                'first_name' => 'James',
                'last_name' => 'Brown',
                'password' => bcrypt('password'),
                'bio' => 'Data Analyst turning data into actionable insights. Visualization and storytelling with data.',
                'city' => 'Seattle',
                'country' => 'USA',
                'skills' => ['SQL', 'Tableau', 'Python', 'Data Visualization', 'Analytics'],
                'experience_level' => 'mid',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sophia.martinez@example.com'],
            [
                'name' => 'Sophia Martinez',
                'first_name' => 'Sophia',
                'last_name' => 'Martinez',
                'password' => bcrypt('password'),
                'bio' => 'Business Intelligence Specialist helping businesses make data-driven decisions. Expert in SQL and business analytics.',
                'city' => 'Chicago',
                'country' => 'USA',
                'skills' => ['SQL', 'Power BI', 'Business Analytics', 'Excel', 'Data Modeling'],
                'experience_level' => 'senior',
            ]
        );

        // Call other seeders
        $this->call([
            CategorySeeder::class,
            EventCategorySeeder::class,
            PostSeeder::class,
            EventSeeder::class,
            HackathonSeeder::class,
        ]);
    }
}
