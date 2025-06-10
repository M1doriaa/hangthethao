<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Category routes
Route::get('/category/{category}', [CategoryController::class, 'index'])->name('category.index');
Route::get('/search', [CategoryController::class, 'search'])->name('search');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Test route (only in development)
if (config('app.debug')) {
    Route::get('/test-cart', function () {
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        return view('test-cart', compact('cartCount'));
    })->name('test.cart');

    Route::get('/cart-debug', function () {
        return view('cart-debug');
    })->name('cart.debug');
}
