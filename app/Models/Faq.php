<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'attestment',
        'sort_order',
        'status',
        'site_type',
    ];
    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
}
