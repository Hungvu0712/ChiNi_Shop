<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('client.pages.home');
    }

    public function danhsachdiachi() {
        $address = Address::get();
        return view('profile.address', compact('address'));
    }

    public function addAddress(Request $request) {
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
