<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_is_accessible()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ]);

        $response = $this->actingAs($user)->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.index');
    }

    public function test_user_can_place_order()
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'address' => '123 Test St'
        ]);
        $product = Product::factory()->create(['price' => 100]);
        
        $cart = Cart::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price
        ]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'payment_method' => 'cod',
            'notes' => 'Test order'
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => 250.00, // 200 + 50 shipping
            'payment_method' => 'cod',
            'notes' => 'Test order'
        ]);
    }
}
