<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Tạo categories nếu chưa có
        $categories = [
            ['name' => 'Áo CLB', 'slug' => 'ao-clb'],
            ['name' => 'Áo Đội Tuyển', 'slug' => 'ao-doi-tuyen'],
            ['name' => 'Phụ Kiện', 'slug' => 'phu-kien'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Tạo test products với variants
        $this->createProductsWithVariants();
    }

    private function createProductsWithVariants()
    {
        // Product 1: Áo Barcelona với nhiều size và màu
        $product1 = Product::firstOrCreate([
            'name' => 'Áo Barcelona 2024/25 - Có Variants'
        ], [
            'name' => 'Áo Barcelona 2024/25 - Có Variants',
            'description' => 'Áo đấu chính thức của Barcelona mùa giải 2024/25 với nhiều size và màu sắc.',
            'price' => 850000, // Giá base
            'original_price' => 950000,
            'sku' => 'BCN-2024-VAR',
            'category' => 'ao-clb',
            'category_id' => Category::where('slug', 'ao-clb')->first()->id,
            'brand' => 'Nike',
            'images' => [
                'https://via.placeholder.com/400x400/004D98/FFFFFF?text=Barcelona+Home',
                'https://via.placeholder.com/400x400/DC143C/FFFFFF?text=Barcelona+Away'
            ],
            'stock_quantity' => 0, // Sẽ tính từ variants
            'rating' => 4.8,
            'reviews_count' => 127,
            'is_featured' => true,
            'is_active' => true,
            'status' => 'active',
            'has_variants' => true
        ]);

        // Tạo variants cho product 1
        $variants1 = [
            ['size' => 'S', 'color' => 'Xanh Blaugrana', 'price' => 850000, 'sale_price' => 750000, 'stock' => 10],
            ['size' => 'M', 'color' => 'Xanh Blaugrana', 'price' => 850000, 'sale_price' => 750000, 'stock' => 15],
            ['size' => 'L', 'color' => 'Xanh Blaugrana', 'price' => 850000, 'sale_price' => 750000, 'stock' => 12],
            ['size' => 'XL', 'color' => 'Xanh Blaugrana', 'price' => 850000, 'sale_price' => 750000, 'stock' => 8],
            
            ['size' => 'S', 'color' => 'Đỏ Away', 'price' => 880000, 'sale_price' => 780000, 'stock' => 8],
            ['size' => 'M', 'color' => 'Đỏ Away', 'price' => 880000, 'sale_price' => 780000, 'stock' => 10],
            ['size' => 'L', 'color' => 'Đỏ Away', 'price' => 880000, 'sale_price' => 780000, 'stock' => 7],
            ['size' => 'XL', 'color' => 'Đỏ Away', 'price' => 880000, 'sale_price' => 780000, 'stock' => 5],
        ];

        foreach ($variants1 as $variantData) {
            ProductVariant::firstOrCreate([
                'product_id' => $product1->id,
                'size' => $variantData['size'],
                'color' => $variantData['color']
            ], [
                'product_id' => $product1->id,
                'size' => $variantData['size'],
                'color' => $variantData['color'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'],
                'stock_quantity' => $variantData['stock'],
                'sku' => 'BCN-' . $variantData['size'] . '-' . strtoupper(substr($variantData['color'], 0, 3)),
                'is_active' => true
            ]);
        }

        // Product 2: Áo Real Madrid với variants
        $product2 = Product::firstOrCreate([
            'name' => 'Áo Real Madrid 2024/25 - Có Variants'
        ], [
            'name' => 'Áo Real Madrid 2024/25 - Có Variants',
            'description' => 'Áo đấu chính thức của Real Madrid mùa giải 2024/25.',
            'price' => 900000, // Giá base
            'original_price' => 1000000,
            'sku' => 'RMA-2024-VAR',
            'category' => 'ao-clb',
            'category_id' => Category::where('slug', 'ao-clb')->first()->id,
            'brand' => 'Adidas',
            'images' => [
                'https://via.placeholder.com/400x400/FFFFFF/000000?text=Real+Madrid+Home',
                'https://via.placeholder.com/400x400/000000/FFFFFF?text=Real+Madrid+Away'
            ],
            'stock_quantity' => 0,
            'rating' => 4.9,
            'reviews_count' => 189,
            'is_featured' => true,
            'is_active' => true,
            'status' => 'active',
            'has_variants' => true
        ]);

        // Tạo variants cho product 2
        $variants2 = [
            ['size' => 'S', 'color' => 'Trắng Home', 'price' => 900000, 'sale_price' => null, 'stock' => 12],
            ['size' => 'M', 'color' => 'Trắng Home', 'price' => 900000, 'sale_price' => null, 'stock' => 18],
            ['size' => 'L', 'color' => 'Trắng Home', 'price' => 900000, 'sale_price' => null, 'stock' => 15],
            ['size' => 'XL', 'color' => 'Trắng Home', 'price' => 900000, 'sale_price' => null, 'stock' => 10],
            
            ['size' => 'S', 'color' => 'Đen Away', 'price' => 920000, 'sale_price' => 850000, 'stock' => 6],
            ['size' => 'M', 'color' => 'Đen Away', 'price' => 920000, 'sale_price' => 850000, 'stock' => 9],
            ['size' => 'L', 'color' => 'Đen Away', 'price' => 920000, 'sale_price' => 850000, 'stock' => 8],
            ['size' => 'XL', 'color' => 'Đen Away', 'price' => 920000, 'sale_price' => 850000, 'stock' => 4],
        ];

        foreach ($variants2 as $variantData) {
            ProductVariant::firstOrCreate([
                'product_id' => $product2->id,
                'size' => $variantData['size'],
                'color' => $variantData['color']
            ], [
                'product_id' => $product2->id,
                'size' => $variantData['size'],
                'color' => $variantData['color'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'],
                'stock_quantity' => $variantData['stock'],
                'sku' => 'RMA-' . $variantData['size'] . '-' . strtoupper(substr($variantData['color'], 0, 3)),
                'is_active' => true
            ]);
        }

        // Product 3: Sản phẩm đơn giản không có variants
        Product::firstOrCreate([
            'name' => 'Bóng đá Nike Premier League'
        ], [
            'name' => 'Bóng đá Nike Premier League',
            'description' => 'Bóng đá chính thức Premier League 2024/25.',
            'price' => 450000,
            'original_price' => 500000,
            'sku' => 'BALL-PL-2024',
            'category' => 'phu-kien',
            'category_id' => Category::where('slug', 'phu-kien')->first()->id,
            'brand' => 'Nike',
            'images' => [
                'https://via.placeholder.com/400x400/E60026/FFFFFF?text=Premier+League+Ball'
            ],
            'sizes' => ['Standard'],
            'colors' => ['Trắng/Đỏ'],
            'stock_quantity' => 25,
            'rating' => 4.6,
            'reviews_count' => 43,
            'is_featured' => false,
            'is_active' => true,
            'status' => 'active',
            'has_variants' => false
        ]);

        echo "✅ Đã tạo test products với variants!\n";
    }
}
