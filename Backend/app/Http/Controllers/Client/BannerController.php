<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function show() {
        $banner = Banner::where('active', 1)->first();

        return view('client.banner.index', compact('banner'));
    }
}
