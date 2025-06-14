<?php
// Debug script cho categories và products của Hang The Thao

require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Category;
use App\Models\Product;

echo "🏆 Debug Categories và Products cho Hang The Thao" . PHP_EOL;
echo "=================================================" . PHP_EOL . PHP_EOL;

// 1. Kiểm tra categories
echo "📋 Categories hiện tại:" . PHP_EOL;
$categories = Category::all();
foreach ($categories as $category) {
    echo "ID: {$category->id}, Name: '{$category->name}', Slug: '{$category->slug}'" . PHP_EOL;
}

echo PHP_EOL;

// 2. Kiểm tra products và relationships
echo "📦 Products và category relationships:" . PHP_EOL;
$products = Product::all();
foreach ($products as $product) {
    echo "Product: {$product->name}" . PHP_EOL;
    echo "  - category_id: " . ($product->category_id ?? 'NULL') . PHP_EOL;
    echo "  - category (slug): " . ($product->category ?? 'NULL') . PHP_EOL;
    
    // Test relationship mới
    try {
        $categoryById = $product->category;
        echo "  - Category relationship: " . ($categoryById ? $categoryById->name : 'NULL') . PHP_EOL;
    } catch (Exception $e) {
        echo "  - Category relationship ERROR: " . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
}

// 3. Test scope byCategory
echo "🔍 Testing scope byCategory:" . PHP_EOL;
$testSlugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];

foreach ($testSlugs as $slug) {
    try {
        $products = Product::byCategory($slug)->get();
        echo "Category '{$slug}': {$products->count()} products" . PHP_EOL;
        foreach ($products as $product) {
            echo "  - {$product->name}" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "ERROR with category '{$slug}': " . $e->getMessage() . PHP_EOL;
    }
    echo PHP_EOL;
}

// 4. Sync category_id nếu cần
echo "🔄 Syncing category_id..." . PHP_EOL;
$synced = 0;
foreach (Product::whereNull('category_id')->get() as $product) {
    if ($product->category) {
        $category = Category::where('slug', $product->category)->first();
        if ($category) {
            $product->update(['category_id' => $category->id]);
            echo "✅ Synced: {$product->name} -> {$category->name}" . PHP_EOL;
            $synced++;
        }
    }
}

if ($synced > 0) {
    echo "✅ Synced {$synced} products" . PHP_EOL;
} else {
    echo "ℹ️ No products need syncing" . PHP_EOL;
}

echo PHP_EOL . "🎉 Debug completed!" . PHP_EOL;
