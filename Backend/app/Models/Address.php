<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'address',
        'to_district_id',
        'to_ward_code',
        'specific_address',
        'is_default',
    ];
    protected $casts = [
        'is_default' => 'boolean',
    ];
}
