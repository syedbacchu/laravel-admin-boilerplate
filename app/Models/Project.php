<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'location',
        'savings',
        'project_url',
        'video_url',
        'start_date',
        'end_date',
        'thumbnail',
        'gallery',
        'category_id',
        'project_status',
        'sort_order',
        'is_featured',
        'status',
        'site_type',
        'added_by',
        'updated_by',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'meta_image',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'category_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'gallery' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    public function scopeWithCategory($query)
    {
        return $query->with('category:id,name,slug');
    }

    public function scopeByProjectStatus($query, $status)
    {
        return $query->where('project_status', $status);
    }
}
