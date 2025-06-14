<?php

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;

echo "=== KIỂM TRA TẤT CẢ SẢN PHẨM ===" . PHP_EOL;

// Kiểm tra tổng số sản phẩm
$totalProducts = DB::table('products')->count();
echo "Tổng số sản phẩm trong database: " . $totalProducts . PHP_EOL;

// Kiểm tra sản phẩm theo trạng thái
$activeProducts = DB::table('products')->where('is_active', 1)->where('status', 'active')->count();
echo "Sản phẩm active: " . $activeProducts . PHP_EOL;

$inactiveProducts = DB::table('products')->where('is_active', 0)->orWhere('status', '!=', 'active')->count();
echo "Sản phẩm không active: " . $inactiveProducts . PHP_EOL;

echo PHP_EOL . "=== CHI TIẾT TẤT CẢ SẢN PHẨM ===" . PHP_EOL;

// Lấy tất cả sản phẩm với thông tin chi tiết
$allProducts = DB::table('products')->orderBy('id', 'desc')->get();
foreach ($allProducts as $i => $product) {
    echo ($i + 1) . ". {$product->name}" . PHP_EOL;
    echo "   - ID: {$product->id}" . PHP_EOL;
    echo "   - is_active: " . ($product->is_active ? 'true' : 'false') . PHP_EOL;
    echo "   - status: {$product->status}" . PHP_EOL;
    echo "   - category: {$product->category}" . PHP_EOL;
    echo "   - category_id: {$product->category_id}" . PHP_EOL;
    echo "   - created_at: {$product->created_at}" . PHP_EOL;
    echo PHP_EOL;
}
