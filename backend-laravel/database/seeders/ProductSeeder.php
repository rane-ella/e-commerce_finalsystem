<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create Categories
        $lips = Category::firstOrCreate(['slug' => 'lips'], ['name' => 'Lips']);
        $face = Category::firstOrCreate(['slug' => 'face'], ['name' => 'Face']);
        $skincare = Category::firstOrCreate(['slug' => 'skincare'], ['name' => 'Skincare']);
        $eyes = Category::firstOrCreate(['slug' => 'eyes'], ['name' => 'Eyes']);

        // Create Products
        
        // 1. Soft Matte Lip Cream
        Product::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Soft Matte Lip Cream',
                'description' => 'Velvety smooth soft matte lip cream that delivers high-pigment color with a comfortable, non-drying finish. Long-wearing and lightweight.',
                'price' => 249.00,
                'category_id' => $lips->id,
                'image' => 'images/soft-matte-lip-cream.png',
                'stock' => 100
            ]
        );

        // 2. Flawless Finish Foundation
        Product::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Flawless Finish Foundation',
                'description' => 'Achieve a radiant, perfected complexion with GlowBabe Flawless Finish Foundation. This lightweight, buildable liquid foundation glides on smoothly, blurring imperfections and evening out skin tone for a naturally flawless look that lasts all day.',
                'price' => 499.00,
                'category_id' => $face->id,
                'image' => 'images/flawless-finish-foundation.png',
                'stock' => 100
            ]
        );

        // 3. Hydrating Day Cream
        Product::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Hydrating Day Cream',
                'description' => 'A luxurious, lightweight day cream designed to provide intense hydration and long-lasting moisture without feeling heavy. It absorbs quickly, leaving skin feeling soft, supple, and perfectly prepped for makeup. Ideal for daily use to maintain a healthy, radiant complexion.',
                'price' => 799.00,
                'category_id' => $skincare->id,
                'image' => 'images/hydrating-day-cream.png',
                'stock' => 100
            ]
        );

        // 4. Eyeshadow Palette - Warm Neutrals
        Product::updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Eyeshadow Palette - Warm Neutrals',
                'description' => 'Discover endless possibilities with this versatile eyeshadow palette, featuring a curated selection of warm, earthy neutral shades. Each shadow offers rich pigment, blendable formulas, and a mix of matte, shimmer, and metallic finishes for effortless day-to-night looks. Perfect for creating subtle enhancements or dramatic allure.',
                'price' => 650.00,
                'category_id' => $eyes->id,
                'image' => 'images/eyeshadow-palette.png',
                'stock' => 100
            ]
        );
        // 5. GlowBabe Length & Lift Mascara
        Product::updateOrCreate(
            ['name' => 'GlowBabe Length & Lift Mascara'],
            [
                'description' => 'A long-wearing mascara that lengthens, curls, and volumizes without clumping. Smudge-proof and perfect for all-day wear.',
                'price' => 279.00,
                'category_id' => $eyes->id,
                'image' => 'images/length-lift-mascara.png',
                'stock' => 100
            ]
        );

        // 6. GlowBabe Radiant Skin Tint SPF 25
        Product::updateOrCreate(
            ['name' => 'GlowBabe Radiant Skin Tint SPF 25'],
            [
                'description' => 'A breathable skin tint that evens out the complexion while still looking natural. Formulated with SPF 25 to protect your skin from daily sun exposure. Ideal for a glowy, fresh look.',
                'price' => 349.00,
                'category_id' => $face->id,
                'image' => 'images/radiant-skin-tint.png',
                'stock' => 100
            ]
        );

        // 7. GlowBabe Velvet Blush Pot
        Product::updateOrCreate(
            ['name' => 'GlowBabe Velvet Blush Pot'],
            [
                'description' => 'A creamy blush pot that blends seamlessly into the skin, giving your cheeks a natural flushed glow. Available in universally flattering shades suitable for all skin tones.',
                'price' => 199.00,
                'category_id' => $face->id,
                'image' => 'images/velvet-blush-pot.jpg',
                'stock' => 100
            ]
        );
        // 8. GlowBabe Dewy Fix Setting Spray
        Product::updateOrCreate(
            ['name' => 'GlowBabe Dewy Fix Setting Spray'],
            [
                'description' => 'A refreshing setting spray that keeps your makeup in place while giving your skin a dewy, radiant glow. Ideal for dry or dull skin.',
                'price' => 199.00,
                'category_id' => $face->id,
                'image' => 'images/dewy-fix-setting-spray.jpg',
                'stock' => 100
            ]
        );

        // 9. GlowBabe Light & Airy Loose Setting Powder
        Product::updateOrCreate(
            ['name' => 'GlowBabe Light & Airy Loose Setting Powder'],
            [
                'description' => 'A finely milled setting powder that controls shine and sets makeup without caking. Leaves your skin smooth, matte, and photo-ready.',
                'price' => 299.00,
                'category_id' => $face->id,
                'image' => 'images/loose-setting-powder.png',
                'stock' => 100
            ]
        );

        // 10. GlowBabe Hydrating Lip Oil
        Product::updateOrCreate(
            ['name' => 'GlowBabe Hydrating Lip Oil'],
            [
                'description' => 'A nourishing lip oil infused with jojoba and vitamin E. Provides instant hydration and a glossy finish without feeling sticky.',
                'price' => 159.00,
                'category_id' => $lips->id,
                'image' => 'images/hydrating-lip-oil.png',
                'stock' => 100
            ]
        );
    }
}
