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
        'name',
        'sku',
        'price',
        'stock',
        'attributes',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
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
}