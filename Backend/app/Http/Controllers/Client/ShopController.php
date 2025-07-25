<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Menu;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])
            ->where('active', 1)
            ->paginate(12);

        foreach ($products as $product) {
            $colorSet = collect();
            $sizeSet = collect();
            $variantMap = [];

            foreach ($product->variants as $variant) {
                $colorAttr = $variant->variantAttributeValues->firstWhere('attribute_id', 1);
                $sizeAttr  = $variant->variantAttributeValues->firstWhere('attribute_id', 2);

                if ($colorAttr && $sizeAttr) {
                    $color = strtolower(Str::slug($colorAttr->attributeValue->value));
                    $size  = strtoupper($sizeAttr->attributeValue->value);
                    $key = "{$color}-{$size}";

                    $variantMap[$key] = [
                        'image' => $variant->variant_image ?? $product->product_image,
                        'price' => $variant->price ?? $product->price,
                    ];
                }
            }

            $product->variantMap = $variantMap;
            $product->colors = collect(array_unique(array_map(fn($key) => explode('-', $key)[0], array_keys($variantMap))))->values()->all();
            $product->sizes = collect(array_unique(array_map(fn($key) => explode('-', $key)[1], array_keys($variantMap))))->values()->all();
        }

        return view('client.pages.shop', compact('products', 'menus'));
    }



    public function show($slug)
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        $product = Product::with([
            'variants.variantAttributeValues.attributeValue',
            'category',
            'brand',
            'attachments',
        ])
            ->where('slug', $slug)
            ->where('active', 1)
            ->firstOrFail();

        // Gộp gallery chính + phụ
        $galleryImages = array_merge(
            [$product->product_image],
            $product->attachments->pluck('attachment_image')->toArray()
        );

        // Chuẩn bị tập hợp
        $colorSet = collect();
        $sizeSet = collect();
        $variantByColor = [];

        foreach ($product->variants as $variant) {
            $colorAttr = $variant->variantAttributeValues->firstWhere('attribute_id', 1);
            $sizeAttr = $variant->variantAttributeValues->firstWhere('attribute_id', 2);

            if ($colorAttr && $sizeAttr && $colorAttr->attributeValue && $sizeAttr->attributeValue) {
                $colorSlug = strtolower(Str::slug($colorAttr->attributeValue->value));
                $sizeSlug = strtoupper($sizeAttr->attributeValue->value);

                $colorSet->push($colorSlug);
                $sizeSet->push($sizeSlug);

                // ✅ Biến thể lồng: color → size
                $variantByColor[$colorSlug]['variants'][$sizeSlug] = [
                    'variant_name' => $variant->variant_name ?? $product->name,
                    'price' => $variant->price ?? $product->price,
                    'sku' => $variant->sku ?? $product->sku,
                    'image' => $variant->variant_image,
                    'gallery' => [$variant->variant_image],
                ];
            }
        }

        $product->colors = $colorSet->unique()->values()->all();
        $product->sizes = $sizeSet->unique()->values()->all();
        $product->variantByColor = $variantByColor;

        // Sản phẩm cùng thương hiệu (giữ nguyên phần dưới)
        $relatedProducts = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->where('active', 1)
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
            $related->colorVariants = $colorVariants;
        }

        return view('client.pages.product_detail', compact('product', 'galleryImages', 'relatedProducts', 'menus'));
    }
}
