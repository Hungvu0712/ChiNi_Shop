<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'voucher_id',
        'code',
        'total_price',
        'shipping_fee',
        'discount_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'note',
    ];
}
