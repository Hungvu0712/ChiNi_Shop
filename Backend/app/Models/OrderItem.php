<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'total',
    ];

    // 🔹 Thêm quan hệ đến đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // 🔹 Thêm quan hệ đến sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
