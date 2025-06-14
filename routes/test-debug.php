<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;

Route::get('/test-categories', function () {
    echo "<h1>Test Categories Debug</h1>";
    
    echo "<h2>Categories:</h2>";
    $categories = Category::all();
    foreach ($categories as $cat) {
        echo "<p>ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}</p>";
    }
    
    echo "<h2>Testing byCategory scope:</h2>";
    $slugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
    
    foreach ($slugs as $slug) {
        echo "<h3>Category: {$slug}</h3>";
        $products = Product::byCategory($slug)->get();
        echo "<p>Found {$products->count()} products:</p>";
        foreach ($products as $product) {
            echo "<li>{$product->name} (category_id: {$product->category_id}, category: {$product->category})</li>";
        }
    }
    
    echo "<h2>Testing Home Controller logic:</h2>";
    $footballJerseys = Product::active()->byCategory('ao-clb')->limit(6)->get();
    echo "<p>Football Jerseys (ao-clb): {$footballJerseys->count()} products</p>";
    
    $nationalTeamJerseys = Product::active()->byCategory('ao-doi-tuyen')->limit(6)->get();
    echo "<p>National Team Jerseys (ao-doi-tuyen): {$nationalTeamJerseys->count()} products</p>";
    
    $accessories = Product::active()->byCategory('phu-kien')->limit(6)->get();
    echo "<p>Accessories (phu-kien): {$accessories->count()} products</p>";
});
