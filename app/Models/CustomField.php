<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = [
        'module',
        'name',
        'label',
        'type',
        'options',
        'is_required',
        'default_value',
        'validation_rules',
        'status',
        'sort_order'
    ];


    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'status' => 'boolean',
    ];


    public function values()
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
