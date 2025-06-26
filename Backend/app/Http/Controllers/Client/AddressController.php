<?php

namespace App\Http\Controllers\Client;

use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Address\StoreAddress;
use App\Http\Requests\Address\UpdateAddress;

class AddressController extends Controller
{
    public function danhsachdiachi()
    {
        $address = Address::get();
        return view('profile.address.index', compact('address'));
    }

public function addAddress(StoreAddress $request)
{
    $isDefault = $request->has('is_default') ? true : false;

    if ($isDefault) {
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
    }

    $address = [
        'user_id' => auth()->id(),
        'fullname' => $request->fullname,
        'phone' => $request->phone,
        'address' => $request->address,
        'specific_address' => $request->specific_address,
        'is_default' => $isDefault,
    ];

    Address::create($address);

    return redirect()->route('address');
}



  public function update(UpdateAddress $request, $id)
{
    $address = Address::findOrFail($id);

    // Nếu là địa chỉ mặc định mới → cập nhật lại tất cả địa chỉ khác
    if ($request->has('is_default')) {
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->is_default = true;
    } else {
        $address->is_default = false;
    }

    // Lấy dữ liệu đã được validate
    $data = $request->validated();

    $address->fullname = $data['fullname'];
    $address->phone = $data['phone'];
    $address->address = $data['address'];
    $address->specific_address = $data['specific_address'];

    $address->save();

    return redirect()->route('address');
}

}
