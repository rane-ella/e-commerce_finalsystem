<x-store-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Product List</h3>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Product
                        </a>
                    </div>

                    <style>
                        /* --- Refined Product List Table Styles --- */
                        .product-table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        
                        .product-table th, .product-table td {
                            padding: 15px 10px;
                            text-align: left;
                            border-bottom: 1px solid #f0f0f0;
                            vertical-align: middle; /* Ensures all content is centered vertically */
                        }

                        .product-table th {
                            font-size: 0.9em;
                            color: #666;
                            text-transform: uppercase;
                            font-weight: 500;
                            padding-top: 5px; /* Added slight padding at the top of the header for better spacing */
                        }
                        
                        .product-table td {
                             height: 80px; /* Set a consistent height for cleaner rows */
                        }



                        /* Thumbnail Column */
                        .product-thumb {
                            width: 50px;
                            height: 50px;
                            background-color: #f7e7e3;
                            border-radius: 4px;
                            display: block;
                            object-fit: cover;
                        }
                        
                        /* Price Column */
                        .price-col {
                            font-weight: 500;
                        }

                        /* Stock Status Indicators */
                        .stock-indicator {
                            font-weight: 700;
                            display: flex;
                            align-items: center;
                            font-size: 1.05em; /* Slightly larger text for stock numbers */
                        }
                        .stock-status-dot {
                            width: 8px;
                            height: 8px;
                            border-radius: 50%;
                            margin-right: 8px;
                            flex-shrink: 0;
                        }

                        /* Action Links */
                        .action-links a, .action-links button {
                            margin-right: 10px;
                            color: #c77d7d; /* Terracotta accent */
                            text-decoration: none;
                            white-space: nowrap; /* Prevents action links from wrapping */
                            background: none;
                            border: none;
                            cursor: pointer;
                            font-size: 1rem;
                            padding: 0;
                        }
                        .action-links a:hover, .action-links button:hover {
                            text-decoration: underline;
                        }
                        
                        /* Stock specific colors */
                        .stock-green .stock-status-dot { background-color: #4caf50; }
                        .stock-yellow .stock-status-dot { background-color: #ffc107; }
                        .stock-red { 
                            color: #f44336; /* Make the text RED */
                        }
                        .stock-red .stock-status-dot { 
                            background-color: #f44336;
                        }
                        .stock-red span {
                            font-weight: 700; /* Ensure 'Out of Stock' text is bold */
                        }
                    </style>

                    <div class="relative overflow-x-auto">
                        <table class="product-table">
                            <thead>
                                <tr>

                                    <th style="width: 8%;">Image</th>
                                    <th style="width: 30%;">Name</th>
                                    <th style="width: 10%;">ID</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 15%;">Stock</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>

                                    <td>
                                        @if($product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="product-thumb" alt="{{ $product->name }}">
                                        @else
                                            <div class="product-thumb"></div>
                                        @endif
                                    </td>
                                    <td class="product-name-col">{{ $product->name }}</td>
                                    <td>{{ $product->id }}</td>
                                    <td class="price-col">₱{{ number_format($product->price, 2) }}</td>
                                    
                                    @php
                                        $stockClass = 'stock-green';
                                        $stockText = $product->stock;
                                        if ($product->stock <= 0) {
                                            $stockClass = 'stock-red';
                                            $stockText = 'Out of Stock';
                                        } elseif ($product->stock <= 20) {
                                            $stockClass = 'stock-yellow';
                                            $stockText = $product->stock . ' (Low)';
                                        }
                                    @endphp

                                    <td class="stock-indicator {{ $stockClass }}">
                                        <span class="stock-status-dot"></span> {{ $stockText }}
                                    </td>
                                    <td class="action-links">
                                        <a href="{{ route('admin.products.edit', $product->id) }}">Edit</a>
                                        <form action="{{ route('admin.products.outOfStock', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to mark this product as out of stock?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit">Out of Stock</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No products found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-store-layout>
