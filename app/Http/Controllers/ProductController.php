<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($id)
    {
        // Tạm thời sử dụng dữ liệu mẫu, sau này sẽ lấy từ database
        $product = $this->getProductById($id);
        $relatedProducts = $this->getRelatedProducts($product['category_id'] ?? 1);
        
        if (!$product) {
            abort(404);
        }
        
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    /**
     * Lấy thông tin sản phẩm theo ID
     */
    private function getProductById($id)
    {
        $products = [
            1 => [
                'id' => 1,
                'name' => 'Áo Khoác Gió Đức Màu Đen',
                'slug' => 'ao-khoac-gio-duc-mau-den',
                'category_id' => 1,
                'category_name' => 'Đồ bóng đá',
                'brand' => 'Adidas',
                'price' => 500000,
                'original_price' => 650000,
                'discount_percent' => 23,
                'rating' => 5,
                'total_reviews' => 128,
                'in_stock' => true,
                'stock_quantity' => 50,
                'sku' => 'AGD001',
                'description' => 'Áo khoác gió chính hãng từ Đức với chất liệu cao cấp, thiết kế thể thao hiện đại.',
                'features' => [
                    'Logo đính trang trí và chất liệu',
                    'Chất liệu vải đẹp Polyester cao cấp',
                    'phOrm - phOrm Fit - phOrm Phát',
                    'Áo khoác gió cao Gon 2 lớp',
                    'Ống GAM ÁO Ô',
                    'Chất liệu khiến sức cao GAO',
                    'Vải chống gió đặc trị 100%',
                    'Thiết kế ống tay độc đáo phần cổ có thể theo ý',
                    'Phía trên như cúc có thể',
                    'Khóa kéo ở áo',
                    'Sống Gãi ở áo toàn bộ công áo',
                    'Họa tiết in trên áo theo áo',
                    'Thiết kể mô thể sức khốn với chất da áo như cao áo bộ ghi da áo trang phúc áo',
                    'Có thể in áo từ trong áo thành áo',
                    'Hãy tin vào và mặc có suc đăng ký FB chao cùa SKT: 035 893 353'
                ],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Đen', 'Trắng', 'Xanh'],
                'images' => [
                    'main' => '/images/products/ao-khoac-gio-duc-main.jpg',
                    'gallery' => [
                        '/images/products/ao-khoac-gio-duc-1.jpg',
                        '/images/products/ao-khoac-gio-duc-2.jpg',
                        '/images/products/ao-khoac-gio-duc-3.jpg',
                        '/images/products/ao-khoac-gio-duc-4.jpg',
                        '/images/products/ao-khoac-gio-duc-5.jpg',
                    ]
                ]
            ]
        ];
        
        return $products[$id] ?? null;
    }
    
    /**
     * Lấy sản phẩm liên quan
     */
    private function getRelatedProducts($categoryId)
    {
        return [
            [
                'id' => 2,
                'name' => 'Áo khoác gió Pháp màu Trắng',
                'price' => 460000,
                'original_price' => 600000,
                'image' => '/images/products/ao-phap-trang.jpg',
                'rating' => 5
            ],
            [
                'id' => 3,
                'name' => 'Áo Home (2024-2025)',
                'price' => 460000,
                'original_price' => 580000,
                'image' => '/images/products/ao-home-2024.jpg',
                'rating' => 5
            ],
            [
                'id' => 4,
                'name' => 'Áo Away (2024-2025) Màu tím than áo Chất Player',
                'price' => 460000,
                'original_price' => 580000,
                'image' => '/images/products/ao-away-tim.jpg',
                'rating' => 5
            ],
            [
                'id' => 5,
                'name' => 'TBM Home (2024-2025) Đặc biệt Cực Chất',
                'price' => 460000,
                'original_price' => 580000,
                'image' => '/images/products/tbm-home.jpg',
                'rating' => 5
            ],
            [
                'id' => 6,
                'name' => 'Argentina Home (2024-2025) Màu xanh dương',
                'price' => 460000,
                'original_price' => 580000,
                'image' => '/images/products/argentina-home.jpg',
                'rating' => 5
            ],
            [
                'id' => 7,
                'name' => 'BSN Home (2024-2025) Đặc biệt cả quần',
                'price' => 460000,
                'original_price' => 580000,
                'image' => '/images/products/bsn-home.jpg',
                'rating' => 5
            ]
        ];
    }
}
