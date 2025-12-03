<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show user cart
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart;
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect([]);
        
        return response()->json($cartItems);
    }

    // Add product to cart
    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        $cart = $user->cart ?? $user->cart()->create();

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        $quantity = $request->input('quantity', 1);

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart!',
            'cart_count' => $cart->items()->sum('quantity')
        ]);
    }

    // Remove item from cart
    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart.']);
    }

    // Update item quantity
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated.']);
    }
}
