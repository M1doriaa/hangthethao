<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Có thể thêm logic lấy sản phẩm nổi bật, danh mục, v.v.
        $featuredProducts = $this->getFeaturedProducts();
        $footballJerseys = $this->getFootballJerseys();
        $categories = $this->getCategories();
        
        return view('home', compact('featuredProducts', 'footballJerseys', 'categories'));
    }
      /**
     * Lấy danh sách sản phẩm nổi bật
     */
    private function getFeaturedProducts()
    {
        // Tạm thời return dữ liệu mẫu, sau này sẽ lấy từ database
        return [
            [
                'id' => 1,
                'name' => 'Áo đấu năm 1999',
                'price' => 668000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=Jersey+1999',
                'stock_quantity' => 15
            ],
            [
                'id' => 2,
                'name' => 'Cốc gốm màu hồng MU Đỏng',
                'price' => 270000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=MU+Cup',
                'stock_quantity' => 25
            ],
            [
                'id' => 3,
                'name' => 'Cốc gốm Feniec Đỏng MU',
                'price' => 270000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=Feniec+Cup',
                'stock_quantity' => 20
            ],
            [
                'id' => 4,
                'name' => 'Cốc gốm HANG MU Đỏng',
                'price' => 270000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=HANG+MU+Cup',
                'stock_quantity' => 18
            ],
            [
                'id' => 5,
                'name' => 'Cốc giữ nhiệt Cầu thủ MU',
                'price' => 270000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=MU+Thermos',
                'stock_quantity' => 12
            ],
            [
                'id' => 6,
                'name' => 'Cốc gốm mới Logo MU DỎ',
                'price' => 270000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x200/ffffff/cccccc?text=MU+Logo+Cup',
                'stock_quantity' => 22
            ]
        ];
    }
    
    /**
     * Lấy danh sách danh mục
     */
    private function getCategories()
    {
        return [
            [
                'id' => 1,
                'name' => 'Đồ bóng đá',
                'description' => 'Áo, quần, giày bóng đá chính hãng từ các thương hiệu nổi tiếng',
                'icon' => '⚽'
            ],
            [
                'id' => 2,
                'name' => 'Phụ kiện',
                'description' => 'Găng tay, túi xách, bình nước và các phụ kiện thể thao khác',
                'icon' => '🎽'
            ],
            [
                'id' => 3,
                'name' => 'Đồ bóng rổ',
                'description' => 'Áo, quần, giày bóng rổ từ các thương hiệu hàng đầu thế giới',
                'icon' => '🏀'
            ]
        ];
    }

    /**
     * Lấy danh sách áo bóng đá
     */
    private function getFootballJerseys()
    {
        // Tạm thời return dữ liệu mẫu cho Football Jerseys section
        return [
            [
                'id' => 'jersey_1',
                'name' => 'MU Home (2024-2025) Mẫu Đẹp Cực Chất Áo thun nam ngắn tay',
                'price' => 410000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x250/C41E3A/ffffff?text=MU+Home+Jersey',
                'stock_quantity' => 25
            ],
            [
                'id' => 'jersey_2',
                'name' => 'MU Away (2024-2025) Mẫu gốm áo ĐẸP REAL MADRID Ký thi hét ngắn tay',
                'price' => 460000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x250/000080/ffffff?text=MU+Away+Jersey',
                'stock_quantity' => 20
            ],
            [
                'id' => 'jersey_3',
                'name' => 'MU Third (2024-2025) Mẫu Đẹp Cực Chất Áo thun nam ngắn tay',
                'price' => 410000,
                'rating' => 4,
                'image' => 'https://via.placeholder.com/200x250/ffffff/000000?text=MU+Third+Jersey',
                'stock_quantity' => 15
            ],
            [
                'id' => 'jersey_4',
                'name' => 'MU Home (2024-2025) Đặc biệt áo ngắn tay | Không có quần',
                'price' => 460000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x250/C41E3A/ffffff?text=MU+Home+2024',
                'stock_quantity' => 30
            ],
            [
                'id' => 'jersey_5',
                'name' => 'MU Away (2024-2025) Đặc biệt áo ngắn tay | Không có quần',
                'price' => 460000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x250/000080/ffffff?text=MU+Away+2024',
                'stock_quantity' => 28
            ],
            [
                'id' => 'jersey_6',
                'name' => 'MU Special Edition (2024-2025) Phiên bản giới hạn',
                'price' => 550000,
                'rating' => 5,
                'image' => 'https://via.placeholder.com/200x250/FFD700/000000?text=MU+Special',
                'stock_quantity' => 10
            ]
        ];
    }
}
