<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'description' => 'This is a high-quality beauty product designed to enhance your natural glow. Made with premium ingredients for the best results.',
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}
