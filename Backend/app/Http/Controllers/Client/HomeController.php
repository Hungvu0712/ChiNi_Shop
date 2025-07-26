<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Attribute; // ✅ 1. Thêm model Attribute

class HomeController extends Controller
{

    public function index()
    {
        $banner = Banner::where('active', 1)->first();
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();

        // ✅ SỬA LỖI: Eager load đầy đủ các relationship cần thiết
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue.attribute'
        ])
        ->where('active', 1)
        ->get();

        $colorMap = config('custom.color_map', []);
        $colorAttribute = Attribute::where('name', 'Màu sắc')->first();

        foreach ($products as $product) {
            $attributesByProduct = [];
            $colorDataForView = [];

            // Chỉ xử lý nếu sản phẩm có biến thể
            if ($product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    // Chỉ xử lý nếu biến thể có thuộc tính
                    if ($variant->variantAttributeValues->isNotEmpty()) {
                        foreach ($variant->variantAttributeValues as $vav) {
                            if (!$vav->attributeValue || !$vav->attributeValue->attribute) {
                                continue;
                            }

                            $attributeName = $vav->attributeValue->attribute->name;
                            $attributeValue = $vav->attributeValue->value;

                            // Xử lý riêng cho MÀU SẮC
                            if ($colorAttribute && $vav->attribute_id == $colorAttribute->id) {
                                $colorKey = strtolower($attributeValue);

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

                            // Gom nhóm các thuộc tính khác
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

        // Nhớ xóa dòng dd() này sau khi đã kiểm tra xong
        // dd($products->first()->toArray()); 

        return view('client.pages.home', compact('products', 'banner', 'menus'));
    }

    public function danhsachdiachi()
    {
        $address = Address::get();
        return view('profile.address', compact('address'));
    }

    public function addAddress(Request $request)
    {
        $address = [
            'user_id' => auth()->id(),
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'address' => $request->address,
            'specific_address' => $request->specific_address,
        ];
        Address::create($address);
    }
}
