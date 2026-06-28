<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropshipping extends Model
{
    use HasFactory;

    protected $table = 'dropshippings';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'district',
        'thana',
        'address',
        'product_id',
        'product_range',
        'status',
        'note',
    ];

    /**
     * Optional relation (if you have products table)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}