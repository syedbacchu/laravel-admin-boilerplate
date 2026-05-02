<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory; 
    protected $table = 'attribute_values';

    protected $fillable = [
        'type_id',
        'name',
        'icon',
        'value',
        'status',
    ];

    public function attribute()
    {
        return $this->belongsTo(AttributeType::class, 'type_id');
    }
}
