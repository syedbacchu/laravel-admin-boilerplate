<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryWaterLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'district',
        'thana',
        'address',
        'bottle_size',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'note',
        'admin_note',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'status' => 'integer',
    ];
}
