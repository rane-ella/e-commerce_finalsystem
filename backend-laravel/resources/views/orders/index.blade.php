<x-store-layout>
    <style>
        /* Global Styles & Variables */
        :root {
            --cream-bg: #fcfbf8; 
            --terracotta-accent: #e76f51; /* Primary Brand Color */
            --text-color: #333333;
            --light-gray: #e0e0e0;
            --pending-color: #ff9800; /* Orange for Pending/Processing */
            --border-radius: 6px;
        }

        .purchases-container {
            padding: 40px 20px;
            max-width: 900px;
            margin: 0 auto;
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
        }
        
        .page-title {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .page-subtitle {
             font-size: 1em;
             color: #666;
             margin-bottom: 40px;
        }

        /* --- Order Block Container --- */
        .order-block {
            background-color: white;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        /* --- 1. Order Summary Header (New) --- */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            background-color: #fafafa;
            border-bottom: 1px solid var(--light-gray);
            font-size: 0.9em;
        }

        .order-header-details span {
            margin-right: 20px;
        }
        
        /* Status Badge Styling */
        .status-badge {
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            text-transform: uppercase;
            color: white;
            background-color: var(--pending-color); /* Default Pending/Orange */
        }
        
        .status-completed { background-color: #4caf50; }
        .status-cancelled { background-color: #f44336; }

        /* --- 2. Action Bar (View/Track/Cancel) --- */
        .order-actions {
            padding: 15px 25px;
            display: flex;
            justify-content: flex-end; /* Push buttons to the right */
            gap: 15px;
            border-bottom: 1px solid var(--light-gray);
        }

        .action-btn {
            padding: 8px 15px;
            border-radius: var(--border-radius);
            font-size: 0.9em;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel {
            background-color: transparent;
            border: 1px solid #ccc;
            color: #666;
        }
        .btn-cancel:hover {
            color: red;
            border-color: red;
        }
        
        .btn-track {
            background-color: var(--terracotta-accent);
            border: none;
            color: white;
        }

        /* --- 3. Item List (Replacing the Huge Image) --- */
        .order-items-list {
            padding: 20px 25px;
        }
        
        .item-row {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed var(--light-gray);
        }
        .item-row:last-child {
            border-bottom: none;
        }

        .item-thumb {
            width: 60px;
            height: 60px;
            background-color: #f7e7e3; /* Light background for the thumbnail */
            border-radius: var(--border-radius);
            margin-right: 20px;
            flex-shrink: 0;
            overflow: hidden;
        }
        .item-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex-grow: 1;
        }
        .item-details strong {
            display: block;
            font-size: 1.1em;
            margin-bottom: 3px;
        }
        .item-details span {
            font-size: 0.9em;
            color: #666;
        }

        .item-price-col {
            text-align: right;
            font-weight: 700;
            color: var(--terracotta-accent);
        }

        /* --- 4. Final Price Summary --- */
        .order-footer-summary {
            padding: 20px 25px;
            background-color: #fafafa;
            border-top: 2px solid var(--light-gray);
            text-align: right;
        }

        .summary-line {
            display: flex;
            justify-content: flex-end;
            padding: 3px 0;
        }
        .summary-line span:first-child {
            width: 150px;
            color: #555;
            font-size: 0.9em;
        }
        .summary-line span:last-child {
            width: 100px;
            font-weight: 700;
            color: var(--text-color);
        }

        .final-total {
            margin-top: 10px;
            font-size: 1.2em;
        }
        .final-total span:last-child {
            color: var(--terracotta-accent);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-header-details {
                margin-bottom: 10px;
            }
            .order-actions {
                justify-content: space-around;
            }
            .item-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .item-thumb {
                margin-right: 0;
            }
            .item-price-col {
                width: 100%;
                text-align: left;
                padding-top: 5px;
            }
        }
    </style>

    <div class="purchases-container">
        <h2 class="page-title">My Purchases</h2>
        <p class="page-subtitle">Track your orders and view their status.</p>

        @if($orders->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('products.index') }}" class="text-terracotta-accent hover:underline font-bold">Start Shopping</a>
            </div>
        @else
            @foreach($orders as $order)
                <div class="order-block">
                    
                    <div class="order-header">
                        <div class="order-header-details">
                            <span class="font-bold">Order #{{ $order->id }}</span>
                            <span>Placed: {{ $order->created_at->format('M d, Y') }}</span>
                            <div class="text-sm text-gray-500 mt-1">
                                Item will be delivered in {{ $order->created_at->addDays(7)->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="order-status">
                            <span class="status-badge {{ $order->status == 'completed' ? 'status-completed' : ($order->status == 'cancelled' ? 'status-cancelled' : '') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-actions">
                        @if($order->status == 'pending')
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn btn-cancel">Cancel Order</button>
                            </form>
                        @endif
                        <!-- Placeholder for View Details or Track Order if implemented later -->
                        <!-- <a href="#" class="action-btn" style="background-color: #999; color: white;">View Full Details</a> -->
                    </div>

                    <div class="order-items-list">
                        @foreach($order->items as $item)
                            <div class="item-row">
                                <div class="item-thumb">
                                    @if($item->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div style="width:100%; height:100%; background-color:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#888; font-size:0.7em;">No Img</div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <strong>{{ $item->product->name }}</strong>
                                    <span>Qty: {{ $item->quantity }} | {{ $item->product->category->name }}</span>
                                </div>
                                <div class="item-price-col">
                                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-footer-summary">
                        <div class="summary-line">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($order->total_amount - $order->shipping_fee + $order->discount, 2) }}</span>
                        </div>
                        <div class="summary-line">
                            <span>Shipping:</span>
                            <span>₱{{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        <div class="summary-line final-total">
                            <span>Order Total:</span>
                            <span>₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-store-layout>
