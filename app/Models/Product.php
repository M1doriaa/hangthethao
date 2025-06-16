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
        'slug',
        'has_variants',
        'variant_options'
    ];

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
        'specifications' => 'array',
        'variant_options' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'has_variants' => 'boolean'
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

    // Relationship with product variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Get active variants
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    // Get available sizes from variants
    public function getAvailableSizes()
    {
        if (!$this->has_variants) {
            return $this->sizes ?? [];
        }
        
        return $this->activeVariants()
            ->whereNotNull('size')
            ->pluck('size')
            ->unique()
            ->values()
            ->toArray();
    }

    // Get available colors from variants
    public function getAvailableColors()
    {
        if (!$this->has_variants) {
            return $this->colors ?? [];
        }
        
        return $this->activeVariants()
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values()
            ->toArray();
    }

    // Get price range for product with variants
    public function getPriceRange()
    {
        if (!$this->has_variants) {
            return [
                'min' => $this->price,
                'max' => $this->price
            ];
        }

        $variants = $this->activeVariants()->get();
        if ($variants->isEmpty()) {
            return [
                'min' => $this->price,
                'max' => $this->price
            ];
        }

        $prices = $variants->map(function ($variant) {
            return $variant->getCurrentPrice();
        });

        return [
            'min' => $prices->min(),
            'max' => $prices->max()
        ];
    }

    // Get variant by size and color
    public function getVariant($size = null, $color = null)
    {
        if (!$this->has_variants) {
            return null;
        }

        $query = $this->activeVariants();
        
        if ($size) {
            $query->where('size', $size);
        }
        
        if ($color) {
            $query->where('color', $color);
        }

        return $query->first();
    }

    // Get cheapest variant
    public function getCheapestVariant()
    {
        if (!$this->has_variants) {
            return null;
        }

        return $this->activeVariants()
            ->orderBy('price', 'asc')
            ->first();
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

    /**
     * Get the reviews for the product
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews for the product
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get average rating from approved reviews
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count (approved only)
     */
    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }
}
