<x-store-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap');

        /* Global Styles & Variables (Matching your header colors) */
        :root {
            --cream-bg: #fcfbf8; /* Soft background */
            --terracotta-accent: #e76f51;
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --spacing-large: 60px;
            --spacing-medium: 40px;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Roboto', sans-serif;
            --font-clean: 'Montserrat', sans-serif;
        }

        /* Scoped to dashboard content */
        .dashboard-container {
            font-family: var(--font-sans);
            background-color: var(--cream-bg);
            color: var(--text-color);
            margin: 0;
            /* padding-top: 90px; Removed as header is handled by layout */
        }
        
        .dashboard-container a {
            text-decoration: none;
            color: var(--terracotta-accent);
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            font-family: var(--font-serif);
            font-size: 2.5em;
            text-align: center;
            color: var(--terracotta-accent);
            margin-bottom: var(--spacing-medium);
        }

        /* --- 1. Hero Section Refinement --- */
        .hero-section {
            text-align: center;
            padding: 60px 20px 40px;
            background-color: var(--cream-bg);
        }

        .hero-image-placeholder {
            /* Placeholder for your large logo image */
            max-width: 700px;
            height: 450px;
            background-color: #f7e7e3; 
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-serif);
            font-size: 3em;
            color: var(--terracotta-accent);
            border-radius: 8px;
        }
        
        .hero-tagline {
            font-family: var(--font-clean);
            font-size: 1.5em;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.5;
            color: #666;
            font-weight: 400;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 35px;
            background-color: var(--terracotta-accent);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #d15a3a;
        }

        /* --- 2A. Featured Categories --- */
        .category-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: var(--spacing-large);
        }

        .category-card {
            flex: 1;
            text-align: center;
            overflow: hidden;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .category-card-img {
            width: 100%;
            height: 250px;
            background-color: var(--light-gray);
            object-fit: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            color: #777;
        }
        
        .category-card h3 {
            margin: 15px 0;
            font-size: 1.2em;
            color: var(--text-color);
        }

        /* --- 2B. Brand Value Props --- */
        .value-props {
            display: flex;
            justify-content: space-around;
            padding: var(--spacing-large) 0;
            margin-top: var(--spacing-medium);
            border-top: 1px solid var(--light-gray);
            border-bottom: 1px solid var(--light-gray);
        }

        .value-item {
            text-align: center;
            max-width: 250px;
        }

        .value-item .icon {
            font-size: 2.5em;
            color: var(--terracotta-accent);
            margin-bottom: 10px;
        }

        .value-item p {
            font-weight: 500;
        }

        /* --- 3C. Bestsellers Carousel (Simplified Grid) --- */
        .product-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: var(--spacing-large);
        }

        .product-card {
            flex: 1 1 23%; /* Allows 4 items per row */
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .product-card-img {
            width: 100%;
            height: 200px;
            background-color: #fcece9;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            color: #777;
        }
        
        .product-card h4 {
            font-size: 1em;
            margin: 5px 0;
            font-weight: normal;
        }
        
        .product-card .price {
            font-weight: bold;
            color: var(--terracotta-accent);
            margin-top: 5px;
        }
        
        .rating {
            color: gold;
            margin-bottom: 5px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 900px) {
            .category-grid, .product-grid {
                flex-wrap: wrap;
                justify-content: center;
            }
            .category-card, .product-card {
                flex: 1 1 45%; 
                margin-bottom: 20px;
            }
        }

        @media (max-width: 600px) {
            .value-props {
                flex-direction: column;
                gap: 30px;
            }
            .category-card, .product-card {
                flex: 1 1 100%;
            }
            .section-title {
                font-size: 2em;
            }
        }
    </style>

    <div class="dashboard-container">
        <section class="hero-section">
            <div class="container">
                @if(Auth::user() && Auth::user()->role === 'admin')
                    <h2 class="text-4xl md:text-5xl font-serif text-center text-[#e76f51] mb-8 font-bold">Welcome to Admin Home</h2>
                @endif
                <img src="{{ asset('images/hero-logo.jpg') }}" alt="GlowBabe Logo" class="mx-auto mb-8" style="max-width: 400px; width: 100%; height: auto;">
                
                <p class="hero-tagline">
                    Discover your natural radiance with our premium collection of cosmetics and skincare. Designed to enhance your beauty, naturally.
                </p>
            </div>
        </section>

        <section class="category-section">
            <div class="container">
                <h2 class="section-title">Shop By Category</h2>
                <div class="category-grid">
                    <a href="{{ route('categories.show', 'lips') }}" class="category-card">
                        <div class="category-card-img">
                            <img src="{{ asset('images/lips-category.png') }}" alt="Lips" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3>Lips</h3>
                    </a>
                    <a href="{{ route('categories.show', 'face') }}" class="category-card">
                        <div class="category-card-img">
                            <img src="https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=800&auto=format&fit=crop" alt="Face" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3>Face</h3>
                    </a>
                    <a href="{{ route('categories.show', 'skincare') }}" class="category-card">
                        <div class="category-card-img">
                            <img src="{{ asset('images/skincare-category.png') }}" alt="Skincare" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3>Skincare</h3>
                    </a>
                    <a href="{{ route('categories.show', 'eyes') }}" class="category-card">
                        <div class="category-card-img">
                            <img src="https://i.pinimg.com/1200x/c2/a7/21/c2a721becb791de43aabf5ad8415b074.jpg" alt="Eyes" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h3>Eyes</h3>
                    </a>
                </div>
            </div>
        </section>



        <section class="bestsellers-section">
            <div class="container">
                <h2 class="section-title">GlowBabe Best Sellers</h2>
                <div class="product-grid">
                    @foreach($products as $product)
                    <a href="{{ route('products.show', $product->id) }}" class="product-card">
                        <div class="product-card-img">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/soft-matte-lip-cream.png') }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                        <h4>{{ $product->name }}</h4>
                        <p class="price">₱{{ number_format($product->price, 2) }}</p>
                    </a>
                    @endforeach
                </div>
                <div style="text-align: center; margin-top: 40px;">
                    <a href="{{ route('products.index') }}" class="cta-button" style="background-color: #555;">View All Products</a>
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.products.create') }}" class="cta-button" style="background-color: #555; margin-left: 15px;">Add Product</a>
                    @endif
                </div>
            </div>
        </section>

        <footer style="padding: 50px 0; margin-top: 80px; background-color: #f7e7e3; text-align: center; font-size: 0.9em;">
            <p>&copy; 2025 GlowBabe. All Rights Reserved.</p>
        </footer>
    </div>
</x-store-layout>
