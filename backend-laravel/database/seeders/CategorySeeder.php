<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        Category::truncate();

        $categories = [
            'Lips',
            'Face',
            'Skincare',
            'Eyes'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category, 'slug' => \Illuminate\Support\Str::slug($category)]);
        }
    }
}
