<?php

use Illuminate\Support\Facades\DB;

// Simple database queries to debug
echo "=== DEBUG DATABASE ===" . PHP_EOL;

// Check categories
echo "Categories count: " . DB::table('categories')->count() . PHP_EOL;
echo "Categories data:" . PHP_EOL;
$categories = DB::table('categories')->get();
foreach ($categories as $cat) {
    echo "  ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}" . PHP_EOL;
}

echo PHP_EOL;

// Check products
echo "Products count: " . DB::table('products')->count() . PHP_EOL;
echo "Products with category info:" . PHP_EOL;
$products = DB::table('products')->limit(10)->get();
foreach ($products as $prod) {
    echo "  {$prod->name} - category_id: {$prod->category_id}, category: {$prod->category}" . PHP_EOL;
}

echo PHP_EOL;

// Test category matching
echo "Testing category matching:" . PHP_EOL;
$slugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
foreach ($slugs as $slug) {
    $count = DB::table('products')->where('category', $slug)->count();
    $count2 = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->where('categories.slug', $slug)
        ->count();
    echo "  Slug '{$slug}': {$count} products (by category field), {$count2} products (by join)" . PHP_EOL;
}
