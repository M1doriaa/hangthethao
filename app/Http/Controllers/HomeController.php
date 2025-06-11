<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{    /**
     * Hiển thị trang chủ
     */    public function index()
    {
        // Lấy sản phẩm nổi bật từ database
        $featuredProducts = $this->getFeaturedProducts();
        
        // Lấy áo bóng đá từ database 
        $footballJerseys = $this->getFootballJerseys();
        
        // Lấy áo đội tuyển từ database
        $nationalTeamJerseys = $this->getNationalTeamJerseys();
        
        // Lấy phụ kiện từ database
        $accessories = $this->getAccessories();
        
        // Lấy danh mục từ database
        $categories = $this->getCategories();
        
        return view('home', compact('featuredProducts', 'footballJerseys', 'nationalTeamJerseys', 'accessories', 'categories'));
    }/**
     * Lấy danh sách sản phẩm nổi bật từ database
     */
    private function getFeaturedProducts()
    {
        return Product::active()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'rating' => $product->rating,
                    'image' => $product->main_image,
                    'stock_quantity' => $product->stock_quantity,
                    'formatted_price' => $product->formatted_price
                ];
            });
    }    /**
     * Lấy danh sách danh mục từ database (chỉ hiển thị 3 danh mục cần thiết)
     */
    private function getCategories()
    {        // Lấy tất cả 3 danh mục từ database
        $targetSlugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
        
        $categories = Category::active()
            ->whereIn('slug', $targetSlugs)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'slug' => $category->slug
                ];
            });

        return $categories;
    }/**
     * Lấy danh sách áo bóng đá từ database
     */
    private function getFootballJerseys()
    {        return Product::active()
            ->byCategory('ao-clb')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'rating' => $product->rating,
                    'image' => $product->main_image,
                    'stock_quantity' => $product->stock_quantity,
                    'formatted_price' => $product->formatted_price
                ];
            });
    }/**
     * Lấy danh sách áo đội tuyển quốc gia từ database
     */
    private function getNationalTeamJerseys()
    {
        return Product::active()
            ->byCategory('ao-doi-tuyen')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'rating' => $product->rating,
                    'image' => $product->main_image,
                    'stock_quantity' => $product->stock_quantity,
                    'formatted_price' => $product->formatted_price
                ];
            });
    }

    /**
     * Lấy danh sách phụ kiện từ database
     */
    private function getAccessories()
    {
        return Product::active()
            ->byCategory('phu-kien')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'rating' => $product->rating,
                    'image' => $product->main_image,
                    'stock_quantity' => $product->stock_quantity,
                    'formatted_price' => $product->formatted_price
                ];
            });
    }
}
