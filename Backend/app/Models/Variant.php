<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'quantity',
        'weight',
        'variant_image',
        'public_variant_image_id',
        'active',
    ];

    public function product()
{
return $this->belongsTo(Product::class);
}

public function attributeValues()
{
return $this->hasMany(VariantAttributeValue::class);
}
}
