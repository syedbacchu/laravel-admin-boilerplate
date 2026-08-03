<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_value_id',
        'attributes', // JSON: [1, 5, 12] - multiple attribute_value IDs
        'name',
        'sku',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'float',
    ];

    // PRODUCT RELATION
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // SINGLE ATTRIBUTE (optional backward compatibility)
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    // MULTIPLE ATTRIBUTES (many-to-many relationship)
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variation_attributes', 'variation_id', 'attribute_value_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Get readable variation name from attributes
    public function getVariationNameAttribute(): string
    {
        if ($this->attributes && is_array($this->attributes)) {
            return implode(' - ', array_values($this->attributes));
        }

        if ($this->attributeValue) {
            return $this->attributeValue->value ?? '';
        }

        return $this->name ?? '';
    }
}