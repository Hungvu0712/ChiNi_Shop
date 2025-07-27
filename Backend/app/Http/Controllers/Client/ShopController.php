<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Menu;
use Illuminate\Support\Str;
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
        $colorAttribute = Attribute::whereRaw('LOWER(name) = ?', ['màu sắc'])->first();

        $attributesByProduct = [];
        $colorDataForView = [];
        $variantsMap = [];
        $attributeKeysLowerToOriginal = [];
        $normalize = fn($str) => strtolower(trim($str));

        // Gom tên attribute (dạng chuẩn hóa → tên gốc để hiển thị đúng)
        foreach ($product->variants as $variant) {
            foreach ($variant->variantAttributeValues as $vav) {
                if ($vav->attributeValue && $vav->attributeValue->attribute) {
                    $attrName = $vav->attributeValue->attribute->name;
                    $attrKey = $normalize($attrName);
                    $attributeKeysLowerToOriginal[$attrKey] = $attrName;
                }
            }
        }

        // Lấy danh sách tên attribute (theo thứ tự)
        $attributeNames = array_unique(array_map(
            fn($variant) => array_map(
                fn($vav) => $normalize($vav->attributeValue->attribute->name ?? ''),
                $variant->variantAttributeValues->all()
            ),
            $product->variants->all()
        ), SORT_REGULAR);
        $attributeNames = array_values(array_unique(array_merge(...$attributeNames)));

        // Duyệt tất cả các biến thể để tạo variantsMap
        foreach ($product->variants as $variant) {
            $variantKeyParts = [];

            foreach ($variant->variantAttributeValues as $vav) {
                if (!$vav->attributeValue || !$vav->attributeValue->attribute) {
                    continue;
                }

                $originalAttributeName = $vav->attributeValue->attribute->name;
                $attributeNameKey = $normalize($originalAttributeName);
                $attributeValue = (string) $vav->attributeValue->value;
                $colorKey = $normalize($attributeValue);

                // ✅ giữ nguyên giá trị gốc (viết hoa)
                $variantKeyParts[$attributeNameKey] = $attributeValue;

                // Gom thuộc tính cho view
                if (!isset($attributesByProduct[$attributeNameKey])) {
                    $attributesByProduct[$attributeNameKey] = [];
                }
                if (!in_array($attributeValue, $attributesByProduct[$attributeNameKey])) {
                    $attributesByProduct[$attributeNameKey][] = $attributeValue;
                }

                // Dữ liệu màu sắc riêng
                if ($colorAttribute && $vav->attribute_id == $colorAttribute->id) {
                    if (!collect($colorDataForView)->contains('name', $attributeValue)) {
                        $colorDataForView[] = [
                            'name' => $attributeValue,
                            'attribute_key' => $attributeNameKey,
                            'hex' => $colorMap[$colorKey] ?? '#cccccc',
                            'image' => $variant->variant_image ?? $product->product_image,
                            'price' => $variant->price ?? $product->price,
                            'variant_name' => $variant->variant_name ?? $product->name,
                        ];
                    }
                }
            }

            // ✅ Tạo key không phụ thuộc thứ tự thuộc tính
            $variantKey = implode(
                '-',
                collect($variantKeyParts)
                    ->sortKeys() // sort theo tên thuộc tính
                    ->values()   // lấy value theo thứ tự
                    ->all()
            );

            $variantsMap[$variantKey] = [
                'id' => $variant->id,
                'name' => $variant->variant_name ?? $product->name,
                'price' => $variant->price ?? $product->price,
                'sku' => $variant->sku ?? $product->sku,
                'quantity' => $variant->quantity ?? $product->quantity,
                'variant_image' => $variant->variant_image ?? $product->product_image,
            ];
        }

        // Dùng để hiển thị đúng tên attribute
        $attributeNamesReadable = array_map(fn($key) => $attributeKeysLowerToOriginal[$key] ?? $key, $attributeNames);

        $product->setAttribute('colorData', $colorDataForView);
        $product->setAttribute('attributesGroup', $attributesByProduct);

        // Sản phẩm liên quan (same brand)
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
                        $attrName = $normalize($vav->attributeValue->attribute->name);
                        $attrValue = (string) $vav->attributeValue->value;

                        if (!isset($attributesGrouped[$attrName])) {
                            $attributesGrouped[$attrName] = [];
                        }

                        if (!in_array($attrValue, $attributesGrouped[$attrName])) {
                            $attributesGrouped[$attrName][] = $attrValue;
                        }
                    }
                }
            }

            $related->colors = collect($attributesGrouped['màu sắc'] ?? [])
                ->map(function ($color) use ($colorMap, $normalize) {
                    $colorKey = $normalize($color);
                    return [
                        'name' => $color,
                        'hex' => $colorMap[$colorKey] ?? '#ccc'
                    ];
                })->unique('name')->values()->all();

            $related->sizes = $attributesGrouped['kích cỡ'] ?? [];
            $related->materials = array_merge(
                $attributesGrouped['vải'] ?? [],
                $attributesGrouped['chất liệu'] ?? []
            );

            $related->otherAttributes = collect($attributesGrouped)
                ->except(['màu sắc', 'kích cỡ', 'vải', 'chất liệu']);
        }

        return view('client.pages.product_detail', compact(
            'product',
            'galleryImages',
            'relatedProducts',
            'menus',
            'variantsMap',
            'attributeNamesReadable'
        ));
    }
}
