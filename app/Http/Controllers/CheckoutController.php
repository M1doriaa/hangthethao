<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        // Lấy cart items từ database cho user hiện tại hoặc session
        if (auth()->check()) {
            $cartItems = Cart::with(['product', 'variant'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $sessionId = session()->getId();
            $cartItems = Cart::with(['product', 'variant'])
                ->where('session_id', $sessionId)
                ->get();
        }
        
        // Nếu giỏ hàng trống, redirect về trang chủ
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm để thanh toán.');
        }

        // Tính tổng giá trị đơn hàng
        $total = 0;
        foreach ($cartItems as $item) {
            if (isset($item->variant) && $item->variant) {
                $total += $item->variant->price * $item->quantity;
            } else {
                $total += $item->price * $item->quantity;
            }
        }
        
        // Hiển thị checkout page
        return view('checkout.simple', compact('cartItems', 'total'));
    }

    /**
     * Xử lý thanh toán
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string|max:500',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại',
            'customer_email.email' => 'Email không đúng định dạng',
            'customer_address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
        ]);

        // Lấy cart items từ database
        if (auth()->check()) {
            $cartItems = Cart::with(['product', 'variant'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $sessionId = session()->getId();
            $cartItems = Cart::with(['product', 'variant'])
                ->where('session_id', $sessionId)
                ->get();
        }
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        
        try {
            // Tính toán tổng tiền
            $subtotal = 0;
            $validCartItems = [];

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                if (!$product) {
                    throw new \Exception("Sản phẩm không còn tồn tại");
                }
                
                $variant = $cartItem->variant;
                $price = $variant ? $variant->price : $product->price;
                $total = $price * $cartItem->quantity;
                
                // Kiểm tra stock (chỉ với sản phẩm không có variant)
                if (!$variant && $product->stock_quantity < $cartItem->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} chỉ còn {$product->stock_quantity} sản phẩm");
                }

                $validCartItems[] = [
                    'cart_item' => $cartItem,
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $cartItem->quantity,
                    'price' => $price,
                    'total' => $total
                ];

                $subtotal += $total;
            }

            // Phí vận chuyển miễn phí
            $shippingFee = 0;
            $tax = 0;
            $total = $subtotal + $shippingFee + $tax;            // Tạo địa chỉ đầy đủ
            $fullAddress = $request->customer_address;
            if ($request->ward) $fullAddress = $request->ward . ', ' . $fullAddress;
            if ($request->district) $fullAddress = $request->district . ', ' . $fullAddress;
            if ($request->province) $fullAddress = $request->province . ', ' . $fullAddress;

            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $fullAddress,
                'city' => $request->province ?? '',
                'district' => $request->district ?? '',
                'ward' => $request->ward ?? '',
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'total' => $total,
                'notes' => $request->notes ?? '',
            ]);

            // Tạo order items
            foreach ($validCartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_image' => $item['product']->image,
                    'size' => $item['variant'] ? $item['variant']->size : null,
                    'color' => $item['variant'] ? $item['variant']->color : null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                // Giảm số lượng tồn kho (chỉ với sản phẩm không có variant)
                if (!$item['variant']) {
                    $item['product']->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            // Xóa giỏ hàng sau khi đặt hàng thành công
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
            } else {
                Cart::where('session_id', session()->getId())->delete();
            }

            // Lưu order_id vào session để hiển thị thông báo
            session(['last_order_id' => $order->id]);

            return redirect()->route('home')->with([
                'success' => 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number,
                'order_number' => $order->order_number,
                'order_total' => number_format($order->total, 0, ',', '.') . 'đ'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Trang thành công
     */
    public function success()
    {
        $order = null;
        
        // Lấy thông tin đơn hàng vừa tạo
        if (session('last_order_id')) {
            $order = Order::with('orderItems')->find(session('last_order_id'));
            session()->forget('last_order_id');
        }

        return view('checkout.success', compact('order'));
    }
}
