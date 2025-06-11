<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        // Lấy dữ liệu giỏ hàng từ session hoặc cookie
        $cartItems = $this->getCartItems();
        $cartSummary = $this->calculateCartSummary($cartItems);
        
        // Lấy sản phẩm gợi ý từ database
        $recommendedProducts = $this->getRecommendedProducts();
        
        return view('cart.index', compact('cartItems', 'cartSummary', 'recommendedProducts'));
    }
      /**
     * Thêm sản phẩm vào giỏ hàng (API)
     */
    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'image' => 'nullable|string'
        ]);
        
        // Lấy giỏ hàng hiện tại
        $cart = session()->get('cart', []);
        
        // Tạo key duy nhất cho sản phẩm
        $productKey = $request->id . '_' . $request->size . '_' . $request->color;
        
        if (isset($cart[$productKey])) {
            // Nếu sản phẩm đã có trong giỏ, tăng số lượng
            $cart[$productKey]['quantity'] += $request->quantity;
        } else {
            // Thêm sản phẩm mới
            $cart[$productKey] = [
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
                'image' => $request->image,
            ];
        }
        
        // Lưu vào session
        session()->put('cart', $cart);
        
        // Tính tổng số lượng sản phẩm
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        // Trả về JSON response cho AJAX
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cart_count' => $totalItems,
                'product' => $cart[$productKey],
                'cart_total' => $this->calculateCartTotal($cart)
            ]);
        }
        
        // Redirect cho form thường
        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }
    
    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            $cartSummary = $this->calculateCartSummary($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng!',
                'cart_summary' => $cartSummary
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
        ], 404);
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->key])) {
            unset($cart[$request->key]);
            session()->put('cart', $cart);
            
            $totalItems = array_sum(array_column($cart, 'quantity'));
            $cartSummary = $this->calculateCartSummary($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cart_count' => $totalItems,
                'cart_summary' => $cartSummary
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
        ], 404);
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng!',
            'cart_count' => 0
        ]);
    }
    
    /**
     * Lấy số lượng sản phẩm trong giỏ hàng
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'cart_count' => $totalItems
        ]);
    }
    
    /**
     * Lấy dữ liệu giỏ hàng
     */
    private function getCartItems()
    {
        return session()->get('cart', []);
    }
    
    /**
     * Tính toán tổng kết giỏ hàng
     */
    private function calculateCartSummary($cartItems)
    {
        $totalItems = 0;
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $totalItems += $item['quantity'];
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $shipping = $subtotal >= 500000 ? 0 : 30000; // Miễn phí ship từ 500k
        $tax = $subtotal * 0.1; // VAT 10%
        $total = $subtotal + $shipping + $tax;
        
        return [
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total
        ];
    }

    /**
     * Tính tổng giá trị giỏ hàng
     */    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Lấy sản phẩm gợi ý từ database
     */
    private function getRecommendedProducts()
    {
        // Lấy 6 sản phẩm nổi bật hoặc mới nhất để gợi ý
        return Product::active()
            ->where('is_featured', true)
            ->orWhere('created_at', '>=', now()->subDays(30))
            ->inRandomOrder()
            ->limit(6)
            ->get();
    }
}
