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
            'variants.variantAttributeValues.attributeValue'
        ])
            ->where('active', 1)
            ->paginate(12);

        // ✅ 2. Lấy thuộc tính bằng tên một lần để tái sử dụng
        $colorAttribute = Attribute::where('name', 'Màu sắc')->first();
        $sizeAttribute = Attribute::where('name', 'Kích thước')->first();

        foreach ($products as $product) {
            $variantMap = [];

            // Bỏ qua nếu không có thuộc tính
            if (!$colorAttribute || !$sizeAttribute) continue;

            foreach ($product->variants as $variant) {
                // ✅ 3. Tìm thuộc tính bằng ID động
                $colorAttr = $variant->variantAttributeValues->firstWhere('attribute_id', $colorAttribute->id);
                $sizeAttr  = $variant->variantAttributeValues->firstWhere('attribute_id', $sizeAttribute->id);

                if ($colorAttr && $sizeAttr && $colorAttr->attributeValue && $sizeAttr->attributeValue) {
                    // ✅ 4. Dùng giá trị gốc, không dùng slug
                    $color = strtolower($colorAttr->attributeValue->value);
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


        // dd($variantsMap);

        return view('client.pages.product_detail', compact(
            'product',
            'galleryImages',
            'relatedProducts',
            'menus',
            'variantsMap',
            'attributeNames'
        ));
    }
}
