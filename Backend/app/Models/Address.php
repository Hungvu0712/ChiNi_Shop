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
        'email',
        'phone',
        'province',
        'district',
        'ward',
        'address',
        'note',
        'is_default',
    ];
}
