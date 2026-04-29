<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'title',
        'subtitle',
        'description',
        'link',
        'sort_order',
        'status',
        'added_by',
        'updated_by',
    ];
}
