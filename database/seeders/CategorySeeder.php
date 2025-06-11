<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả categories cũ trước
        Category::truncate();
        
        $categories = [
            [
                'name' => 'Áo CLB',
                'slug' => 'ao-clb',
                'description' => 'Áo thi đấu các câu lạc bộ bóng đá nổi tiếng',
                'icon' => 'fas fa-shirt',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Áo Đội Tuyển',
                'slug' => 'ao-doi-tuyen',
                'description' => 'Áo thi đấu các đội tuyển quốc gia',
                'icon' => 'fas fa-flag',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Phụ Kiện',
                'slug' => 'phu-kien',
                'description' => 'Phụ kiện thể thao: bóng, giày, găng tay,...',
                'icon' => 'fas fa-shopping-bag',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
