<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'ranelaesgana@gmail.com',
            'role' => 'admin',
        ]);

        User::factory(5)->create(['role' => 'seller']);
        User::factory(10)->create(['role' => 'customer']);

        $this->call(CategorySeeder::class);
        Product::factory(20)->create();
        ProductImage::factory(40)->create();

        Cart::factory(10)->create()->each(function ($cart) {
            CartItem::factory(rand(1,3))->create(['cart_id' => $cart->id]);
        });

        Order::factory(10)->create()->each(function ($order) {
            OrderItem::factory(rand(1,3))->create(['order_id' => $order->id]);
        });

        Review::factory(15)->create();
    }
}
