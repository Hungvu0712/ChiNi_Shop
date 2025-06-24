<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
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
            'category'
        ])->where('slug', $slug)->firstOrFail();

        // Gộp ảnh từ các variants (nếu có)
        $galleryImages = $product->variants
            ->pluck('variant_image')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Xử lý color và size như ở trang danh sách
        $colorSet = collect();
        $sizeSet = collect();

        foreach ($product->variants as $variant) {
            foreach ($variant->variantAttributeValues as $attr) {
                if (!$attr->attributeValue) continue;

                if ($attr->attribute_id == 1) {
                    $colorSet->push(strtolower(Str::slug($attr->attributeValue->value)));
                } elseif ($attr->attribute_id == 2) {
                    $sizeSet->push(strtoupper($attr->attributeValue->value));
                }
            }
        }

        $product->colors = $colorSet->unique()->values()->all();
        $product->sizes = $sizeSet->unique()->values()->all();

        return view('client.pages.product_detail', compact('product', 'galleryImages'));
    }
}
