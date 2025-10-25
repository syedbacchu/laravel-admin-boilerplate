<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerificationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'attemts',
        'expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
