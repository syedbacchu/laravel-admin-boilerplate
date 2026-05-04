<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'image',
        'gallery',
        'video_img',
        'price',
        'brand_id',
        'attributes',
        'features',
        'short_description',
        'description',
        'usage_instructions',
        'category_id',
        'stock',
        'sold',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'video_link',
        'discount',
        'discount_type',
        'tax',
        'tax_type',
        'quantity_discounts',
        'is_featured',
        'status',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'gallery' => 'array',
        'attributes' => 'array',
        'features' => 'array',
        'quantity_discounts' => 'array',
        'price' => 'float',
        'discount' => 'float',
        'tax' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Brand
    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class, 'brand_id');
    // }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL BUT USEFUL)
    |--------------------------------------------------------------------------
    */

    // Final Price (after discount)
    public function getFinalPriceAttribute()
    {
        if (!$this->price) return 0;

        if ($this->discount) {
            if ($this->discount_type === 'percent') {
                return $this->price - ($this->price * $this->discount / 100);
            }

            return $this->price - $this->discount;
        }

        return $this->price;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}