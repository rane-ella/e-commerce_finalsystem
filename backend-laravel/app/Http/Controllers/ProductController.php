<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category')) {
            $slug = $request->get('category');
            $query->whereHas('category', function($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $products = $query->paginate(20);
        
        return response()->json($products);
    }

    // Show product details
    public function show(Product $product)
    {
        $product->load('category');
        return response()->json($product);
    }

    // Store product (Admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ], 201);
    }

    // Update product (Admin)
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    // Delete product (Admin)
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully!'
        ]);
    }
    
    // Mark out of stock (Admin)
    public function markOutOfStock(Product $product)
    {
        $product->update(['stock' => 0]);
        return response()->json([
            'message' => 'Product marked as out of stock',
            'product' => $product
        ]);
    }
}
