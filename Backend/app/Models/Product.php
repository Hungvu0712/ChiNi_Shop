<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'price',
        'description',
        'product_image',
        'public_product_image_id',
        'weight',
        'quantity',
        'quantity_warning',
        'tags',
        'sku',
        'active',
    ];
    public function attachments()
    {
        return $this->hasMany(ProductAttachment::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
