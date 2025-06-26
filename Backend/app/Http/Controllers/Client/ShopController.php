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
        ])
            ->where('active', 1) // ✅ Chỉ sản phẩm đã kích hoạt
            ->paginate(12);
        foreach ($products as $product) {
            $colorSet = collect();
            $sizeSet = collect();
            $colorVariants = [];
            $colorPrices = [];
            $colorNames = [];

            foreach ($product->variants ?? [] as $variant) {
                foreach ($variant->variantAttributeValues ?? [] as $attr) {
                    if (!$attr->attributeValue) continue;

                    $value = $attr->attributeValue->value;
                    if ($attr->attribute_id == 1) {
                        $slug = strtolower(Str::slug($value));
                        $colorSet->push($slug);

                        if (!isset($colorVariants[$slug]) && $variant->variant_image) {
                            $colorVariants[$slug] = $variant->variant_image;
                        }

                        // Gán tên và giá theo màu (biến thể)
                        if (!isset($colorPrices[$slug])) {
                            $colorPrices[$slug] = $variant->price;
                            $colorNames[$slug] = $variant->variant_name ?? $product->name;
                        }
                    } elseif ($attr->attribute_id == 2) {
                        $sizeSet->push(strtoupper($value));
                    }
                }
            }

            $product->colors = $colorSet->unique()->values()->all();
            $product->sizes = $sizeSet->unique()->values()->all();
            $product->colorVariants = $colorVariants;
            $product->colorPrices = $colorPrices;
            $product->colorNames = $colorNames;
        }


        return view('client.pages.shop', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'variants.variantAttributeValues.attributeValue',
            'category',
            'brand',
        ])
            ->where('slug', $slug)
            ->where('active', 1) // ✅ Kiểm tra trạng thái
            ->firstOrFail();
        // Gộp ảnh: ảnh chính + ảnh phụ từ attachments
        $galleryImages = array_merge(
            [$product->product_image],
            $product->attachments->pluck('attachment_image')->toArray()
        );

        // Xử lý màu và size
        $colorSet = collect();
        $sizeSet = collect();
        $variantByColor = [];

        foreach ($product->variants as $variant) {
            $colorAttr = $variant->variantAttributeValues->firstWhere('attribute_id', 1);
            $sizeAttr = $variant->variantAttributeValues->firstWhere('attribute_id', 2);

            if ($colorAttr && $colorAttr->attributeValue) {
                $colorSlug = strtolower(Str::slug($colorAttr->attributeValue->value));

                // ✅ Thêm dòng này để danh sách màu hiển thị
                $colorSet->push($colorSlug);

                if (!isset($variantByColor[$colorSlug])) {
                    $variantByColor[$colorSlug] = [
                        'variant_name' => $variant->variant_name ?? $product->name,
                        'price' => $variant->price ?? $product->price,
                        'sku' => $variant->sku ?? $product->sku,
                        'image' => $variant->variant_image,
                        'gallery' => [$variant->variant_image] // nếu sau này có gallery riêng thì sửa
                    ];
                }
            }

            if ($sizeAttr && $sizeAttr->attributeValue) {
                $sizeSet->push(strtoupper($sizeAttr->attributeValue->value));
            }
        }



        $product->colors = $colorSet->unique()->values()->all();
        $product->sizes = $sizeSet->unique()->values()->all();
        $product->variantByColor = $variantByColor;

        // Sản phẩm cùng thương hiệu
        $relatedProducts = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->where('active', 1) // ✅ Chỉ sản phẩm đã kích hoạt
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
