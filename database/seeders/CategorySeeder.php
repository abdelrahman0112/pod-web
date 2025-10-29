<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::getDefaultCategories();

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryData['name'])],
                array_merge($categoryData, ['is_active' => true])
            );
        }

        $this->command->info('Default job categories have been seeded successfully!');
    }
}
