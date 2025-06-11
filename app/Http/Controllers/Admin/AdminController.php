<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'featured_products' => Product::featured()->count(),
            'out_of_stock' => Product::where('stock_quantity', 0)->count(),
            
            // Thống kê đơn hàng
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'today_orders' => Order::whereDate('created_at', Carbon::today())->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total'),
        ];

        $recent_products = Product::latest()->limit(5)->get();
        $low_stock_products = Product::where('stock_quantity', '<=', 10)
                                   ->where('stock_quantity', '>', 0)
                                   ->limit(5)
                                   ->get();

        // Thêm đơn hàng gần đây
        $recent_orders = Order::with('orderItems')
                              ->latest()
                              ->limit(5)
                              ->get();

        return view('admin.dashboard', compact('stats', 'recent_products', 'low_stock_products', 'recent_orders'));
    }
}
