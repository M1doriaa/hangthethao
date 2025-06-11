<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShareCartData
{    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Share cart count with all views
            $cart = session()->get('cart', []);
            $cartCount = is_array($cart) ? array_sum(array_column($cart, 'quantity')) : 0;
            
            View::share('cartCount', $cartCount);
        } catch (\Exception $e) {
            // Fallback to 0 if there's any error
            View::share('cartCount', 0);
        }
        
        return $next($request);
    }
}
