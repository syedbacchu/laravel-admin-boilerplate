<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\HasCustomFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory, Auditable, HasCustomFields;

    protected $fillable = [
        'photo',
        'title',
        'subtitle',
        'tagline',
        'status',
        'link',
        'mobile_banner',
        'type',
        'serial',
        'video_link',
        'page',
        'site_type',
        'cta_button',
        'stat',
    ];

    protected $casts = [
        'cta_button' => 'array',
        'stat' => 'array',
    ];

}
