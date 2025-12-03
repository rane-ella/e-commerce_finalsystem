<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_products()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Makeup', 'slug' => 'makeup']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'stock' => 10,
            'image' => 'test.jpg'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('100.00');
    }

    public function test_add_to_cart_from_dashboard()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Makeup', 'slug' => 'makeup']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'stock' => 10,
            'image' => 'test.jpg'
        ]);

        $response = $this->actingAs($user)->post(route('cart.add', $product));

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
    }

    public function test_buy_now_redirects_to_checkout()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Makeup', 'slug' => 'makeup']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 100.00,
            'stock' => 10,
            'image' => 'test.jpg'
        ]);

        $response = $this->actingAs($user)->post(route('cart.add', $product), [
            'redirect_to' => 'checkout'
        ]);

        $response->assertRedirect(route('checkout'));
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);
    }
}
