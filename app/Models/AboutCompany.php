<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_image',
        'title',
        'subtitle',
        'our_story',
        'story_image',
        'mission',
        'vision',
        'core_values',
        'company_stats',
        'why_choose',
        'added_by',
        'updated_by',
        'site_type'
    ];

    protected $casts = [
        'core_values' => 'array',
        'company_stats' => 'array',
        'why_choose' => 'array',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
