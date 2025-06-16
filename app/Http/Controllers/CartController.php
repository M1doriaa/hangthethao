<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        if (auth()->check()) {
            // User đã đăng nhập - lấy từ database
            $cartItems = \App\Models\Cart::with(['product.activeVariants', 'variant'])
                ->where('user_id', auth()->id())
                ->get();
            
            // Convert to format compatible with view
            $cartItemsFormatted = $cartItems->map(function($item) {
                $product = $item->product;
                $availableSizes = [];
                $availableColors = [];
                
                // Nếu sản phẩm có variants, lấy danh sách size và color có sẵn
                if ($product->has_variants && $product->activeVariants->count() > 0) {
                    $availableSizes = $product->activeVariants->pluck('size')->unique()->filter()->values()->toArray();
                    $availableColors = $product->activeVariants->pluck('color')->unique()->filter()->values()->toArray();
                }
                
                return [
                    'id' => $item->product_id,
                    'name' => $product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'size' => $item->variant->size ?? null,
                    'color' => $item->variant->color ?? null,
                    'image' => $product->main_image,
                    'variant_id' => $item->variant_id,
                    'cart_id' => $item->id,
                    'available_sizes' => $availableSizes,
                    'available_colors' => $availableColors,
                    'has_variants' => $product->has_variants,
                ];
            })->toArray();
            
            $cartSummary = $this->calculateCartSummaryFromDb($cartItems);        } else {
            // Guest user - lấy từ database với session_id
            $sessionId = session()->getId();
            $cartItems = \App\Models\Cart::with(['product.activeVariants', 'variant'])
                ->where('session_id', $sessionId)
                ->get();
            
            // Convert to format compatible with view
            $cartItemsFormatted = $cartItems->map(function($item) {
                $product = $item->product;
                $availableSizes = [];
                $availableColors = [];
                
                // Nếu sản phẩm có variants, lấy danh sách size và color có sẵn
                if ($product->has_variants && $product->activeVariants->count() > 0) {
                    $availableSizes = $product->activeVariants->pluck('size')->unique()->filter()->values()->toArray();
                    $availableColors = $product->activeVariants->pluck('color')->unique()->filter()->values()->toArray();
                }
                
                return [
                    'id' => $item->product_id,
                    'name' => $product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'size' => $item->variant->size ?? null,
                    'color' => $item->variant->color ?? null,
                    'image' => $product->main_image,
                    'variant_id' => $item->variant_id,
                    'cart_id' => $item->id,
                    'available_sizes' => $availableSizes,
                    'available_colors' => $availableColors,
                    'has_variants' => $product->has_variants,
                ];
            })->toArray();
            
            $cartSummary = $this->calculateCartSummaryFromDb($cartItems);
        }
        
        // Lấy sản phẩm gợi ý từ database
        $recommendedProducts = $this->getRecommendedProducts();
        
        return view('cart.index', [
            'cartItems' => $cartItemsFormatted,
            'cartSummary' => $cartSummary,
            'recommendedProducts' => $recommendedProducts
        ]);
    }/**
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
            'image' => 'nullable|string',
            'variant_id' => 'nullable|integer'
        ]);
        
        // Lấy thông tin sản phẩm từ database để validate
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại!'
            ], 404);
        }
        
        // Nếu user đã đăng nhập, lưu vào database
        if (auth()->check()) {
            return $this->addToUserCart($request, $product);
        }
        
        // Nếu chưa đăng nhập, lưu vào session như cũ
        return $this->addToSessionCart($request, $product);
    }    /**
     * Thêm sản phẩm vào giỏ hàng của user đã đăng nhập
     */
    private function addToUserCart(Request $request, Product $product)
    {
        // Sử dụng database Cart model
        $existingCart = \App\Models\Cart::where('user_id', auth()->id())
            ->where('product_id', $request->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingCart) {
            // Nếu sản phẩm đã có trong giỏ, tăng số lượng
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            \App\Models\Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_count' => \App\Models\Cart::where('user_id', auth()->id())->sum('quantity')
        ]);
    }    /**
     * Thêm sản phẩm vào giỏ hàng session (guest users)
     */
    private function addToSessionCart(Request $request, Product $product)
    {
        $sessionId = session()->getId();
        
        // Sử dụng database Cart model cho guest users với session_id
        $existingCart = \App\Models\Cart::where('session_id', $sessionId)
            ->where('product_id', $request->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingCart) {
            // Nếu sản phẩm đã có trong giỏ, tăng số lượng
            $existingCart->quantity += $request->quantity;
            $existingCart->save();
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            \App\Models\Cart::create([
                'user_id' => null,
                'session_id' => $sessionId,
                'product_id' => $request->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_count' => \App\Models\Cart::where('session_id', $sessionId)->sum('quantity')
        ]);    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);
        
        if (auth()->check()) {
            // User đã đăng nhập - cập nhật trong database
            $cartItem = \App\Models\Cart::where('id', $request->cart_id)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($cartItem) {
                $cartItem->update(['quantity' => $request->quantity]);
                
                $allItems = \App\Models\Cart::where('user_id', auth()->id())->get();
                $cartSummary = $this->calculateCartSummaryFromDb($allItems);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật giỏ hàng!',
                    'cart_summary' => $cartSummary
                ]);
            }
        } else {
            // Guest user - cập nhật trong database với session_id
            $sessionId = session()->getId();
            $cartItem = \App\Models\Cart::where('id', $request->cart_id)
                ->where('session_id', $sessionId)
                ->first();
                
            if ($cartItem) {
                $cartItem->update(['quantity' => $request->quantity]);
                
                $allItems = \App\Models\Cart::where('session_id', $sessionId)->get();
                $cartSummary = $this->calculateCartSummaryFromDb($allItems);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật giỏ hàng!',
                    'cart_summary' => $cartSummary
                ]);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
        ], 404);
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer'
        ]);
        
        if (auth()->check()) {
            // User đã đăng nhập - xóa từ database
            $cartItem = \App\Models\Cart::where('id', $request->cart_id)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($cartItem) {
                $cartItem->delete();
                
                $remainingItems = \App\Models\Cart::where('user_id', auth()->id())->get();
                $cartSummary = $this->calculateCartSummaryFromDb($remainingItems);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                    'cart_count' => $remainingItems->sum('quantity'),
                    'cart_summary' => $cartSummary
                ]);
            }
        } else {
            // Guest user - xóa từ database với session_id
            $sessionId = session()->getId();
            $cartItem = \App\Models\Cart::where('id', $request->cart_id)
                ->where('session_id', $sessionId)
                ->first();
                
            if ($cartItem) {
                $cartItem->delete();
                
                $remainingItems = \App\Models\Cart::where('session_id', $sessionId)->get();
                $cartSummary = $this->calculateCartSummaryFromDb($remainingItems);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                    'cart_count' => $remainingItems->sum('quantity'),
                    'cart_summary' => $cartSummary
                ]);
            }
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
        if (auth()->check()) {
            // User đã đăng nhập - xóa từ database
            \App\Models\Cart::where('user_id', auth()->id())->delete();
        } else {
            // Guest user - xóa từ database với session_id
            $sessionId = session()->getId();
            \App\Models\Cart::where('session_id', $sessionId)->delete();
        }
        
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
        if (auth()->check()) {
            // User đã đăng nhập - đếm từ database
            $totalItems = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
        } else {
            // Guest user - đếm từ database với session_id
            $sessionId = session()->getId();
            $totalItems = \App\Models\Cart::where('session_id', $sessionId)->sum('quantity');
        }        
        return response()->json([
            'cart_count' => $totalItems ?? 0
        ]);
    }
    
    /**
     * Lấy thông tin giỏ hàng từ session với thông tin variants
     */
    private function getCartItems()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        
        foreach ($cart as $key => $item) {
            // Lấy thông tin sản phẩm và variants từ database
            $product = Product::with('activeVariants')->find($item['id']);
            
            if ($product) {
                $cartItem = $item;
                $cartItem['product'] = $product;
                
                // Nếu sản phẩm có variants, lấy danh sách size và color available
                if ($product->has_variants) {
                    $cartItem['available_sizes'] = $product->getAvailableSizes();
                    $cartItem['available_colors'] = $product->getAvailableColors();
                    
                    // Lấy variants cho size hiện tại để tính giá
                    $cartItem['variants_for_size'] = $product->activeVariants()
                        ->where('size', $item['size'])
                        ->get()
                        ->keyBy('color');
                    
                    // Lấy variants cho color hiện tại để tính giá
                    $cartItem['variants_for_color'] = $product->activeVariants()
                        ->where('color', $item['color'])
                        ->get()
                        ->keyBy('size');
                } else {
                    $cartItem['available_sizes'] = $product->sizes ?? [];
                    $cartItem['available_colors'] = $product->colors ?? [];
                }
                
                $cartItems[$key] = $cartItem;
            }
        }
        
        return $cartItems;
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
    }    /**
     * Tính tổng giá trị giỏ hàng
     */
    private function calculateCartTotal($cart)
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

    /**
     * Cập nhật variant (size/color) của sản phẩm trong giỏ
     */
    public function updateVariant(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string'
        ]);
        
        try {
            if (auth()->check()) {
                // User đã đăng nhập
                $userId = auth()->id();
                $cartItem = \App\Models\Cart::where('user_id', $userId)
                    ->where('id', $request->key)
                    ->first();
            } else {
                // Guest user
                $sessionId = session()->getId();
                $cartItem = \App\Models\Cart::where('session_id', $sessionId)
                    ->where('id', $request->key)
                    ->first();
            }
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
                ], 404);
            }
            
            $product = Product::with('activeVariants')->find($cartItem->product_id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại!'
                ], 404);
            }
            
            // Nếu sản phẩm có variants, tìm variant mới
            if ($product->has_variants) {
                $variant = $product->getVariant($request->size, $request->color);
                
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy biến thể phù hợp!'
                    ], 400);
                }
                
                // Kiểm tra tồn kho
                if ($variant->stock_quantity < $cartItem->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không đủ hàng trong kho. Chỉ còn ' . $variant->stock_quantity . ' sản phẩm!'
                    ], 400);
                }
                
                // Kiểm tra xem đã có sản phẩm với variant này chưa
                $existingCartItem = null;
                if (auth()->check()) {
                    $existingCartItem = \App\Models\Cart::where('user_id', $userId)
                        ->where('product_id', $product->id)
                        ->where('variant_id', $variant->id)
                        ->where('id', '!=', $cartItem->id)
                        ->first();
                } else {
                    $existingCartItem = \App\Models\Cart::where('session_id', $sessionId)
                        ->where('product_id', $product->id)
                        ->where('variant_id', $variant->id)
                        ->where('id', '!=', $cartItem->id)
                        ->first();
                }
                
                if ($existingCartItem) {
                    // Merge với item đã có
                    $existingCartItem->quantity += $cartItem->quantity;
                    $existingCartItem->save();
                    
                    // Xóa item cũ
                    $cartItem->delete();
                    
                    $responseKey = $existingCartItem->id;
                    $updatedItem = [
                        'id' => $existingCartItem->product_id,
                        'name' => $product->name,
                        'price' => $existingCartItem->price,
                        'quantity' => $existingCartItem->quantity,
                        'size' => $variant->size,
                        'color' => $variant->color,
                        'image' => $product->main_image,
                        'variant_id' => $variant->id,
                        'cart_id' => $existingCartItem->id,
                    ];
                } else {
                    // Cập nhật item hiện tại
                    $cartItem->variant_id = $variant->id;
                    $cartItem->price = $variant->getCurrentPrice();
                    $cartItem->save();
                    
                    $responseKey = $cartItem->id;
                    $updatedItem = [
                        'id' => $cartItem->product_id,
                        'name' => $product->name,
                        'price' => $cartItem->price,
                        'quantity' => $cartItem->quantity,
                        'size' => $variant->size,
                        'color' => $variant->color,
                        'image' => $product->main_image,
                        'variant_id' => $variant->id,
                        'cart_id' => $cartItem->id,
                    ];
                }
            } else {
                // Sản phẩm đơn giản - không có variants
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm này không có variants!'
                ], 400);
            }
            
            // Tính toán lại summary
            if (auth()->check()) {
                $cartItems = \App\Models\Cart::where('user_id', auth()->id())->get();
            } else {
                $cartItems = \App\Models\Cart::where('session_id', session()->getId())->get();
            }
            
            $cartSummary = $this->calculateCartSummaryFromDb($cartItems);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật sản phẩm!',
                'updated_item' => $updatedItem,
                'new_key' => $responseKey,
                'old_key' => $request->key,
                'cart_summary' => $cartSummary
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Update variant error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * Tính tổng giỏ hàng từ database
     */
    private function calculateCartSummaryFromDb($cartItems)
    {
        $subtotal = 0;
        $count = 0;
        
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
            $count += $item->quantity;
        }
        
        $vat = $subtotal * 0.1; // VAT 10%
        $shipping = $subtotal >= 500000 ? 0 : 30000; // Miễn phí vận chuyển nếu >= 500k
          return [
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'count' => $count,
            'shipping' => $shipping,
            'tax' => $vat, // Đổi từ 'vat' thành 'tax' để match với view
            'vat' => $vat,
            'grand_total' => $subtotal + $vat + $shipping
        ];
    }
}
