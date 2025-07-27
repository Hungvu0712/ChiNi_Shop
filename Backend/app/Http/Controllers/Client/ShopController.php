<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Menu;
use Illuminate\Support\Str;
use App\Models\ProductReview;
use App\Models\Attribute; // ✅ 1. Thêm model Attribute

class ShopController extends Controller
{
    public function index()
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue.attribute'
        ])
            ->where('active', 1)
            ->paginate(12);

        // ✅ Lấy map mã màu từ config
        $colorMap = config('custom.color_map', []);

        // ✅ Lấy thuộc tính "Màu sắc" một lần để sử dụng
        $colorAttribute = Attribute::where('name', 'Màu sắc')->first();

        foreach ($products as $product) {
            $attributesByProduct = [];
            $colorDataForView = [];

            if ($product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    if ($variant->variantAttributeValues->isNotEmpty()) {
                        foreach ($variant->variantAttributeValues as $vav) {
                            if (!$vav->attributeValue || !$vav->attributeValue->attribute) {
                                continue;
                            }

                            $attributeName = $vav->attributeValue->attribute->name;
                            $attributeValue = $vav->attributeValue->value;

                            // ✅ Xử lý riêng cho màu sắc
                            if ($colorAttribute && $vav->attribute_id == $colorAttribute->id) {
                                $colorKey = trim($attributeValue); // KHÔNG dùng strtolower
                                if (!collect($colorDataForView)->contains('name', $attributeValue)) {
                                    $colorDataForView[] = [
                                        'name' => $attributeValue,
                                        'hex' => $colorMap[$colorKey] ?? '#cccccc', // Sử dụng đúng key
                                        'image' => $variant->variant_image ?? $product->product_image,
                                        'price' => $variant->price ?? $product->price,
                                        'variant_name' => $variant->variant_name ?? $product->name,
                                    ];
                                }
                            }
                            // ✅ Gom nhóm các thuộc tính khác (không chỉ màu)
                            if (!isset($attributesByProduct[$attributeName])) {
                                $attributesByProduct[$attributeName] = [];
                            }
                            if (!in_array($attributeValue, $attributesByProduct[$attributeName])) {
                                $attributesByProduct[$attributeName][] = $attributeValue;
                            }
                        }
                    }
                }
            }

            // ✅ Luôn gán vào sản phẩm (dù có hoặc không có biến thể)
            $product->setAttribute('colorData', $colorDataForView);
            $product->setAttribute('attributesGroup', $attributesByProduct);
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

        $galleryImages = array_merge(
            [$product->product_image],
            $product->attachments->pluck('attachment_image')->toArray()
        );

        $colorMap = config('custom.color_map', []);
        $colorAttribute = Attribute::where('name', 'Màu sắc')->first();

        $attributesByProduct = [];
        $colorDataForView = [];
        $variantsMap = [];
        $attributeNames = [];

        if ($product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                $variantKeyParts = [];

                foreach ($variant->variantAttributeValues as $vav) {
                    if (!$vav->attributeValue || !$vav->attributeValue->attribute) {
                        continue;
                    }

                    $attributeName = $vav->attributeValue->attribute->name;
                    $value = $vav->attributeValue->value;
                    $attributeValue = is_array($value) ? implode(' ', $value) : (string) $value;
                    $colorKey = strtolower(trim($attributeValue));

                    // ✅ Gộp các giá trị theo attribute
                    $variantKeyParts[$attributeName] = $attributeValue;

                    // ✅ Màu sắc
                    if ($colorAttribute && $vav->attribute_id == $colorAttribute->id) {
                        if (!collect($colorDataForView)->contains('name', $attributeValue)) {
                            $colorDataForView[] = [
                                'name' => $attributeValue,
                                'hex' => $colorMap[$colorKey] ?? '#cccccc',
                                'image' => $variant->variant_image ?? $product->product_image,
                                'price' => $variant->price ?? $product->price,
                                'variant_name' => $variant->variant_name ?? $product->name,
                            ];
                        }
                    }

                    // ✅ Gom nhóm thuộc tính khác
                    if (!isset($attributesByProduct[$attributeName])) {
                        $attributesByProduct[$attributeName] = [];
                    }
                    if (!in_array($attributeValue, $attributesByProduct[$attributeName])) {
                        $attributesByProduct[$attributeName][] = $attributeValue;
                    }
                }

                // ✅ Xây variantsMap để truyền qua JS
                if (!empty($variantKeyParts)) {
                    $variantKey = implode('-', array_map(function ($attrName) use ($variantKeyParts) {
                        return $variantKeyParts[$attrName] ?? '';
                    }, array_keys($attributesByProduct)));


                    $variantsMap[$variantKey] = [
                        'id' => $variant->id, // Đúng chuẩn để JS đọc được variant.id
                        'name' => $variant->variant_name ?? $product->name,
                        'price' => $variant->price ?? $product->price,
                        'sku' => $variant->sku ?? $product->sku,
                        'quantity' => $variant->quantity ?? $product->quantity,
                        'variant_image' => $variant->variant_image ?? $product->product_image,
                    ];
                }
            }

            $attributeNames = array_keys($attributesByProduct);
        }

        $product->setAttribute('colorData', $colorDataForView);
        $product->setAttribute('attributesGroup', $attributesByProduct);

        $relatedProducts = Product::with([
            'variants.variantAttributeValues.attributeValue.attribute',
            'brand'
        ])
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->where('active', 1)
            ->limit(6)
            ->get();

        foreach ($relatedProducts as $related) {
            $attributesGrouped = [];
            foreach ($related->variants as $variant) {
                foreach ($variant->variantAttributeValues as $vav) {
                    if ($vav->attributeValue && $vav->attributeValue->attribute) {
                        $attrName = $vav->attributeValue->attribute->name;
                        $attrValue = is_array($vav->attributeValue->value)
                            ? implode(' ', $vav->attributeValue->value)
                            : (string) $vav->attributeValue->value;

                        if (!isset($attributesGrouped[$attrName])) {
                            $attributesGrouped[$attrName] = [];
                        }

                        if (!in_array($attrValue, $attributesGrouped[$attrName])) {
                            $attributesGrouped[$attrName][] = $attrValue;
                        }
                    }
                }
            }

            // Lưu vào các thuộc tính riêng
            $related->colors = collect($attributesGrouped['Màu sắc'] ?? [])
                ->map(function ($color) use ($colorMap) {
                    $colorKey = strtolower(trim($color));
                    return [
                        'name' => $color,
                        'hex' => $colorMap[$colorKey] ?? '#ccc'
                    ];
                })->unique('name')->values()->all();

            $related->sizes = $attributesGrouped['Kích cỡ'] ?? []; // hoặc 'Size'
            $related->materials = $attributesGrouped['Vải'] ?? []; // nếu có thuộc tính vải
            $related->materials = $attributesGrouped['Chất liệu'] ?? [];
            $related->otherAttributes = collect($attributesGrouped)->except(['Màu sắc', 'Kích cỡ', 'Vải']);
        }


        //bình luận
        $reviews = ProductReview::with('user', 'images')
            ->where('product_id', $product->id)
            ->latest()
            ->get();

        $reviewCount = $reviews->count();


        // dd($variantsMap);

        return view('client.pages.product_detail', compact(
            'product',
            'galleryImages',
            'relatedProducts',
            'menus',
            'variantsMap',
            'attributeNames',
            'reviews',
            'reviewCount',
        ));
    }
}
