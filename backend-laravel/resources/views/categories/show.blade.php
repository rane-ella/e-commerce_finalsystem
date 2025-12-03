<x-store-layout>
    <style>
        :root {
            --cream-bg: #fcfbf8;
            --terracotta-accent: #e76f51;
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Roboto', sans-serif;
        }

        .shop-container {
            font-family: var(--font-sans);
            background-color: var(--cream-bg);
            color: var(--text-color);
            padding: 40px 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            font-family: var(--font-serif);
            font-size: 2.5em;
            text-align: center;
            color: var(--terracotta-accent);
            margin-bottom: 40px;
        }

        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start; /* Align items to the start */
        }

        .product-card {
            flex: 0 1 calc(25% - 15px); /* 4 items per row with gap */
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none; /* Remove underline from anchor */
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }
        
        .product-card-img {
            width: 100%;
            aspect-ratio: 1 / 1; /* Square aspect ratio */
            background-color: #fcece9; /* Light pink placeholder background */
            margin-bottom: 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-card-img img {
            width: 60%;
            height: auto;
            object-fit: contain;
            mix-blend-mode: multiply; /* Helps blend image with pink bg if transparent */
        }
        
        .product-card h4 {
            font-family: var(--font-serif);
            font-size: 1.1em;
            margin: 10px 0 5px;
            font-weight: normal;
            color: var(--text-color);
        }
        
        .product-card .price {
            font-weight: bold;
            color: var(--terracotta-accent);
            margin-top: auto; /* Push to bottom */
            font-size: 1.1em;
        }
        
        .rating {
            color: #f5c518; /* Gold color */
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .product-card {
                flex: 0 1 calc(50% - 10px); /* 2 items per row */
            }
        }

        @media (max-width: 600px) {
            .product-card {
                flex: 0 1 100%; /* 1 item per row */
            }
        }
    </style>

    <div class="shop-container">
        <div class="container">
            <h1 class="page-title">{{ $category->name }}</h1>
            
            @if($products->isEmpty())
                <p class="text-center text-gray-500">No products found in this category.</p>
            @else
                <div class="product-grid">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="product-card">
                            <div class="product-card-img">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </div>
                            <div class="rating">⭐⭐⭐⭐⭐</div>
                            <h4>{{ $product->name }}</h4>
                            <p class="price">₱{{ number_format($product->price, 2) }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-store-layout>
