<?php
// Script debug và sync categories cho Hang The Thao

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

use App\Models\Category;
use App\Models\Product;

echo "🏆 Debug Categories và Products cho Hang The Thao\n";
echo "=================================================\n\n";

// 1. Kiểm tra categories hiện tại
echo "📋 Categories hiện tại:\n";
$categories = Category::all();
foreach ($categories as $category) {
    echo "ID: {$category->id}, Name: '{$category->name}', Slug: '{$category->slug}'\n";
}

echo "\n";

// 2. Kiểm tra products và category relationships
echo "📦 Products và category relationships:\n";
$products = Product::all();
foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  - category_id: " . ($product->category_id ?? 'NULL') . "\n";
    echo "  - category (slug): " . ($product->category ?? 'NULL') . "\n";
    
    // Kiểm tra relationship
    if ($product->category_id) {
        $cat = Category::find($product->category_id);
        echo "  - Category by ID: " . ($cat ? $cat->name : 'NOT FOUND') . "\n";
    }
    
    if ($product->category) {
        $cat = Category::where('slug', $product->category)->first();
        echo "  - Category by slug: " . ($cat ? $cat->name : 'NOT FOUND') . "\n";
    }
    echo "\n";
}

// 3. Sync category_id dựa trên category slug
echo "🔄 Syncing category_id based on category slug...\n";
$synced = 0;
foreach ($products as $product) {
    if (!$product->category_id && $product->category) {
        $category = Category::where('slug', $product->category)->first();
        if ($category) {
            $product->update(['category_id' => $category->id]);
            echo "✅ Synced product '{$product->name}' to category '{$category->name}'\n";
            $synced++;
        } else {
            echo "❌ Category not found for slug: {$product->category}\n";
        }
    }
}

echo "\n✅ Synced {$synced} products.\n\n";

// 4. Hiển thị products theo category sau khi sync
echo "📊 Products theo category sau sync:\n";
foreach ($categories as $category) {
    $productCount = $category->products()->count();
    $legacyProductCount = $category->productsBySlug()->count();
    
    echo "Category: {$category->name} ({$category->slug})\n";
    echo "  - Products (by category_id): {$productCount}\n";
    echo "  - Products (by slug): {$legacyProductCount}\n";
    
    if ($productCount > 0) {
        $products = $category->products()->get();
        foreach ($products as $product) {
            echo "    * {$product->name} (₫" . number_format($product->price) . ")\n";
        }
    }
    echo "\n";
}

echo "🎉 Debug và sync hoàn tất!\n";
