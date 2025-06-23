<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); // hoặc paginate(n) nếu cần phân trang
        return view('client.products.index', compact('products'));
    }
}
