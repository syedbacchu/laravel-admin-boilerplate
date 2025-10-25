<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    public const TYPE_ADMIN = 'admin';
    public const TYPE_STAFF = 'staff';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'phone_code',
        'password',
        'role_module',
        'role_id',
        'status',
        'is_private',
        'added_by',
        'is_phone_verified',
        'is_email_verified',
        'image',
        'gender',
        'date_of_birth',
        'blood_group',
        'language',
        'address',
        'country',
        'division',
        'district',
        'thana',
        'city',
        'postal_code',
        'is_social_login',
        'social_network_id',
        'social_network_type',
        'email_notification_status',
        'phone_notification_status',
        'push_notification_status',
        'facebook_link',
        'linkedin_link',
        'youtube_link',
        'twitter_link',
        'instagram_link',
        'whatsapp_link',
        'telegram_link',
        'device_token',
        'avatar',
        'avatar_original',
        'referral_code',
        'referred_by',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['avatar_url'];


    public function getAvatarAttribute($value)
    {
        if (empty($value)) return '';

        return ($value);
    }


    public function getAvatarUrlAttribute()
    {
        return $this->avatar_original ? ($this->avatar_original) : null;
    }


}
