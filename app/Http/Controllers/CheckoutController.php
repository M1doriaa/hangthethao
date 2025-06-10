<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        // Demo cart items for checkout
        $cartItems = [
            [
                'id' => 1,
                'name' => 'Mũ Home (2024-2025) Màn 30 x Cầu',
                'price' => 410000,
                'quantity' => 1,
                'image' => 'https://via.placeholder.com/80x80?text=Hat',
                'size' => 'L'
            ]
        ];

        $total = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Xử lý thanh toán
     */
    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'full_name' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        // Xử lý logic thanh toán ở đây
        
        return redirect()->route('checkout.success')->with('success', 'Đặt hàng thành công!');
    }

    /**
     * Trang thành công
     */
    public function success()
    {
        return view('checkout.success');
    }
}
