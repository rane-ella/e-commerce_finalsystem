<x-store-layout>
    <style>
        /* Global Styles & Variables (Matching your brand colors) */
        :root {
            --cream-bg: #fcfbf8; 
            --terracotta-accent: #e76f51; /* Primary CTA Color */
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --header-height: 50px; /* Reduced header height for cart preview */
            --border-radius: 6px;
        }

        /* --- Cart Layout --- */
        .cart-layout {
            display: flex;
            gap: 40px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
        }

        /* Left Column: Product List */
        .cart-items {
            flex: 2; /* Takes up 2/3 of the space */
        }

        .cart-heading {
            font-size: 1.8em;
            margin-bottom: 30px;
            font-weight: 700;
        }
        
        /* Item Card Style (Using layout from your image) */
        .cart-item-card {
            display: flex;
            border-top: 1px solid var(--light-gray);
            padding: 20px 0;
            gap: 20px;
        }
        .cart-item-card:last-child {
            border-bottom: 1px solid var(--light-gray);
        }

        .item-image {
            width: 100px;
            height: 100px;
            background-color: #f7e7e3;
            border-radius: var(--border-radius);
            flex-shrink: 0;
            overflow: hidden;
        }
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex-grow: 1;
        }
        
        .item-details h3 {
            font-size: 1.1em;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .item-details p {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 8px;
        }

        .action-link {
            font-size: 0.9em;
            color: #555;
            text-decoration: none;
            cursor: pointer;
        }
        .action-link:hover {
            color: var(--terracotta-accent);
        }

        /* Quantity and Price Control */
        .item-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        .quantity-control button {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 1em;
            color: #555;
        }
        .quantity-control span {
            padding: 8px 10px;
            border-left: 1px solid var(--light-gray);
            border-right: 1px solid var(--light-gray);
            font-weight: 700;
            font-size: 0.9em;
        }
        
        .item-price {
            font-weight: 700;
            color: var(--terracotta-accent);
            font-size: 1.1em;
        }

        /* --- Right Column: Sticky Order Summary --- */
        .cart-summary-wrapper {
            flex: 1; /* Takes up 1/3 of the space */
            min-width: 300px;
        }

        .cart-summary {
            position: sticky; /* Makes the sidebar stick */
            top: 100px; /* Sticks below the header */
            padding: 20px;
            background-color: white;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .summary-title {
            font-size: 1.4em;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.95em;
        }
        .summary-total {
            border-top: 2px solid var(--light-gray);
            margin-top: 15px;
            padding-top: 15px;
            font-weight: 700;
            font-size: 1.2em;
        }
        .summary-total .price {
            color: var(--terracotta-accent);
            font-size: 1.4em;
        }

        /* Promo Code */
        .promo-code {
            margin: 20px 0;
            padding: 15px 0;
            border-top: 1px solid var(--light-gray);
        }
        .promo-code label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .promo-code input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
        }

        /* Checkout CTA */
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--terracotta-accent);
            color: white;
            text-align: center;
            font-size: 1.1em;
            font-weight: 700;
            border-radius: var(--border-radius);
            transition: background-color 0.3s;
            margin-top: 20px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .checkout-btn:hover {
            background-color: #d15a3a;
        }

        /* Responsive Adjustments */
        @media (max-width: 900px) {
            .cart-layout {
                flex-direction: column;
                gap: 20px;
            }
            .cart-summary-wrapper {
                min-width: 100%;
            }
            .cart-summary {
                position: static; /* Disable sticky on smaller screens */
            }
        }
    </style>

    @php
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        $shipping = $cartItems->isEmpty() ? 0 : 100.00;
        $total = $subtotal + $shipping;
    @endphp

    <div class="cart-layout">
        
        <div class="cart-items">
            <h2 class="cart-heading">Shopping Cart ({{ $cartItems->count() }} Items)</h2>

            @if($cartItems->count() > 0)
                @foreach($cartItems as $item)
                    <div class="cart-item-card">
                        <div class="item-image">
                            @if($item->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                            @else
                                <div style="width:100%; height:100%; background-color:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#888; font-size:0.8em;">No Image</div>
                            @endif
                        </div>
                        <div class="item-details">
                            <h3>{{ $item->product->name }}</h3>
                            <p>{{ $item->product->category->name }}</p>
                            
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-link" style="background:none; border:none; padding:0;">Remove</button>
                            </form>

                            <div class="item-controls">
                                <div class="quantity-control">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                        <button type="submit" {{ $item->quantity <= 1 ? 'disabled style=opacity:0.5' : '' }}>-</button>
                                    </form>
                                    
                                    <span>{{ $item->quantity }}</span>
                                    
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit">+</button>
                                    </form>
                                </div>
                                <div class="item-price">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="text-terracotta-accent hover:underline">Start Shopping</a>
            @endif

            <div style="margin-top: 30px; text-align: right;">
                <a href="{{ route('products.index') }}" style="font-weight: 700;">← Continue Shopping</a>
            </div>

        </div>

        <div class="cart-summary-wrapper">
            <div class="cart-summary">
                <div class="summary-title">Order Summary</div>

                <div class="summary-line">
                    <span>Subtotal ({{ $cartItems->count() }} Items)</span>
                    <span>₱{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-line">
                    <span>Shipping Estimate</span>
                    <span>₱{{ number_format($shipping, 2) }}</span>
                </div>
                
                <div class="promo-code">
                    <label for="promo">Apply Discount Code</label>
                    <input type="text" id="promo" placeholder="Enter code here">
                    <button class="cta-button" style="padding: 8px 15px; background-color: #ccc; color: #333; border: none; cursor: pointer; border-radius: 4px;">Apply</button>
                </div>

                <div class="summary-total summary-line">
                    <span>Order Total</span>
                    <span class="price">₱{{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="checkout-btn">
                    Proceed to Checkout
                </a>
                
                <p style="font-size: 0.8em; text-align: center; margin-top: 15px; color: #666;">
                    🔒 Secure Checkout Guaranteed
                </p>
            </div>
        </div>
    </div>
</x-store-layout>
