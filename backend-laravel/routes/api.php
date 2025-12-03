<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController; // We will create this
use App\Http\Controllers\OtpController; // We will create this

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/otp/send', [OtpController::class, 'send']);
Route::post('/login/otp/verify', [OtpController::class, 'verify']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/debug-data', function () {
    return response()->json([
        'products' => \App\Models\Product::with(['category', 'images'])->get(),
        'categories' => \App\Models\Category::all(),
        'images' => \App\Models\ProductImage::all(),
    ]);
});

Route::get('/payments/paymongo/success', [OrderController::class, 'paymentSuccess']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    // Route::delete('/profile', [ProfileController::class, 'destroy']); // Be careful with account deletion

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add/{product}', [CartController::class, 'add']);
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove']);
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/checkout', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']); // Add show method to OrderController if missing
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::patch('/orders/{order}/complete', [OrderController::class, 'complete']);

    // Payment Routes (Placeholders for now)
    Route::post('/payments/paymongo/checkout', [OrderController::class, 'createPayMongoCheckout']);
    Route::post('/payments/paypal/create', [OrderController::class, 'createPayPalOrder']);
    Route::post('/payments/paypal/capture', [OrderController::class, 'capturePayPalOrder']);
});

// Admin Routes
Route::prefix('admin')->middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    // We will migrate admin routes later or keep them as is if Admin is still server-side rendered?
    // User asked for "frontend let's use remix", implying the WHOLE frontend.
    // So we should expose admin APIs too.
    
    // Dashboard stats (to be implemented)
    // Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Product Management
    Route::get('products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create']);
    Route::get('products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit']);
    Route::apiResource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::patch('/products/{product}/out-of-stock', [\App\Http\Controllers\Admin\ProductController::class, 'markOutOfStock']);
    
    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index']);
    Route::patch('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'update']);
});
