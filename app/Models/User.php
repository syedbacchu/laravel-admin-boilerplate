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
        'email',
        'password',
        'address',
        'city',
        'thana',
        'district',
        'postal_code',
        'phone',
        'country',
        'provider_id',
        'email_verified_at',
        'verification_code',
        'skinType',
        'gender',
        'dateOfBirth',
        'interested',
        'verification_status',
        'membership_group',
        'occupation',
        'concern',
        'user_sap_id',
        'referred_by',
        'user_type',
        'code_expire',
        'new_email_verificiation_code',
        'remember_token',
        'device_token',
        'avatar',
        'avatar_original',
        'address',
        'country',
        'city',
        'postal_code',
        'phone',
        'balance',
        'banned',
        'referral_code',
        'customer_package_id',
        'remaining_uploads',
        'expires_at',
        'messenger_color',
        'dark_mode',
        'active_status',
        'skinType',
        'last_login_at',
        'last_login_ip',
        'interested',
        'gender',
        'dateOfBirth',
        'employee_id',
        'sales_employee_id',
        'membership_expired_date',
        'otp_attempts',
        'last_otp_sent_at',
        'sap_sync_status',
        'sap_sync_date'
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


    protected $casts = [
        'id' => 'string',
        'banned' => 'string',
        'remaining_uploads' => 'string',
        'dark_mode' => 'string',
        'active_status' => 'string',
        'verification_status' => 'string',
        'last_login_at' => 'datetime',
    ];

    public function getAttributes()
    {
        $attributes = parent::getAttributes();

        foreach ($attributes as $key => $value) {
            // Convert null to empty string only for attributes that are strings
            if (is_null($value) && in_array($key, [
                'banned',
                'remaining_uploads',
                'dark_mode',
                'active_status',
                'verification_status',
            ])) {
                $attributes[$key] = '';
            }
        }

        return $attributes;
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($attributes as $key => $value) {
            // Convert null to empty string only in the JSON array
            $attributes[$key] = is_null($value) ? '' : $value;
        }

        return $attributes;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_original ? ($this->avatar_original) : null;
    }

   
    public function adminActivityLogs()
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    // Add helper method
    public function getLastActivityAttribute()
    {
        return $this->adminActivityLogs()
            ->latest()
            ->first();
    }


    public function isStaffOrAdmin(): bool
    {
        return in_array($this->user_type, [self::TYPE_ADMIN, self::TYPE_STAFF]);
    }

}
