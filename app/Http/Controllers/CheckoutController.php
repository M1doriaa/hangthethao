<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        // Lấy cart data từ session
        $cartItems = session()->get('cart', []);
        
        // Nếu giỏ hàng trống, redirect về trang chủ
        if (empty($cartItems)) {
            return redirect()->route('home')->with('error', 'Giỏ hàng trống. Vui lòng thêm sản phẩm để thanh toán.');
        }
        
        // Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

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
            'payment_method' => 'required|string|in:cod,bank_transfer,momo',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'full_name.required' => 'Vui lòng nhập họ tên',
            'city.required' => 'Vui lòng chọn tỉnh/thành phố',
            'district.required' => 'Vui lòng chọn quận/huyện',
            'ward.required' => 'Vui lòng chọn phường/xã',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
        ]);

        // Lấy cart items từ session
        $cartItems = session()->get('cart', []);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();
        
        try {
            // Tính toán tổng tiền
            $subtotal = 0;
            $validCartItems = [];

            foreach ($cartItems as $productId => $item) {
                // Kiểm tra sản phẩm còn tồn tại và đủ số lượng
                $product = Product::active()->find($productId);
                
                if (!$product) {
                    throw new \Exception("Sản phẩm {$item['name']} không còn tồn tại");
                }
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Sản phẩm {$product->name} chỉ còn {$product->stock_quantity} sản phẩm");
                }

                $validCartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'price' => $product->price,
                    'total' => $product->price * $item['quantity']
                ];

                $subtotal += $product->price * $item['quantity'];
            }

            // Tính phí vận chuyển (cố định 30,000đ)
            $shippingFee = 30000;
            $tax = 0; // Có thể tính thuế VAT 10% nếu cần
            $total = $subtotal + $shippingFee + $tax;

            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->full_name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'shipping_address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'ward' => $request->ward,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'total' => $total,
            ]);

            // Tạo order items và cập nhật stock
            foreach ($validCartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_image' => $item['product']->main_image,
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                // Giảm số lượng tồn kho
                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            // Xóa giỏ hàng sau khi đặt hàng thành công
            session()->forget('cart');

            // Lưu order_id vào session để hiển thị ở trang success
            session(['last_order_id' => $order->id]);

            return redirect()->route('checkout.success')->with('success', 'Đặt hàng thành công!');
            
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
