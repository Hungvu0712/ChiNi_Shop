<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        // Lấy sản phẩm cùng các mối quan hệ
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])->paginate(12);

        foreach ($products as $product) {
            $colorSet = collect();
            $sizeSet = collect();
            $colorVariants = [];

            foreach ($product->variants ?? [] as $variant) {
                foreach ($variant->variantAttributeValues ?? [] as $attr) {
                    if (!$attr->attributeValue) {
                        continue;
                    }

                    $value = $attr->attributeValue->value;

                    if ($attr->attribute_id == 1) {
                        $slug = strtolower(Str::slug($value));
                        $colorSet->push($slug);

                        // Gán ảnh cho từng màu nếu chưa có
                        if (!isset($colorVariants[$slug]) && $variant->variant_image) {
                            $colorVariants[$slug] = $variant->variant_image;
                        }
                    } elseif ($attr->attribute_id == 2) {
                        $sizeSet->push(strtoupper($value));
                    }
                }
            }

            // Gắn lại thuộc tính cho sản phẩm (thêm dòng này nhưng không xoá gì cả)

            $product->colors = $colorSet->unique()->values()->all();
            $product->sizes = $sizeSet->unique()->values()->all();
            $product->colorVariants = $colorVariants;
        }

        return view('client.pages.shop', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'variants.variantAttributeValues.attributeValue',
            'category',
            'brand',
            'attachments' // thêm để eager load
        ])->where('slug', $slug)->firstOrFail();

        // Gộp ảnh: ảnh chính + ảnh phụ từ attachments
        $galleryImages = array_merge(
            [$product->product_image],
            $product->attachments->pluck('attachment_image')->toArray()
        );

        // Xử lý màu và size
        $colorSet = collect();
        $sizeSet = collect();
        $colorVariants = [];

        foreach ($product->variants as $variant) {
            foreach ($variant->variantAttributeValues as $attr) {
                if (!$attr->attributeValue) continue;

                if ($attr->attribute_id == 1) {
                    $slug = strtolower(Str::slug($attr->attributeValue->value));
                    $colorSet->push($slug);

                    // Gán ảnh cho từng màu nếu chưa có
                    if (!isset($colorVariants[$slug]) && $variant->variant_image) {
                        $colorVariants[$slug] = $variant->variant_image;
                    }
                } elseif ($attr->attribute_id == 2) {
                    $sizeSet->push(strtoupper($attr->attributeValue->value));
                }
            }
        }

        $product->colors = $colorSet->unique()->values()->all();
        $product->sizes = $sizeSet->unique()->values()->all();
        $product->colorVariants = $colorVariants; // ✅ thêm dòng này

        // Sản phẩm cùng thương hiệu
        $relatedProducts = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->limit(6)
            ->get();

        foreach ($relatedProducts as $related) {
            $colorSet = collect();
            $sizeSet = collect();
            $colorVariants = [];

            foreach ($related->variants as $variant) {
                foreach ($variant->variantAttributeValues as $attr) {
                    if (!$attr->attributeValue) continue;

                    if ($attr->attribute_id == 1) {
                        $slug = strtolower(Str::slug($attr->attributeValue->value));
                        $colorSet->push($slug);

                        // Gán ảnh cho từng màu nếu chưa có
                        if (!isset($colorVariants[$slug]) && $variant->variant_image) {
                            $colorVariants[$slug] = $variant->variant_image;
                        }
                    } elseif ($attr->attribute_id == 2) {
                        $sizeSet->push(strtoupper($attr->attributeValue->value));
                    }
                }
            }

            $related->colors = $colorSet->unique()->values()->all();
            $related->sizes = $sizeSet->unique()->values()->all();
            $related->colorVariants = $colorVariants; // ✅ thêm dòng này
        }

        return view('client.pages.product_detail', compact('product', 'galleryImages', 'relatedProducts'));
    }
}
