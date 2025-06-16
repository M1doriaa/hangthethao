<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Debug logging
        \Log::info('Product filter query:', [
            'search' => $request->search,
            'category' => $request->category,
            'status' => $request->status,
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $products = $query->latest()->paginate(20);
        $categories = Category::active()->orderBy('name')->pluck('name', 'slug');

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }    /**
     * Store a newly created resource in storage.
     */    public function store(Request $request)
    {
        // Log request data for debugging
        \Log::info('Product store request:', [
            'has_variants' => $request->has_variants,
            'variants_count' => $request->has('variants') ? count($request->variants ?? []) : 0,
            'request_all' => $request->all()
        ]);

        // Validate base product fields first
        $baseRules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'required|string',
            'brand' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|string|in:active,inactive,out_of_stock',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            // Image validation
            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            // Variant support
            'has_variants' => 'nullable|boolean',
        ];

        // Add conditional validation based on product type
        if ($request->has_variants) {
            // For variant products
            $baseRules['variants'] = 'required|array|min:1';
            $baseRules['variants.*.size'] = 'nullable|string|max:50';
            $baseRules['variants.*.color'] = 'nullable|string|max:50';
            $baseRules['variants.*.color_code'] = 'nullable|string|max:7';
            $baseRules['variants.*.price'] = 'required|numeric|min:0';
            $baseRules['variants.*.sale_price'] = 'nullable|numeric|min:0';
            $baseRules['variants.*.stock_quantity'] = 'required|integer|min:0';
            $baseRules['variants.*.sku'] = 'nullable|string|max:100';
            $baseRules['variants.*.is_active'] = 'nullable|boolean';
        } else {
            // For simple products
            $baseRules['stock_quantity'] = 'required|integer|min:0';
        }

        try {
            $validated = $request->validate($baseRules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle image uploads
        $images = [];
        
        // Upload main image
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . Str::random(10) . '.' . $mainImage->getClientOriginalExtension();
            $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
            $images[] = '/storage/' . $mainImagePath;
        }
        
        // Upload additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $image) {
                $imageName = time() . '_additional_' . ($index + 1) . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $images[] = '/storage/' . $imagePath;
            }
        }        // Set images array
        $validated['images'] = $images;
        
        // Lấy category_id từ category slug
        $category = Category::where('slug', $validated['category'])->first();
        if ($category) {
            $validated['category_id'] = $category->id;
        }
          // Tạo sản phẩm
        try {
            $product = Product::create($validated);
            \Log::info('Product created successfully:', ['id' => $product->id, 'name' => $product->name]);
        } catch (\Exception $e) {
            \Log::error('Failed to create product:', [
                'error' => $e->getMessage(),
                'validated_data' => $validated
            ]);
            throw $e;
        }

        // Nếu sản phẩm có variants, tạo các variants
        if ($request->has_variants && $request->filled('variants')) {
            \Log::info('Creating variants for product:', ['product_id' => $product->id, 'variants_count' => count($request->variants)]);
            
            foreach ($request->variants as $index => $variantData) {
                try {
                    $variantData['product_id'] = $product->id;
                    $variantData['is_active'] = $variantData['is_active'] ?? true;
                    
                    // Tạo SKU cho variant nếu không có
                    if (empty($variantData['sku'])) {
                        $sizePart = !empty($variantData['size']) ? strtoupper($variantData['size']) : '';
                        $colorPart = !empty($variantData['color']) ? strtoupper(str_replace(' ', '', $variantData['color'])) : '';
                        $variantData['sku'] = $product->sku . ($sizePart ? '-' . $sizePart : '') . ($colorPart ? '-' . $colorPart : '');
                    }
                    
                    $variant = \App\Models\ProductVariant::create($variantData);
                    \Log::info("Variant {$index} created:", ['id' => $variant->id, 'sku' => $variant->sku]);
                } catch (\Exception $e) {
                    \Log::error("Failed to create variant {$index}:", [
                        'error' => $e->getMessage(),
                        'variant_data' => $variantData
                    ]);
                    throw $e;
                }
            }
        }        $variantCount = $request->has_variants && $request->filled('variants') ? count($request->variants) : 0;
        $imageCount = count($images);
        
        $successMessage = "Sản phẩm \"{$validated['name']}\" đã được tạo thành công với {$imageCount} ảnh!";
        if ($variantCount > 0) {
            $successMessage .= " Đã tạo {$variantCount} biến thể.";
        }

        return redirect()->route('admin.products.index')->with('success', $successMessage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sku' => ['required', 'string', Rule::unique('products', 'sku')->ignore($product->id)],
            'category' => 'required|string',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer|min:0',
            'sizes' => 'nullable|array',
            'colors' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|string|in:active,inactive,out_of_stock',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            // Image validation
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'keep_images' => 'nullable|string', // Indices of images to keep
            // Variant support
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.size' => 'required_if:has_variants,true|string|max:50',
            'variants.*.color' => 'required_if:has_variants,true|string|max:50',
            'variants.*.color_code' => 'nullable|string|max:7',
            'variants.*.price' => 'required_if:has_variants,true|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required_if:has_variants,true|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle image updates
        $currentImages = $product->images ?? [];
        $newImages = [];
        
        // Keep selected existing images
        if ($request->filled('keep_images')) {
            $keepIndices = explode(',', $request->keep_images);
            foreach ($keepIndices as $index) {
                if (isset($currentImages[$index])) {
                    $newImages[] = $currentImages[$index];
                }
            }
        }
        
        // Upload new main image
        if ($request->hasFile('main_image')) {
            $mainImage = $request->file('main_image');
            $mainImageName = time() . '_main_' . Str::random(10) . '.' . $mainImage->getClientOriginalExtension();
            $mainImagePath = $mainImage->storeAs('products', $mainImageName, 'public');
            // Insert at beginning (main image first)
            array_unshift($newImages, '/storage/' . $mainImagePath);
        }
        
        // Upload additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $image) {
                $imageName = time() . '_additional_' . ($index + 1) . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $newImages[] = '/storage/' . $imagePath;
            }
        }
        
        // If we have new images, update the images array
        if (!empty($newImages)) {
            $validated['images'] = $newImages;
        }        // Clean up keep_images from validated data as it's not a model field
        unset($validated['keep_images']);
        
        // Lấy category_id từ category slug
        $category = Category::where('slug', $validated['category'])->first();
        if ($category) {
            $validated['category_id'] = $category->id;
        }
        
        $product->update($validated);

        // Xử lý variants
        if ($request->has_variants && $request->filled('variants')) {
            // Lấy danh sách variant IDs hiện tại
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $submittedVariantIds = array_filter(array_column($request->variants, 'id'));
            
            // Xóa các variants không còn trong danh sách submit
            $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
            if (!empty($variantsToDelete)) {
                \App\Models\ProductVariant::whereIn('id', $variantsToDelete)->delete();
            }
            
            // Cập nhật hoặc tạo mới variants
            foreach ($request->variants as $variantData) {
                $variantData['product_id'] = $product->id;
                $variantData['is_active'] = $variantData['is_active'] ?? true;
                
                // Tạo SKU cho variant nếu không có
                if (empty($variantData['sku'])) {
                    $variantData['sku'] = $product->sku . '-' . strtoupper($variantData['size']) . '-' . strtoupper(str_replace(' ', '', $variantData['color']));
                }
                
                if (!empty($variantData['id'])) {
                    // Cập nhật variant hiện có
                    $variant = \App\Models\ProductVariant::find($variantData['id']);
                    if ($variant) {
                        $variant->update($variantData);
                    }
                } else {
                    // Tạo variant mới
                    \App\Models\ProductVariant::create($variantData);
                }
            }
        } else {
            // Nếu không có variants, xóa tất cả variants hiện có
            $product->variants()->delete();
        }

        $imageCount = !empty($newImages) ? count($newImages) : count($product->images ?? []);
        return redirect()->route('admin.products.index')
                        ->with('success', 'Sản phẩm "' . $validated['name'] . '" đã được cập nhật thành công với ' . $imageCount . ' ảnh!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}
