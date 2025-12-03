<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $categories = [
            'Electronics', 'Clothing', 'Books', 'Home & Garden', 
            'Sports', 'Toys', 'Beauty', 'Automotive', 'Music', 'Games'
        ];

        return [
            'name' => $this->faker->randomElement($categories),
        ];
    }
}
