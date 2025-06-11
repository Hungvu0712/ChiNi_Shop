<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attachment_image',
        'public_attachment_image_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
