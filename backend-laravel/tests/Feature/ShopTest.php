<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_page_can_be_rendered(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Filters');
        $response->assertSee('Category');
        $response->assertSee('Price');
    }

    public function test_shop_page_displays_products(): void
    {
        // Create a category and product
        $category = Category::factory()->create(['name' => 'Makeup']);
        $product = Product::factory()->create([
            'name' => 'Test Lipstick',
            'category_id' => $category->id,
            'price' => 20.00
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Test Lipstick');
        $response->assertSee('$20.00');
    }
}
