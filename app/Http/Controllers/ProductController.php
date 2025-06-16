<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị chi tiết sản phẩm từ database
     */    public function show($id)
    {
        // Lấy sản phẩm từ database
        $product = Product::active()->findOrFail($id);
        
        // Lấy sản phẩm liên quan cùng danh mục
        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }
}
