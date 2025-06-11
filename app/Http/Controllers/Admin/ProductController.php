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
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
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
            'main_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

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
        }
        
        // Set images array
        $validated['images'] = $images;        Product::create($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Sản phẩm "' . $validated['name'] . '" đã được tạo thành công với ' . count($images) . ' ảnh!');
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
        }

        // Clean up keep_images from validated data as it's not a model field
        unset($validated['keep_images']);        $product->update($validated);

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
