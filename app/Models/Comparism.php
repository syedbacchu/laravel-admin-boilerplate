<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparism extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_type',
        'area',
        'status',
    ];

    public function areas()
    {
        return $this->hasMany(ComparismArea::class, 'compare_id');
    }
}
