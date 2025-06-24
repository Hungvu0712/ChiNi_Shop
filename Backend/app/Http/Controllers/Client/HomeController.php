<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'variants.variantAttributeValues.attributeValue'
        ])->get();

        // Gắn danh sách màu (đã chuẩn hóa) vào từng sản phẩm
        foreach ($products as $product) {
            $colorSet = [];

            foreach ($product->variants as $variant) {
                foreach ($variant->variantAttributeValues as $attr) {
                    if ($attr->attribute_id == 1) { // 1 = color
                        $value = strtolower(Str::slug($attr->attributeValue->value));
                        $colorSet[] = $value;
                    }
                }
            }

            $product->setAttribute('colors', array_unique($colorSet));
        }

        return view('client.pages.home', compact('products'));
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
