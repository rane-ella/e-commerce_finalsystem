<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class GlowBabeProductsSeeder extends Seeder
{
    public function run()
    {
        // Ensure categories exist
        $lips = Category::firstOrCreate(['slug' => 'lips'], ['name' => 'Lips']);
        $face = Category::firstOrCreate(['slug' => 'face'], ['name' => 'Face']);
        $eyes = Category::firstOrCreate(['slug' => 'eyes'], ['name' => 'Eyes']);
        $skincare = Category::firstOrCreate(['slug' => 'skincare'], ['name' => 'Skincare']);

        $products = [
            [
                'name' => 'GlowBabe Soft Matte Lip Cream',
                'price' => 249.00,
                'description' => 'A lightweight, velvety lip cream that glides smoothly onto the lips and dries into a comfortable soft-matte finish. Highly pigmented and non-drying, perfect for everyday wear.',
                'category_id' => $lips->id,
                'image' => '/images/products/soft-matte-lip-cream.jpg',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Radiant Skin Tint SPF 25',
                'price' => 349.00,
                'description' => 'A breathable skin tint that evens out the complexion while still looking natural. Formulated with SPF 25 to protect your skin from daily sun exposure. Ideal for a glowy, fresh look.',
                'category_id' => $face->id,
                'image' => '/images/products/radiant-skin-tint.jpg',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Velvet Blush Pot',
                'price' => 199.00,
                'description' => 'A creamy blush pot that blends seamlessly into the skin, giving your cheeks a natural flushed glow. Available in universally flattering shades suitable for all skin tones.',
                'category_id' => $face->id,
                'image' => '/images/products/velvet-blush-pot.jpg',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Length & Lift Mascara',
                'price' => 279.00,
                'description' => 'A long-wearing mascara that lengthens, curls, and volumizes without clumping. Smudge-proof and perfect for all-day wear.',
                'category_id' => $eyes->id,
                'image' => '/images/products/length-lift-mascara.png',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Hydrating Lip Oil',
                'price' => 159.00,
                'description' => 'A nourishing lip oil infused with jojoba and vitamin E. Provides instant hydration and a glossy finish without feeling sticky.',
                'category_id' => $lips->id,
                'image' => '/images/products/hydrating-lip-oil.png',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Light & Airy Loose Setting Powder',
                'price' => 299.00,
                'description' => 'A finely milled setting powder that controls shine and sets makeup without caking. Leaves your skin smooth, matte, and photo-ready.',
                'category_id' => $face->id,
                'image' => '/images/products/loose-setting-powder.png',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Dewy Fix Setting Spray',
                'price' => 199.00,
                'description' => 'A refreshing setting spray that keeps your makeup in place while giving your skin a dewy, radiant glow. Ideal for dry or dull skin.',
                'category_id' => $face->id,
                'image' => '/images/products/dewy-setting-spray.jpg',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Brow Sculpt Gel',
                'price' => 179.00,
                'description' => 'A clear, long-wearing brow gel that lifts and sets brows in place all day. Perfect for achieving a natural, fluffy brow look.',
                'category_id' => $eyes->id,
                'image' => '/images/products/brow-sculpt-gel.png',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Eyeshadow Quad – “Warm Bloom”',
                'price' => 229.00,
                'description' => 'A compact eyeshadow palette featuring four warm-toned shades: shimmer champagne, rose gold, soft brown, and deep cocoa. Easy to blend and perfect for daily glam.',
                'category_id' => $eyes->id,
                'image' => '/images/products/eyeshadow-quad-warm-bloom.jpg',
                'stock' => 100,
            ],
            [
                'name' => 'GlowBabe Nourishing Night Serum',
                'price' => 399.00,
                'description' => 'A lightweight night serum infused with hyaluronic acid and niacinamide. Helps repair, hydrate, and brighten the skin while you sleep.',
                'category_id' => $skincare->id,
                'image' => '/images/products/nourishing-night-serum.png',
                'stock' => 100,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}
