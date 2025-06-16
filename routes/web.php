<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// Auth Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Đây là các route chính của website Hang The Thao
*/

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');
    
    // Review routes
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product routes
Route::get('/product/{id}', function($id) {
    $product = App\Models\Product::active()->with('reviews.user')->findOrFail($id);
    $relatedProducts = App\Models\Product::active()
        ->where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->limit(6)
        ->get();
    return view('products.show', compact('product', 'relatedProducts'));
})->name('product.show');

Route::get('/products', function() {
    $products = App\Models\Product::active()->with(['category', 'variants'])->paginate(12);
    return view('products.index', compact('products'));
})->name('products.index');

Route::get('/products/{id}', function($id) {
    $product = App\Models\Product::active()->with('reviews.user')->findOrFail($id);
    $relatedProducts = App\Models\Product::active()
        ->where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->limit(6)
        ->get();
    return view('products.show', compact('product', 'relatedProducts'));
})->name('products.show');

// Category and search routes
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.index');
Route::get('/search', [CategoryController::class, 'search'])->name('search');

// Static pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about'); 
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('pages.contact.submit');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/update-variant', [CartController::class, 'updateVariant'])->name('cart.update-variant');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product management
    Route::resource('products', AdminProductController::class);
    
    // Order management
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    
    // Review management
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('reviews/bulk-action', [AdminReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
    
    // API routes for admin
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');
        Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
        
        // Order status update API
        Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});

