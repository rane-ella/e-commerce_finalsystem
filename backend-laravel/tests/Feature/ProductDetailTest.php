<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products/1');

        $response->assertStatus(200);
        $response->assertSee('SilkSkin Serum');
        $response->assertSee('$48.00');
        $response->assertSee('Add To Cart');
        $response->assertSee('Buy Now');
        $response->assertSee('Description');
        $response->assertSee('Additional Information');
        $response->assertSee('Reviews');
    }
}
