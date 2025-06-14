<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'sort_order',
        'is_active',
        'parent_id',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship với parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship với child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }    // Relationship với products (primary relationship)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Legacy relationship với products theo slug (để backward compatibility)
    public function productsBySlug()
    {
        return $this->hasMany(Product::class, 'category', 'slug');
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for parent categories
    public function scopeParentCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    // Method to generate slug
    public function generateSlug()
    {
        return \Str::slug($this->name);
    }

    // Method to get full category path
    public function getFullPathAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }
}
