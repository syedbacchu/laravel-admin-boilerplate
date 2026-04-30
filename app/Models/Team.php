<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'image',
        'cover_image',
        'bio',
        'designation',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'github_url',
        'youtube_url',
        'join_date',
        'created_by',
        'updated_by',
        'site_type',
        'is_featured',
        'status',
    ];
}
