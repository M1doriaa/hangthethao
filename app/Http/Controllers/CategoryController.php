<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm theo danh mục
     */
    public function index($category)
    {
        $categories = [
            'ao-clb' => 'Áo CLB',
            'ao-doi-tuyen' => 'Áo Đội Tuyển Quốc Gia', 
            'phu-kien' => 'Phụ Kiện'
        ];

        // Kiểm tra danh mục có hợp lệ không
        if (!array_key_exists($category, $categories)) {
            abort(404);
        }

        $categoryName = $categories[$category];
        $products = $this->getProductsByCategory($category);

        return view('categories.index', compact('products', 'categoryName', 'category'));
    }

    /**
     * Lấy sản phẩm theo danh mục (demo data)
     */
    private function getProductsByCategory($category)
    {
        $products = [];

        switch ($category) {
            case 'ao-clb':
                $products = [
                    [
                        'id' => 101,
                        'name' => 'Áo Manchester United Home 2024',
                        'price' => 890000,
                        'image' => 'https://via.placeholder.com/300x300/C41E3A/ffffff?text=MU+Home',
                        'rating' => 5,
                        'stock_quantity' => 15,
                        'description' => 'Áo đấu chính thức Manchester United mùa giải 2024'
                    ],
                    [
                        'id' => 102,
                        'name' => 'Áo Barcelona Home 2024',
                        'price' => 920000,
                        'image' => 'https://via.placeholder.com/300x300/A50044/ffffff?text=Barca+Home',
                        'rating' => 5,
                        'stock_quantity' => 12,
                        'description' => 'Áo đấu chính thức FC Barcelona mùa giải 2024'
                    ],
                    [
                        'id' => 103,
                        'name' => 'Áo Real Madrid Home 2024',
                        'price' => 950000,
                        'image' => 'https://via.placeholder.com/300x300/ffffff/000000?text=Real+Home',
                        'rating' => 5,
                        'stock_quantity' => 18,
                        'description' => 'Áo đấu chính thức Real Madrid mùa giải 2024'
                    ],
                    [
                        'id' => 104,
                        'name' => 'Áo Liverpool Home 2024',
                        'price' => 880000,
                        'image' => 'https://via.placeholder.com/300x300/C8102E/ffffff?text=Liverpool+Home',
                        'rating' => 4,
                        'stock_quantity' => 20,
                        'description' => 'Áo đấu chính thức Liverpool FC mùa giải 2024'
                    ],
                    [
                        'id' => 105,
                        'name' => 'Áo Arsenal Home 2024',
                        'price' => 860000,
                        'image' => 'https://via.placeholder.com/300x300/EF0107/ffffff?text=Arsenal+Home',
                        'rating' => 4,
                        'stock_quantity' => 14,
                        'description' => 'Áo đấu chính thức Arsenal FC mùa giải 2024'
                    ],
                    [
                        'id' => 106,
                        'name' => 'Áo Chelsea Home 2024',
                        'price' => 870000,
                        'image' => 'https://via.placeholder.com/300x300/034694/ffffff?text=Chelsea+Home',
                        'rating' => 4,
                        'stock_quantity' => 16,
                        'description' => 'Áo đấu chính thức Chelsea FC mùa giải 2024'
                    ],
                    [
                        'id' => 107,
                        'name' => 'Áo PSG Home 2024',
                        'price' => 910000,
                        'image' => 'https://via.placeholder.com/300x300/004170/ffffff?text=PSG+Home',
                        'rating' => 5,
                        'stock_quantity' => 10,
                        'description' => 'Áo đấu chính thức Paris Saint-Germain mùa giải 2024'
                    ],
                    [
                        'id' => 108,
                        'name' => 'Áo Bayern Munich Home 2024',
                        'price' => 930000,
                        'image' => 'https://via.placeholder.com/300x300/DC052D/ffffff?text=Bayern+Home',
                        'rating' => 5,
                        'stock_quantity' => 13,
                        'description' => 'Áo đấu chính thức Bayern Munich mùa giải 2024'
                    ]
                ];
                break;

            case 'ao-doi-tuyen':
                $products = [
                    [
                        'id' => 201,
                        'name' => 'Áo Đội Tuyển Việt Nam Home 2024',
                        'price' => 650000,
                        'image' => 'https://via.placeholder.com/300x300/DA020E/ffffff?text=VN+Home',
                        'rating' => 5,
                        'stock_quantity' => 25,
                        'description' => 'Áo đấu chính thức Đội Tuyển Việt Nam mùa giải 2024'
                    ],
                    [
                        'id' => 202,
                        'name' => 'Áo Đội Tuyển Brazil Home 2024',
                        'price' => 750000,
                        'image' => 'https://via.placeholder.com/300x300/FBDD00/009739?text=Brazil+Home',
                        'rating' => 5,
                        'stock_quantity' => 15,
                        'description' => 'Áo đấu chính thức Đội Tuyển Brazil World Cup 2024'
                    ],
                    [
                        'id' => 203,
                        'name' => 'Áo Đội Tuyển Argentina Home 2024',
                        'price' => 780000,
                        'image' => 'https://via.placeholder.com/300x300/75AADB/ffffff?text=Argentina+Home',
                        'rating' => 5,
                        'stock_quantity' => 12,
                        'description' => 'Áo đấu chính thức Đội Tuyển Argentina World Cup 2024'
                    ],
                    [
                        'id' => 204,
                        'name' => 'Áo Đội Tuyển Pháp Home 2024',
                        'price' => 770000,
                        'image' => 'https://via.placeholder.com/300x300/002395/ffffff?text=France+Home',
                        'rating' => 4,
                        'stock_quantity' => 18,
                        'description' => 'Áo đấu chính thức Đội Tuyển Pháp Euro 2024'
                    ],
                    [
                        'id' => 205,
                        'name' => 'Áo Đội Tuyển Đức Home 2024',
                        'price' => 760000,
                        'image' => 'https://via.placeholder.com/300x300/ffffff/000000?text=Germany+Home',
                        'rating' => 4,
                        'stock_quantity' => 20,
                        'description' => 'Áo đấu chính thức Đội Tuyển Đức Euro 2024'
                    ],
                    [
                        'id' => 206,
                        'name' => 'Áo Đội Tuyển Anh Home 2024',
                        'price' => 740000,
                        'image' => 'https://via.placeholder.com/300x300/ffffff/C8102E?text=England+Home',
                        'rating' => 4,
                        'stock_quantity' => 16,
                        'description' => 'Áo đấu chính thức Đội Tuyển Anh Euro 2024'
                    ]
                ];
                break;

            case 'phu-kien':
                $products = [
                    [
                        'id' => 301,
                        'name' => 'Găng Tay Thủ Môn Adidas Predator',
                        'price' => 450000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=Predator+Gloves',
                        'rating' => 5,
                        'stock_quantity' => 8,
                        'description' => 'Găng tay thủ môn chuyên nghiệp Adidas Predator'
                    ],
                    [
                        'id' => 302,
                        'name' => 'Túi Đựng Giày Nike Academy',
                        'price' => 280000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=Nike+Bag',
                        'rating' => 4,
                        'stock_quantity' => 15,
                        'description' => 'Túi đựng giày thể thao Nike Academy chính hãng'
                    ],
                    [
                        'id' => 303,
                        'name' => 'Bình Nước Thể Thao 750ml',
                        'price' => 120000,
                        'image' => 'https://via.placeholder.com/300x300/C41E3A/ffffff?text=Water+Bottle',
                        'rating' => 4,
                        'stock_quantity' => 30,
                        'description' => 'Bình nước thể thao chống rò rỉ 750ml'
                    ],
                    [
                        'id' => 304,
                        'name' => 'Băng Đội Trưởng Nike',
                        'price' => 80000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=Captain+Band',
                        'rating' => 5,
                        'stock_quantity' => 25,
                        'description' => 'Băng đội trưởng Nike chính hãng nhiều màu'
                    ],
                    [
                        'id' => 305,
                        'name' => 'Tất Đá Bóng Adidas Performance',
                        'price' => 150000,
                        'image' => 'https://via.placeholder.com/300x300/ffffff/000000?text=Adidas+Socks',
                        'rating' => 4,
                        'stock_quantity' => 40,
                        'description' => 'Tất đá bóng Adidas Performance chống trượt'
                    ],
                    [
                        'id' => 306,
                        'name' => 'Balo Thể Thao Under Armour',
                        'price' => 680000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=UA+Backpack',
                        'rating' => 5,
                        'stock_quantity' => 12,
                        'description' => 'Balo thể thao Under Armour chống nước'
                    ],
                    [
                        'id' => 307,
                        'name' => 'Miếng Lót Ống Đồng Nike',
                        'price' => 200000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=Shin+Guards',
                        'rating' => 4,
                        'stock_quantity' => 20,
                        'description' => 'Miếng lót ống đồng Nike bảo vệ tối ưu'
                    ],
                    [
                        'id' => 308,
                        'name' => 'Khăn Thể Thao Puma',
                        'price' => 180000,
                        'image' => 'https://via.placeholder.com/300x300/000000/ffffff?text=Puma+Towel',
                        'rating' => 4,
                        'stock_quantity' => 35,
                        'description' => 'Khăn thể thao Puma siêu thấm hút'
                    ]
                ];
                break;
        }

        return $products;
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', 'all');
        
        if (empty($query)) {
            return redirect()->route('home')->with('error', 'Vui lòng nhập từ khóa tìm kiếm');
        }

        // Get all products from all categories
        $allProducts = array_merge(
            $this->getProductsByCategory('ao-clb'),
            $this->getProductsByCategory('ao-doi-tuyen'),
            $this->getProductsByCategory('phu-kien')
        );

        // Filter products based on search query
        $products = collect($allProducts)->filter(function ($product) use ($query) {
            return stripos($product['name'], $query) !== false || 
                   stripos($product['description'], $query) !== false;
        })->values()->all();

        return view('categories.search', compact('products', 'query', 'category'));
    }
}
