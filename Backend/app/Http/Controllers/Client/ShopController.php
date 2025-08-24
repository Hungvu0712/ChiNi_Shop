<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Menu;
use Illuminate\Support\Str;
use App\Models\ProductReview;
use App\Models\Attribute;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->orderBy('order_index', 'asc')->get();

        // Lấy dữ liệu cho sidebar
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('attributeValues')->get();
        $priceMin = Product::min('price');
        $priceMax = Product::max('price');

        // Sản phẩm mặc định khi chưa lọc
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue.attribute'
        ])
            ->where('active', 1)
            ->paginate(12);

        // Gắn dữ liệu màu sắc & thuộc tính
        $this->attachProductAttributes($products);

        return view('client.pages.shop', compact(
            'products',
            'menus',
            'categories',
            'brands',
            'attributes',
            'priceMin',
            'priceMax'
        ));
    }


    public function show($slug)
    {
        $menus = Menu::whereNull('parent_id')->orderBy('order_index', 'asc')->get();

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
                    $attributeValue = (string) $vav->attributeValue->value;
                    $colorKey = strtolower(trim($attributeValue));

                    $variantKeyParts[$attributeName] = $attributeValue;

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

                    if (!isset($attributesByProduct[$attributeName])) {
                        $attributesByProduct[$attributeName] = [];
                    }
                    if (!in_array($attributeValue, $attributesByProduct[$attributeName])) {
                        $attributesByProduct[$attributeName][] = $attributeValue;
                    }
                }

                if (!empty($variantKeyParts)) {
                    $variantKey = implode('-', array_map(function ($attrName) use ($variantKeyParts) {
                        return $variantKeyParts[$attrName] ?? '';
                    }, array_keys($attributesByProduct)));

                    $variantsMap[$variantKey] = [
                        'id' => $variant->id,
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

            $related->colors = collect($attributesGrouped['Màu sắc'] ?? [])
                ->map(function ($color) use ($colorMap) {
                    $colorKey = strtolower(trim($color));
                    return [
                        'name' => $color,
                        'hex' => $colorMap[$colorKey] ?? '#ccc'
                    ];
                })->unique('name')->values()->all();

            $related->sizes = $attributesGrouped['Kích cỡ'] ?? [];
            $related->materials = $attributesGrouped['Chất liệu'] ?? [];
            $related->otherAttributes = collect($attributesGrouped)->except(['Màu sắc', 'Kích cỡ', 'Chất liệu']);
        }

        $reviews = ProductReview::with('user', 'images')
            ->where('product_id', $product->id)
            ->latest()
            ->get();

        $reviewCount = $reviews->count();

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

    public function filter(Request $request)
    {
        Log::info('==================== BẮT ĐẦU YÊU CẦU LỌC MỚI (V3) ====================');
        Log::info('Request data:', $request->all());

        $query = Product::with([
            'variants.variantAttributeValues.attributeValue.attribute'
        ])->where('active', 1);

        // Lọc theo giá, danh mục, thương hiệu (giữ nguyên)
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [(float)$request->min_price, (float)$request->max_price]);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }


        // ======================= LOGIC LỌC BIẾN THỂ VỚI ĐIỀU KIỆN IF MỚI =======================
        // Kiểm tra xem 'attributes' có tồn tại và có phải là một mảng không rỗng hay không
        if ($request->has('attributes') && is_array($request->input('attributes')) && !empty($request->input('attributes'))) {

            Log::info('--> [THÀNH CÔNG] Đã đi vào khối lệnh IF để lọc thuộc tính.');

            $attributes = array_filter($request->input('attributes'));

            if (!empty($attributes)) {
                $matchingVariantIds = null;

                foreach ($attributes as $attributeId => $valueIds) {
                    Log::info("-------> Đang lọc cho Attribute ID: $attributeId với Value IDs:", $valueIds);

                    $variantsWithThisAttribute = \App\Models\VariantAttributeValue::where('attribute_id', $attributeId)
                        ->whereIn('attribute_value_id', $valueIds)
                        ->pluck('variant_id')
                        ->unique();

                    Log::info('-------> Các Variant ID tìm thấy cho thuộc tính này:', $variantsWithThisAttribute->all());

                    if (is_null($matchingVariantIds)) {
                        $matchingVariantIds = $variantsWithThisAttribute;
                    } else {
                        $matchingVariantIds = $matchingVariantIds->intersect($variantsWithThisAttribute);
                    }

                    Log::info('-------> Danh sách tổng hợp Variant ID sau khi xử lý:', $matchingVariantIds->all());
                }

                Log::info('--> Lọc thuộc tính hoàn tất. Danh sách Variant ID cuối cùng:', $matchingVariantIds ? $matchingVariantIds->all() : []);

                if ($matchingVariantIds && $matchingVariantIds->isNotEmpty()) {
                    $query->whereHas('variants', function ($variantQuery) use ($matchingVariantIds) {
                        $variantQuery->whereIn('id', $matchingVariantIds->all());
                    });
                } else {
                    Log::info('--> Không tìm thấy biến thể nào phù hợp. Trả về kết quả rỗng.');
                    $query->where('id', -1);
                }
            } else {
                Log::info('--> Mảng attributes rỗng sau khi dùng array_filter.');
            }
        } else {
            Log::info('--> [THẤT BẠI] Không đi vào được khối lệnh IF. Kiểm tra điều kiện has(), is_array(), !empty().');
        }
        // ======================= KẾT THÚC LOGIC MỚI =======================

        $products = $query->paginate(12)->appends(($request->query()));
        Log::info('==================== KẾT THÚC YÊU CẦU LỌC. TÌM THẤY ' . $products->total() . ' SẢN PHẨM ====================');

        $this->attachProductAttributes($products);

        return view('client.pages.product_list', compact('products'))->render();
    }



    /**
     * Gắn dữ liệu màu sắc & thuộc tính cho danh sách sản phẩm
     */
    private function attachProductAttributes($products)
    {
        $colorMap = config('custom.color_map', []);
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

                            if ($colorAttribute && $vav->attribute_id == $colorAttribute->id) {
                                $colorKey = trim($attributeValue);
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

            $product->setAttribute('colorData', $colorDataForView);
            $product->setAttribute('attributesGroup', $attributesByProduct);
        }
    }
}
