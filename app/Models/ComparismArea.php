<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComparismArea extends Model
{
    use HasFactory;
    protected $fillable = [
        'compare_id',
        'left_side',
        'right_side',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'left_side' => 'array',
        'right_side' => 'array',
        'sort_order' => 'array',
    ];

    public function comparism()
    {
        return $this->belongsTo(Comparism::class, 'compare_id');
    }
}
