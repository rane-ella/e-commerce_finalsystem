<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::with('category', 'images')->get();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // API endpoint to get necessary data for creating a product (e.g. categories)
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = \App\Models\Product::create($request->except('image'));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create(['image_path' => $path]);
        }

        return response()->json(['message' => 'Product created successfully.', 'product' => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = \App\Models\Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return response()->json(['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            // Delete old images if necessary, or just add new one. 
            // For now, let's just add the new one as the primary one.
            // Ideally we might want to delete old ones to save space.
            foreach($product->images as $img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }

            $path = $request->file('image')->store('products', 'public');
            $product->images()->create(['image_path' => $path]);
        }

        return response()->json(['message' => 'Product updated successfully.', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function markOutOfStock(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->update(['stock' => 0]);
        return response()->json(['message' => 'Product marked as out of stock.', 'product' => $product]);
    }
}
