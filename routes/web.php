<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Product detail route - using closure due to ProductController autoload issues
Route::get('/products/{id}', function($id) {
    $product = App\Models\Product::active()->findOrFail($id);
    $relatedProducts = App\Models\Product::active()
        ->where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->limit(6)
        ->get();
    return view('products.show', compact('product', 'relatedProducts'));
})->name('products.show');

// Category routes - sửa để gọi đúng method show
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.index');
Route::get('/search', [CategoryController::class, 'search'])->name('search');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product management
    Route::resource('products', AdminProductController::class);
    
    // Order management (thay thế category management)
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    
    // API routes for admin
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('products/search', [AdminProductController::class, 'search'])->name('products.search');
        Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
        Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
        
        // Order status update API
        Route::post('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});

// Test debug route
Route::get('/test-categories', function () {
    echo "<h1>Test Categories Debug</h1>";
    
    echo "<h2>Categories:</h2>";
    $categories = App\Models\Category::all();
    foreach ($categories as $cat) {
        echo "<p>ID: {$cat->id}, Name: {$cat->name}, Slug: {$cat->slug}</p>";
    }
    
    echo "<h2>Testing byCategory scope:</h2>";
    $slugs = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
    
    foreach ($slugs as $slug) {
        echo "<h3>Category: {$slug}</h3>";
        $products = App\Models\Product::byCategory($slug)->get();
        echo "<p>Found {$products->count()} products:</p>";
        foreach ($products as $product) {
            echo "<li>{$product->name} (category_id: {$product->category_id}, category: {$product->category})</li>";
        }
    }
    
    echo "<h2>Testing Home Controller logic:</h2>";
    $footballJerseys = App\Models\Product::active()->byCategory('ao-clb')->limit(6)->get();
    echo "<p>Football Jerseys (ao-clb): {$footballJerseys->count()} products</p>";
    
    $nationalTeamJerseys = App\Models\Product::active()->byCategory('ao-doi-tuyen')->limit(6)->get();
    echo "<p>National Team Jerseys (ao-doi-tuyen): {$nationalTeamJerseys->count()} products</p>";
    
    $accessories = App\Models\Product::active()->byCategory('phu-kien')->limit(6)->get();
    echo "<p>Accessories (phu-kien): {$accessories->count()} products</p>";
});

// Debug route để kiểm tra sản phẩm
Route::get('/debug-all-products', function () {
    $totalProducts = \App\Models\Product::count();
    $activeProducts = \App\Models\Product::active()->count();
    $allProducts = \App\Models\Product::orderBy('id', 'desc')->get();
    
    $html = "<h2>Debug Tất Cả Sản Phẩm</h2>";
    $html .= "<p><strong>Tổng số sản phẩm:</strong> {$totalProducts}</p>";
    $html .= "<p><strong>Sản phẩm active:</strong> {$activeProducts}</p>";
    $html .= "<p><strong>Sản phẩm không active:</strong> " . ($totalProducts - $activeProducts) . "</p>";
    
    $html .= "<h3>Chi tiết tất cả sản phẩm:</h3>";
    $html .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    $html .= "<tr><th>ID</th><th>Tên</th><th>is_active</th><th>status</th><th>category</th><th>created_at</th></tr>";
    
    foreach ($allProducts as $product) {
        $isActiveText = $product->is_active ? 'true' : 'false';
        $style = $product->is_active && $product->status === 'active' ? '' : 'background-color: #ffcccc;';
        $html .= "<tr style='{$style}'>";
        $html .= "<td>{$product->id}</td>";
        $html .= "<td>{$product->name}</td>";
        $html .= "<td>{$isActiveText}</td>";
        $html .= "<td>{$product->status}</td>";
        $html .= "<td>{$product->category}</td>";
        $html .= "<td>{$product->created_at}</td>";
        $html .= "</tr>";
    }
    
    $html .= "</table>";
    $html .= "<p><em>Sản phẩm có màu đỏ là không active</em></p>";
    
    return $html;
});

// Route để kích hoạt tất cả sản phẩm
Route::get('/activate-all-products', function () {
    $updated = \App\Models\Product::where('is_active', false)
                    ->orWhere('status', '!=', 'active')
                    ->update([
                        'is_active' => true,
                        'status' => 'active'
                    ]);
    
    return "Đã kích hoạt {$updated} sản phẩm. <a href='/debug-all-products'>Kiểm tra lại</a>";
});

// Route để đặt tất cả sản phẩm làm featured
Route::get('/make-all-featured', function () {
    $updated = \App\Models\Product::where('is_featured', false)
                    ->update(['is_featured' => true]);
    
    return "Đã đặt {$updated} sản phẩm làm featured. <a href='/'>Kiểm tra trang chủ</a>";
});

// Route debug cho HomeController
Route::get('/debug-home-data', function () {
    $homeController = new \App\Http\Controllers\HomeController();
    
    // Sử dụng reflection để gọi các method private
    $reflection = new ReflectionClass($homeController);
    
    $html = "<h2>Debug Home Controller Data</h2>";
    
    // Get featured products
    $featuredMethod = $reflection->getMethod('getFeaturedProducts');
    $featuredMethod->setAccessible(true);
    $featuredProducts = $featuredMethod->invoke($homeController);
    
    $html .= "<h3>Featured Products (" . count($featuredProducts) . "):</h3>";
    foreach ($featuredProducts as $product) {
        $html .= "<p>- {$product['name']} (ID: {$product['id']})</p>";
    }
    
    // Get football jerseys
    $footballMethod = $reflection->getMethod('getFootballJerseys');
    $footballMethod->setAccessible(true);
    $footballJerseys = $footballMethod->invoke($homeController);
    
    $html .= "<h3>Football Jerseys (" . count($footballJerseys) . "):</h3>";
    foreach ($footballJerseys as $product) {
        $html .= "<p>- {$product['name']} (ID: {$product['id']})</p>";
    }
    
    // Get national team jerseys
    $nationalMethod = $reflection->getMethod('getNationalTeamJerseys');
    $nationalMethod->setAccessible(true);
    $nationalJerseys = $nationalMethod->invoke($homeController);
    
    $html .= "<h3>National Team Jerseys (" . count($nationalJerseys) . "):</h3>";
    foreach ($nationalJerseys as $product) {
        $html .= "<p>- {$product['name']} (ID: {$product['id']})</p>";
    }
    
    // Get accessories
    $accessoriesMethod = $reflection->getMethod('getAccessories');
    $accessoriesMethod->setAccessible(true);
    $accessories = $accessoriesMethod->invoke($homeController);
    
    $html .= "<h3>Accessories (" . count($accessories) . "):</h3>";
    foreach ($accessories as $product) {
        $html .= "<p>- {$product['name']} (ID: {$product['id']})</p>";
    }
    
    return $html;
});

// Route kiểm tra từng scope riêng biệt
Route::get('/debug-scopes', function () {
    $html = "<h2>Debug Product Scopes</h2>";
    
    // Tất cả sản phẩm
    $allProducts = \App\Models\Product::count();
    $html .= "<p><strong>Tổng số sản phẩm trong DB:</strong> {$allProducts}</p>";
    
    // Sản phẩm active
    $activeProducts = \App\Models\Product::active()->count();
    $html .= "<p><strong>Sản phẩm active:</strong> {$activeProducts}</p>";
    
    // Sản phẩm featured
    $featuredProducts = \App\Models\Product::featured()->count();
    $html .= "<p><strong>Sản phẩm featured:</strong> {$featuredProducts}</p>";
    
    // Sản phẩm active + featured
    $activeFeatured = \App\Models\Product::active()->featured()->count();
    $html .= "<p><strong>Sản phẩm active + featured:</strong> {$activeFeatured}</p>";
    
    $html .= "<hr>";
    
    // Chi tiết theo category
    $categories = ['ao-clb', 'ao-doi-tuyen', 'phu-kien'];
    foreach ($categories as $cat) {
        $allInCat = \App\Models\Product::byCategory($cat)->count();
        $activeInCat = \App\Models\Product::active()->byCategory($cat)->count();
        $html .= "<p><strong>Category '{$cat}':</strong> {$allInCat} total, {$activeInCat} active</p>";
    }
    
    $html .= "<hr>";
    
    // Liệt kê 5 sản phẩm mới nhất
    $html .= "<h3>5 sản phẩm mới nhất (tất cả):</h3>";
    $latestAll = \App\Models\Product::orderBy('created_at', 'desc')->limit(5)->get();
    foreach ($latestAll as $p) {
        $activeStatus = ($p->is_active && $p->status === 'active') ? 'ACTIVE' : 'INACTIVE';
        $featuredStatus = $p->is_featured ? 'FEATURED' : 'NOT FEATURED';
        $html .= "<p>- {$p->name} ({$activeStatus}, {$featuredStatus}) - Category: {$p->category}</p>";
    }
    
    $html .= "<h3>5 sản phẩm mới nhất (chỉ active):</h3>";
    $latestActive = \App\Models\Product::active()->orderBy('created_at', 'desc')->limit(5)->get();
    foreach ($latestActive as $p) {
        $featuredStatus = $p->is_featured ? 'FEATURED' : 'NOT FEATURED';
        $html .= "<p>- {$p->name} ({$featuredStatus}) - Category: {$p->category}</p>";
    }
    
    return $html;
});

// Route để xem dữ liệu thực tế được truyền tới view
Route::get('/debug-view-data', function () {
    $homeController = new \App\Http\Controllers\HomeController();
    $request = request();
    
    // Capture view data
    ob_start();
    $response = $homeController->index($request);
    $content = ob_get_clean();
    
    // Get the view data
    $viewData = $response->getData();
    
    $html = "<h2>Debug View Data for Home Page</h2>";
    
    $html .= "<h3>Featured Products Data:</h3>";
    $html .= "<pre>" . json_encode($viewData['featuredProducts'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
    $html .= "<h3>Football Jerseys Data:</h3>";
    $html .= "<pre>" . json_encode($viewData['footballJerseys'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
    $html .= "<h3>National Team Jerseys Data:</h3>";
    $html .= "<pre>" . json_encode($viewData['nationalTeamJerseys'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
    $html .= "<h3>Accessories Data:</h3>";
    $html .= "<pre>" . json_encode($viewData['accessories'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
    return $html;
});

// Route để đồng bộ category_id cho tất cả sản phẩm
Route::get('/sync-category-ids', function () {
    $html = "<h2>Sync Category IDs</h2>";
    
    $categories = \App\Models\Category::all();
    $updated = 0;
    
    foreach ($categories as $category) {
        $count = \App\Models\Product::where('category', $category->slug)
            ->where(function($query) use ($category) {
                $query->whereNull('category_id')
                      ->orWhere('category_id', '!=', $category->id);
            })
            ->update(['category_id' => $category->id]);
        
        $updated += $count;
        $html .= "<p>Category '{$category->name}' (slug: {$category->slug}): Cập nhật {$count} sản phẩm</p>";
    }
    
    $html .= "<hr><p><strong>Tổng cộng cập nhật: {$updated} sản phẩm</strong></p>";
    $html .= "<p><a href='/debug-scopes'>Kiểm tra lại scopes</a> | <a href='/'>Về trang chủ</a></p>";
    
    return $html;
});

// Route để xem 10 sản phẩm mới nhất với thông tin chi tiết
Route::get('/debug-latest-products', function () {
    $html = "<h2>10 Sản Phẩm Mới Nhất</h2>";
    
    $products = \App\Models\Product::orderBy('created_at', 'desc')->limit(10)->get();
    
    $html .= "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    $html .= "<tr style='background-color: #f0f0f0;'>";
    $html .= "<th>ID</th><th>Tên</th><th>Category</th><th>Category ID</th>";
    $html .= "<th>is_active</th><th>status</th><th>is_featured</th><th>Created At</th>";
    $html .= "</tr>";
    
    foreach ($products as $product) {
        $isActive = $product->is_active ? 'true' : 'false';
        $isFeatured = $product->is_featured ? 'true' : 'false';
        $status = $product->status;
        
        // Highlight inactive products
        $style = ($product->is_active && $product->status === 'active') ? '' : 'background-color: #ffcccc;';
        
        $html .= "<tr style='{$style}'>";
        $html .= "<td>{$product->id}</td>";
        $html .= "<td>{$product->name}</td>";
        $html .= "<td>{$product->category}</td>";
        $html .= "<td>{$product->category_id}</td>";
        $html .= "<td>{$isActive}</td>";
        $html .= "<td>{$status}</td>";
        $html .= "<td>{$isFeatured}</td>";
        $html .= "<td>{$product->created_at}</td>";
        $html .= "</tr>";
    }
    
    $html .= "</table>";
    $html .= "<p><em>Sản phẩm có màu đỏ = không active</em></p>";
    
    return $html;
});

// Route để sửa category cũ sang mới
Route::get('/fix-old-categories', function () {
    $html = "<h2>Fix Old Category Values</h2>";
    
    // Mapping từ category cũ sang mới
    $categoryMapping = [
        'football' => 'ao-clb',
        'national-team' => 'ao-doi-tuyen', 
        'accessories' => 'phu-kien'
    ];
    
    $totalUpdated = 0;
    
    foreach ($categoryMapping as $oldCategory => $newCategory) {
        $count = \App\Models\Product::where('category', $oldCategory)->count();
        
        if ($count > 0) {
            // Lấy category_id từ database
            $category = \App\Models\Category::where('slug', $newCategory)->first();
            
            if ($category) {
                \App\Models\Product::where('category', $oldCategory)
                    ->update([
                        'category' => $newCategory,
                        'category_id' => $category->id
                    ]);
                
                $html .= "<p>✅ Cập nhật {$count} sản phẩm từ '{$oldCategory}' sang '{$newCategory}' (ID: {$category->id})</p>";
                $totalUpdated += $count;
            } else {
                $html .= "<p>❌ Không tìm thấy category với slug '{$newCategory}'</p>";
            }
        } else {
            $html .= "<p>ℹ️ Không có sản phẩm nào với category '{$oldCategory}'</p>";
        }
    }
    
    $html .= "<hr><p><strong>Tổng cộng cập nhật: {$totalUpdated} sản phẩm</strong></p>";
    $html .= "<p><a href='/debug-scopes'>Kiểm tra lại</a> | <a href='/'>Về trang chủ</a></p>";
    
    return $html;
});
