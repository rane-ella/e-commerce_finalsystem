<x-store-layout>
    <style>
        .shop-container {
            --cream-bg: #fcfbf8;
            --terracotta-accent: #e76f51;
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Roboto', sans-serif;

            font-family: var(--font-sans);
            background-color: var(--cream-bg);
            color: var(--text-color);
            padding: 40px 20px;
            min-height: 100vh;
        }

        .shop-container .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .shop-container .page-title {
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
            justify-content: flex-start;
        }

        .product-card {
            flex: 0 1 calc(25% - 15px);
            text-align: center;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
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
            aspect-ratio: 1 / 1;
            background-color: #fcece9;
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
            mix-blend-mode: multiply;
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
            margin-top: auto;
            font-size: 1.1em;
        }
        
        .rating {
            color: #f5c518;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        /* Responsive adjustments */
        @media (max-width: 900px) {
            .product-card {
                flex: 0 1 calc(50% - 10px);
            }
        }

        @media (max-width: 600px) {
            .product-card {
                flex: 0 1 100%;
            }
        }
    </style>

    <div class="shop-container">
        <div class="container">
            <div class="flex justify-between items-center mb-10">
                <h1 class="page-title mb-0">Shop All Products</h1>
                @if(Auth::user() && Auth::user()->role === 'admin')
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Add Product
                    </a>
                @endif
            </div>
            
            @if($products->isEmpty())
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p style="font-size: 1.2em; margin-bottom: 20px;">No results found for "{{ request('search') }}"</p>
                    <a href="{{ route('products.index') }}" style="color: var(--terracotta-accent); text-decoration: underline;">View all products</a>
                </div>
            @else
                <div class="product-grid">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-card">
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
                
                <!-- Pagination (if needed) -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-store-layout>
