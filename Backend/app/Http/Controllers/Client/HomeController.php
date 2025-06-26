<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $banner = Banner::where('active', 1)->first();
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        return view('client.pages.home', compact('banner', 'menus'));
    }



}
