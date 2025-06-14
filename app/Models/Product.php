<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'sku',
        'category_id',  // Sử dụng category_id thay vì category
        'category',     // Giữ category slug để backward compatibility
        'brand',
        'images',
        'sizes',
        'colors',
        'stock_quantity',
        'rating',
        'reviews_count',
        'is_featured',
        'is_active',
        'status',
        'specifications',
        'meta_title',
        'meta_description',
        'slug'
    ];

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'specifications' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Accessor for main image
    public function getMainImageAttribute()
    {
        return $this->images[0] ?? 'https://via.placeholder.com/400x400/cccccc/ffffff?text=No+Image';
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . '₫';
    }    // Accessor for formatted original price
    public function getFormattedOriginalPriceAttribute()
    {
        return $this->original_price ? number_format($this->original_price, 0, ',', '.') . '₫' : null;
    }

    // Accessor for discount percentage
    public function getDiscountPercentAttribute()
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    // Check if product has discount
    public function getHasDiscountAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }    // Relationship with category (sử dụng category_id)
    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Legacy relationship with category slug (để backward compatibility)
    public function categoryBySlug()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    // Scope for category by ID
    public function scopeByCategory($query, $categorySlugOrId)
    {
        // Nếu là string, tìm theo slug
        if (is_string($categorySlugOrId)) {
            $category = Category::where('slug', $categorySlugOrId)->first();
            if ($category) {
                return $query->where('category_id', $category->id);
            }
            // Fallback: tìm theo category field (slug)
            return $query->where('category', $categorySlugOrId);
        }
        
        // Nếu là number, tìm theo category_id
        return $query->where('category_id', $categorySlugOrId);
    }

    // Method to generate slug
    public function generateSlug()
    {
        return \Str::slug($this->name);
    }
}
