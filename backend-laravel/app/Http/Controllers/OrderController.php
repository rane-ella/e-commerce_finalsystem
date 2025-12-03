<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PayMongoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $payMongoService;
    protected $payPalService;

    public function __construct(PayMongoService $payMongoService, PayPalService $payPalService)
    {
        $this->payMongoService = $payMongoService;
        $this->payPalService = $payPalService;
    }

    // Show all orders for logged-in user
    public function index()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    // Show single order
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $order->load('items.product');
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,card,paypal,gcash',
            'notes' => 'nullable|string|max:500',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $user = $request->user();
        
        // Update user contact info
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        $cart = $user->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        $cartItems = $cart->items()->with('product')->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        $shipping = 50.00;
        $discount = 0.00;
        $total = $subtotal + $shipping - $discount;

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending', // For online payments, this might be 'pending_payment'
            'payment_method' => $request->payment_method,
            'shipping_fee' => $shipping,
            'discount' => $discount,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            // Deduct stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order
        ], 201);
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow cancel if status is pending
        if ($order->status === 'pending') {
            
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->status = 'canceled';
            $order->save();

            // Send Email (Optional)
            // ...

            return response()->json(['message' => 'Order canceled successfully.']);
        }

        return response()->json(['message' => 'Order cannot be canceled at this stage.'], 400);
    }

    public function complete(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Allow completion if status is shipped or in_transit (or processing if digital/service)
        // Adjust logic as per business rule. User said "complete option... once user choose complete... disable cancel".
        // Cancel is already disabled if not pending.
        
        if (!in_array($order->status, ['canceled', 'completed'])) {
            $order->update(['status' => 'completed']);
            return response()->json(['message' => 'Order marked as completed.']);
        }

        return response()->json(['message' => 'Order cannot be completed.'], 400);
    }

    // Payment Integration
    public function createPayMongoCheckout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $user = $request->user();
        
        // Update user contact info
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        $cart = $user->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        // Calculate total
        $cartItems = $cart->items()->with('product')->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        $shipping = 50.00;
        $discount = 0.00;
        $total = $subtotal + $shipping - $discount;

        // Create Order with pending_payment status
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending_payment',
            'payment_method' => 'card', // PayMongo
            'shipping_fee' => $shipping,
            'discount' => $discount,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            // Deduct stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        $cart->items()->delete();

        try {
            $successUrl = url('/api/payments/paymongo/success?order_id=' . $order->id);
            $cancelUrl = url('/api/payments/paymongo/cancel?order_id=' . $order->id);

            $session = $this->payMongoService->createCheckoutSession(
                $total, 
                'PHP', 
                $successUrl, 
                $cancelUrl
            );
            
            return response()->json([
                'checkout_url' => $session['attributes']['checkout_url']
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json(['message' => 'Payment initiation failed: ' . $e->getMessage()], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
             return response()->json(['message' => 'Order ID missing'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status to processing (paid)
        if ($order->status === 'pending_payment') {
            $order->update(['status' => 'processing']);
        }

        // Redirect to frontend My Purchases page
        return redirect('http://localhost:5173/orders'); 
    }

    public function createPayPalOrder(Request $request)
    {
        $request->validate(['amount' => 'required|numeric']);

        try {
            $returnUrl = url('/api/payments/paypal/success'); // Or frontend URL
            $cancelUrl = url('/api/payments/paypal/cancel');

            $order = $this->payPalService->createOrder(
                $request->amount, 
                'PHP', 
                $returnUrl, 
                $cancelUrl
            );

            // Find approval link
            $approvalLink = collect($order['links'])->firstWhere('rel', 'approve')['href'];

            return response()->json([
                'checkout_url' => $approvalLink
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            return response()->json(['message' => 'PayPal initiation failed'], 500);
        }
    }

    public function capturePayPalOrder(Request $request)
    {
        $request->validate(['order_id' => 'required|string']);

        try {
            $capture = $this->payPalService->captureOrder($request->order_id);
            return response()->json($capture);
        } catch (\Exception $e) {
            Log::error('PayPal Capture Error: ' . $e->getMessage());
            return response()->json(['message' => 'PayPal capture failed'], 500);
        }
    }
}
