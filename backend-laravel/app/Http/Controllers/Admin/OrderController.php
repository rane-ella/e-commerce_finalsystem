<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user', 'items.product')->latest()->get();
        return response()->json($orders);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,out_for_delivery,completed,canceled,processing',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully.', 'order' => $order]);
    }
}
