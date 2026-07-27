<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'cover_image',
        'meta_title',
        'meta_description',
        'sort_order',
        'status',
        'created_by',
        'updated_by',
        'is_featured',
        'site_type',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Parent Category
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Child Categories
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    // Products in this category (many-to-many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_mappings', 'category_id', 'product_id')
            ->withTimestamps();
    }
}
