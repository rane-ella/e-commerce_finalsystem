<!DOCTYPE html>
<html>
<head>
    <title>Order Cancelled and Refunded</title>
</head>
<body>
    <h1>Order Cancelled</h1>
    <p>Hello {{ $order->user->name }},</p>
    <p>Your order #{{ $order->id }} has been successfully cancelled.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    
    @if($order->payment_method !== 'cod')
        <p>We have initiated a refund of <strong>₱{{ number_format($order->total_amount, 2) }}</strong> to your original payment method.</p>
        <p>Please allow 5-10 business days for the refund to reflect in your account.</p>
    @else
        <p>Since this was a Cash on Delivery order, no refund is necessary.</p>
    @endif

    <p>Thank you for shopping with us.</p>
</body>
</html>
