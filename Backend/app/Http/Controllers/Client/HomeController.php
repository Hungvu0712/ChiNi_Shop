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

            foreach ($product->variants as $variant) {
                foreach ($variant->variantAttributeValues as $attr) {
                    if ($attr->attribute_id == 1 && $attr->attributeValue) {
                        $colorKey = strtolower(Str::slug($attr->attributeValue->value));

                        // ✅ Ghi nhận màu
                        $colorSet[] = $colorKey;

                        // ✅ Gán ảnh cho từng màu
                        if (!isset($colorVariants[$colorKey]) && $variant->variant_image) {
                            $colorVariants[$colorKey] = $variant->variant_image;
                        }

                        // ✅ Gán giá và tên biến thể nếu chưa có
                        if (!isset($colorPrices[$colorKey])) {
                            $colorPrices[$colorKey] = $variant->price;
                            $colorNames[$colorKey] = $variant->variant_name ?? $product->name;
                        }
                    }
                }
            }

            $product->setAttribute('colorVariants', $colorVariants);
            $product->setAttribute('colorPrices', $colorPrices);
            $product->setAttribute('colorNames', $colorNames);
            $product->setAttribute('colors', array_unique($colorSet));
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
