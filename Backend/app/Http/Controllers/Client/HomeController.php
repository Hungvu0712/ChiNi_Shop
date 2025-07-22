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

class HomeController extends Controller
{

    public function index()
    {

        $banner = Banner::where('active', 1)->first();
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();

        $products = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])
            ->where('active', 1) // ✅ Chỉ lấy sản phẩm đã kích hoạt
            ->get();

        foreach ($products as $product) {
            $colorSet = [];
            $colorVariants = [];
            $colorPrices = [];
            $colorNames = [];
            $productVariantsData = []; // ✅ Thêm mảng này để lưu trữ tất cả dữ liệu biến thể

            foreach ($product->variants as $variant) {
                $variantAttributes = [];
                foreach ($variant->variantAttributeValues as $attr) {
                    if ($attr->attributeValue) {
                        $attributeName = strtolower(Str::slug($attr->attribute->name ?? ''));
                        $attributeValue = $attr->attributeValue->value;

                        $variantAttributes[$attributeName] = $attributeValue; // ✅ Lưu trữ tất cả thuộc tính của biến thể
                    }
                }

                // ✅ Lưu trữ thông tin từng biến thể vào mảng mới
                $productVariantsData[] = [
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->variant_name ?? $product->name,
                    'price' => $variant->price,
                    'image' => $variant->variant_image ?? $product->product_image,
                    'attributes' => $variantAttributes,
                ];

                // Logic hiện có của bạn để xử lý màu sắc (nếu bạn vẫn muốn giữ riêng)
                if (isset($variantAttributes['mau-sac']) && $variant->variant_image) {
                    $colorKey = strtolower(Str::slug($variantAttributes['mau-sac']));

                    $colorSet[] = $colorKey;

                    if (!isset($colorVariants[$colorKey])) {
                        $colorVariants[$colorKey] = $variant->variant_image;
                    }

                    if (!isset($colorPrices[$colorKey])) {
                        $colorPrices[$colorKey] = $variant->price;
                        $colorNames[$colorKey] = $variant->variant_name ?? $product->name;
                    }
                }
            }

            $product->setAttribute('colorVariants', $colorVariants);
            $product->setAttribute('colorPrices', $colorPrices);
            $product->setAttribute('colorNames', $colorNames);
            $product->setAttribute('colors', array_unique($colorSet));
            $product->setAttribute('allVariants', $productVariantsData); // ✅ Gán tất cả dữ liệu biến thể vào product
        }

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
