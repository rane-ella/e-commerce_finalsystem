<x-store-layout>
    <style>
        :root {
            --cream-bg: #fcfbf8; 
            --terracotta-accent: #e76f51;
            --text-color: #333333;
            --light-gray: #e0e0e0;
        }
        
        .receipt-page {
            padding: 60px 20px;
            background-color: var(--cream-bg);
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .receipt-container {
            background: white;
            width: 100%;
            max-width: 700px;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px dashed var(--light-gray);
            padding-bottom: 30px;
        }

        .check-icon {
            width: 60px;
            height: 60px;
            background-color: #4caf50;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        .receipt-title {
            font-family: 'Playfair Display', serif;
            font-size: 2em;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .receipt-subtitle {
            color: #666;
            font-size: 1em;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-group h3 {
            font-size: 0.9em;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .info-group p {
            font-size: 1.1em;
            color: var(--text-color);
            line-height: 1.5;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            text-align: left;
            padding: 15px 0;
            border-bottom: 2px solid var(--light-gray);
            color: #888;
            font-weight: normal;
            font-size: 0.9em;
            text-transform: uppercase;
        }

        .invoice-table td {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .invoice-table td:last-child, 
        .invoice-table th:last-child {
            text-align: right;
        }

        .item-name {
            font-weight: bold;
            display: block;
        }
        .item-meta {
            font-size: 0.9em;
            color: #666;
        }

        .totals {
            text-align: right;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            padding: 5px 0;
        }
        .total-row span:first-child {
            width: 150px;
            color: #666;
        }
        .total-row span:last-child {
            width: 120px;
            font-weight: bold;
        }
        .grand-total {
            font-size: 1.3em;
            color: var(--terracotta-accent);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid var(--light-gray);
        }

        .action-area {
            margin-top: 50px;
            text-align: center;
        }

        .finish-btn {
            background-color: var(--terracotta-accent);
            color: white;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            transition: background-color 0.3s;
            display: inline-block;
        }
        .finish-btn:hover {
            background-color: #d65f41;
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="receipt-page">
        <div class="receipt-container">
            <div class="receipt-header">
                <div class="check-icon">✓</div>
                <h1 class="receipt-title">Order Confirmed!</h1>
                <p class="receipt-subtitle">Thank you for your purchase. Your order has been received.</p>
                <p style="margin-top: 10px; font-weight: bold; color: var(--terracotta-accent);">Order #{{ $order->id }}</p>
            </div>

            <div class="info-grid">
                <div class="info-group">
                    <h3>Customer Details</h3>
                    <p><strong>{{ $order->user->name }}</strong></p>
                    <p>{{ $order->user->phone }}</p>
                    <p>{{ $order->user->address }}</p>
                </div>
                <div class="info-group">
                    <h3>Order Info</h3>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                    <p style="color: var(--terracotta-accent); font-weight: bold; margin-top: 5px;">
                        Estimated Delivery: {{ $order->created_at->addDays(7)->format('M d, Y') }}
                    </p>
                </div>
            </div>

            <h3>Invoice Details</h3>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 60%;">Item</th>
                        <th style="width: 20%;">Qty</th>
                        <th style="width: 20%;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <span class="item-name">{{ $item->product->name }}</span>
                            <span class="item-meta">{{ $item->product->category->name }}</span>
                        </td>
                        <td>x{{ $item->quantity }}</td>
                        <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->total_amount - $order->shipping_fee + $order->discount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>₱{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>-₱{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span>Total:</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="action-area">
                <a href="{{ route('orders.index') }}" class="finish-btn">Finish View</a>
            </div>
        </div>
    </div>
</x-store-layout>
