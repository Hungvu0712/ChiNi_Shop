<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $banner = Banner::where('active', 1)->first();
        return view('client.pages.home', compact('banner'));
    }



}
