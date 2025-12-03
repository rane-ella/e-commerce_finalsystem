<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// FRONTEND ROUTES

// Home page redirects to login
Route::get('/', function () {
    return view('auth.login');
});

// Product listing
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Product details page
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Show products by category
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/dashboard', function () {
    $products = App\Models\Product::whereIn('id', [1, 2, 3, 4])->get();
    return view('dashboard', compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// CART & ORDERS (requires login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// ADMIN ROUTES (requires admin login)
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {

    // Admin dashboard
    // Admin dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Product management
    Route::resource('products', AdminProductController::class, [
        'as' => 'admin' // route names like admin.products.index
    ]);
    Route::patch('/products/{product}/out-of-stock', [AdminProductController::class, 'markOutOfStock'])->name('admin.products.outOfStock');

    // Category management (optional)
    Route::resource('categories', AdminCategoryController::class, [
        'as' => 'admin'
    ]);

    // Order management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
});

require __DIR__.'/auth.php';
