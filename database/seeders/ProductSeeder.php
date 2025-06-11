<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Áo Đấu Manchester United 2024/25 Sân Nhà',
                'description' => 'Áo đấu chính thức Manchester United mùa giải 2024/25 sân nhà. Chất liệu Dri-FIT cao cấp, thoáng mát và khô ráo suốt 90 phút. Thiết kế màu đỏ truyền thống với logo Teamviewer.',
                'price' => 899000,
                'original_price' => 1199000,
                'sku' => 'MU-HOME-2024-25',                'slug' => 'ao-dau-manchester-united-2024-25-san-nha',
                'category' => 'ao-clb',
                'brand' => 'Adidas',
                'stock_quantity' => 50,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Đỏ'],
                'images' => [
                    'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop',
                    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=400&fit=crop'
                ],
                'is_featured' => true,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Đấu Manchester United 2024/25 Sân Nhà - Hang The Thao',
                'meta_description' => 'Mua áo đấu chính thức Manchester United 2024/25 sân nhà. Chất lượng cao, giá tốt nhất thị trường. Giao hàng toàn quốc.',
            ],
            [
                'name' => 'Áo Barcelona 2024/25 Sân Khách',
                'description' => 'Áo đấu Barcelona sân khách mùa giải 2024/25. Thiết kế hiện đại với màu xanh navy độc đáo. Công nghệ Nike Dri-FIT ADV giúp tối ưu hóa hiệu suất.',
                'price' => 859000,
                'original_price' => 1099000,
                'sku' => 'FCB-AWAY-2024-25',                'slug' => 'ao-barcelona-2024-25-san-khach',
                'category' => 'ao-clb',
                'brand' => 'Nike',
                'stock_quantity' => 35,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Đỏ'],
                'images' => [
                    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=400&fit=crop'
                ],
                'is_featured' => true,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Barcelona 2024/25 Sân Khách - Hang The Thao',
                'meta_description' => 'Áo đấu Barcelona sân khách chính hãng. Thiết kế độc đáo, chất lượng cao. Mua ngay với giá ưu đãi.',
            ],
            [
                'name' => 'Áo Đội Tuyển Việt Nam 2024',
                'description' => 'Áo đấu chính thức Đội Tuyển Việt Nam 2024. Màu đỏ truyền thống cùng logo rồng vàng. Chất liệu cao cấp, thoáng mát, phù hợp cho mọi hoạt động thể thao.',
                'price' => 650000,
                'original_price' => 850000,
                'sku' => 'VN-NT-2024',                'slug' => 'ao-doi-tuyen-viet-nam-2024',
                'category' => 'ao-doi-tuyen',
                'brand' => 'Grand Sport',
                'stock_quantity' => 100,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Đỏ', 'Trắng'],
                'images' => [
                    'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop'
                ],
                'is_featured' => true,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Đội Tuyển Việt Nam 2024 - Hang The Thao',
                'meta_description' => 'Áo đấu Đội Tuyển Việt Nam 2024 chính hãng. Thể hiện tinh thần yêu nước, ủng hộ đội tuyển. Giao hàng nhanh.',
            ],
            [
                'name' => 'Giày Đá Bóng Nike Mercurial Vapor 15',
                'description' => 'Giày đá bóng Nike Mercurial Vapor 15 Academy với công nghệ Nike Aerotrak Zones và đế ngoài được tối ưu hóa cho tốc độ trên sân cỏ tự nhiên.',
                'price' => 1299000,
                'original_price' => 1599000,
                'sku' => 'NIKE-MERCURIAL-V15',                'slug' => 'giay-da-bong-nike-mercurial-vapor-15',
                'category' => 'phu-kien',
                'brand' => 'Nike',
                'stock_quantity' => 25,
                'sizes' => ['39', '40', '41', '42', '43', '44'],
                'colors' => ['Đen', 'Trắng', 'Xanh'],
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop'
                ],
                'is_featured' => false,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Giày Đá Bóng Nike Mercurial Vapor 15 - Hang The Thao',
                'meta_description' => 'Giày đá bóng Nike Mercurial Vapor 15 chính hãng. Tốc độ và độ chính xác hoàn hảo. Giá tốt nhất thị trường.',
            ],
            [
                'name' => 'Bóng Đá FIFA Quality Pro',
                'description' => 'Bóng đá chuẩn FIFA Quality Pro, được sử dụng trong các giải đấu chuyên nghiệp. Chất liệu da PU cao cấp, độ bền cao, bay chuẩn.',
                'price' => 450000,
                'original_price' => 599000,
                'sku' => 'BALL-FIFA-PRO',                'slug' => 'bong-da-fifa-quality-pro',
                'category' => 'phu-kien',
                'brand' => 'Molten',
                'stock_quantity' => 40,
                'sizes' => ['Size 5'],
                'colors' => ['Trắng/Đen', 'Trắng/Xanh'],
                'images' => [
                    'https://images.unsplash.com/photo-1614632537190-23e4b8dc39f4?w=400&h=400&fit=crop'
                ],
                'is_featured' => false,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Bóng Đá FIFA Quality Pro - Hang The Thao',
                'meta_description' => 'Bóng đá chuẩn FIFA Quality Pro chính hãng. Chất lượng cao, bay chuẩn. Lựa chọn của các cầu thủ chuyên nghiệp.',
            ],
            [
                'name' => 'Áo Đội Tuyển Brazil 2024/25 Sân Nhà',
                'description' => 'Áo đấu chính thức Đội Tuyển Brazil 2024/25 sân nhà. Màu vàng truyền thống iconic với công nghệ Nike Dri-FIT giúp thoáng mát suốt trận đấu.',
                'price' => 750000,
                'original_price' => 950000,
                'sku' => 'BR-NT-HOME-2024',
                'slug' => 'ao-doi-tuyen-brazil-2024-25-san-nha',
                'category' => 'ao-doi-tuyen',
                'brand' => 'Nike',
                'stock_quantity' => 45,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Vàng', 'Xanh'],
                'images' => [
                    'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=400&fit=crop'
                ],
                'is_featured' => false,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Đội Tuyển Brazil 2024/25 Sân Nhà - Hang The Thao',
                'meta_description' => 'Áo đấu Đội Tuyển Brazil 2024/25 sân nhà chính hãng. Màu vàng Brazil truyền thống. Chất lượng cao, giá tốt.',
            ],
            [
                'name' => 'Áo Đội Tuyển Argentina 2024/25',
                'description' => 'Áo đấu Đội Tuyển Argentina 2024/25 với thiết kế sọc xanh trắng truyền thống. Áo của những nhà vô địch World Cup 2022.',
                'price' => 799000,
                'original_price' => 999000,
                'sku' => 'AR-NT-2024',
                'slug' => 'ao-doi-tuyen-argentina-2024-25',
                'category' => 'ao-doi-tuyen',
                'brand' => 'Adidas',
                'stock_quantity' => 60,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['Xanh trắng', 'Trắng'],
                'images' => [
                    'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop'
                ],
                'is_featured' => true,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Đội Tuyển Argentina 2024/25 - Hang The Thao',
                'meta_description' => 'Áo đấu Đội Tuyển Argentina 2024/25 chính hãng. Áo của nhà vô địch World Cup 2022. Thiết kế sọc xanh trắng iconic.',
            ],
            [
                'name' => 'Áo Đội Tuyển Pháp 2024/25 Sân Nhà',
                'description' => 'Áo đấu Đội Tuyển Pháp 2024/25 sân nhà với màu xanh Navy truyền thống. Thiết kế hiện đại của Nike cho Les Bleus.',
                'price' => 770000,
                'original_price' => 980000,
                'sku' => 'FR-NT-HOME-2024',
                'slug' => 'ao-doi-tuyen-phap-2024-25-san-nha',
                'category' => 'ao-doi-tuyen',
                'brand' => 'Nike',
                'stock_quantity' => 30,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Trắng'],
                'images' => [
                    'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=400&fit=crop'
                ],
                'is_featured' => false,
                'is_active' => true,
                'status' => 'active',
                'meta_title' => 'Áo Đội Tuyển Pháp 2024/25 Sân Nhà - Hang The Thao',
                'meta_description' => 'Áo đấu Đội Tuyển Pháp 2024/25 sân nhà chính hãng. Màu xanh Navy truyền thống Les Bleus. Chất lượng Nike cao cấp.',
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
