<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DebugCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug categories and products relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏆 Debug Categories và Products cho Hang The Thao');
        $this->info('=================================================');
        
        // 1. Check categories
        $this->info('📋 Categories hiện tại:');
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->line("ID: {$category->id}, Name: '{$category->name}', Slug: '{$category->slug}'");
        }
        $this->newLine();
        
        // 2. Check products
        $this->info('📦 Products và category relationships:');
        $products = Product::limit(10)->get();
        foreach ($products as $product) {
            $this->line("Product: {$product->name}");
            $this->line("  - category_id: " . ($product->category_id ?? 'NULL'));
            $this->line("  - category (slug): " . ($product->category ?? 'NULL'));
              // Test relationship
            try {
                $categoryById = $product->categoryModel;
                $this->line("  - Category relationship: " . ($categoryById ? $categoryById->name : 'NULL'));
            } catch (\Exception $e) {
                $this->error("  - Category relationship ERROR: " . $e->getMessage());
            }
            $this->newLine();
        }
        
        // 3. Test scope byCategory
        $this->info('🔍 Testing scope byCategory:');
        $testSlugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
        
        foreach ($testSlugs as $slug) {
            try {
                $products = Product::byCategory($slug)->get();
                $this->line("Category '{$slug}': {$products->count()} products");
                foreach ($products as $product) {
                    $this->line("  - {$product->name}");
                }
            } catch (\Exception $e) {
                $this->error("Error for '{$slug}': " . $e->getMessage());
            }
            $this->newLine();
        }
        
        // 4. Raw database check
        $this->info('💾 Raw database queries:');
        $slugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
        foreach ($slugs as $slug) {
            $count1 = DB::table('products')->where('category', $slug)->count();
            $count2 = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('categories.slug', $slug)
                ->count();
            $this->line("Slug '{$slug}': {$count1} products (by category field), {$count2} products (by join)");
        }
        
        return 0;
    }
}
