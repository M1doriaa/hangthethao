<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SyncProductCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product category_id based on category slug';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Syncing product categories...');
        
        // Get all categories
        $categories = Category::all()->keyBy('slug');
        $this->info("Found {$categories->count()} categories");
        
        // Get all products
        $products = Product::all();
        $this->info("Found {$products->count()} products");
        
        $updated = 0;
        $errors = 0;
        
        foreach ($products as $product) {
            $categorySlug = $product->category;
            
            if (!$categorySlug) {
                $this->warn("Product '{$product->name}' has no category slug");
                $errors++;
                continue;
            }
            
            if (isset($categories[$categorySlug])) {
                $category = $categories[$categorySlug];
                $product->category_id = $category->id;
                $product->save();
                
                $this->line("✅ Updated '{$product->name}' -> Category: {$category->name} (ID: {$category->id})");
                $updated++;
            } else {
                $this->warn("⚠️  Product '{$product->name}' has unknown category slug: '{$categorySlug}'");
                $errors++;
            }
        }
        
        $this->newLine();
        $this->info("✅ Sync completed!");
        $this->info("Updated: {$updated} products");
        $this->info("Errors: {$errors} products");
        
        // Handle 'accessories' category issue
        if ($errors > 0) {
            $this->newLine();
            $this->info("🔧 Checking for 'accessories' category issue...");
            
            $accessoriesProducts = Product::where('category', 'accessories')->get();
            if ($accessoriesProducts->count() > 0) {
                $phuKienCategory = Category::where('slug', 'phu-kien')->first();
                if ($phuKienCategory) {
                    foreach ($accessoriesProducts as $product) {
                        $product->category_id = $phuKienCategory->id;
                        $product->category = 'phu-kien'; // Update slug to match
                        $product->save();
                        $this->line("🔧 Fixed '{$product->name}' -> Updated to 'phu-kien' category");
                    }
                    $this->info("✅ Fixed 'accessories' category products");
                }
            }
        }
        
        return 0;
    }
}
