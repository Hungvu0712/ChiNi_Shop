<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;
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
            'variants.variantAttributeValues.attributeValue.attribute',
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

        // Xử lý thuộc tính động
        $attributeNames = [];
        $attributeValues = [];
        $variantsMap = [];

        foreach ($product->variants as $variant) {
            $attrPairs = [];

            foreach ($variant->variantAttributeValues as $attrValue) {
                if (!$attrValue->attributeValue || !$attrValue->attributeValue->attribute) continue;

                $attrName = strtolower($attrValue->attributeValue->attribute->name);
                $value = $attrValue->attributeValue->value;

                $attributeNames[] = $attrName;
                $attributeValues[$attrName][] = $value;

                $attrPairs[$attrName] = $value;
            }

            $attributeNames = array_unique($attributeNames);

            $keyParts = [];
            foreach ($attributeNames as $name) {
                $keyParts[] = $attrPairs[$name] ?? '';
            }

            $key = implode('-', $keyParts);

            $variantsMap[$key] = [
                'id' => $variant->id,
                'name' => $product->name . ' - ' . implode(' / ', array_values($attrPairs)),
                'price' => $variant->price ?? $product->price,
                'quantity' => $variant->quantity ?? 0,
                'variant_image' => $variant->variant_image ?? $product->product_image, // ✅ Dữ liệu này đã có sẵn
            ];
        }

        foreach ($attributeValues as $key => $vals) {
            $attributeValues[$key] = array_unique($vals);
        }

        // Sản phẩm cùng thương hiệu
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
        Log::info('Product Price: ' . $product->price);
        if ($product->variants->first()) {
            Log::info('First Variant Price: ' . $product->variants->first()->price);
        } else {
            Log::info('No variants found for this product.');
        }

        return view('client.pages.product_detail', compact(
            'product',
            'galleryImages',
            'relatedProducts',
            'menus',
            'attributeNames',
            'attributeValues',
            'variantsMap'
        ));
    }
}
