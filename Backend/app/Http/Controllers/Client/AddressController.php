<?php

namespace App\Http\Controllers\Client;

use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Address\StoreAddress;
use App\Http\Requests\Address\UpdateAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function danhsachdiachi()
    {
        $address = Address::where('user_id',Auth::id())->orderByDesc('id')->get();
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

    public function addressDefault(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id'
        ]);

        // Reset tất cả địa chỉ về false
        Address::where('user_id', auth()->id())->update(['is_default' => false]);

        // Set địa chỉ được chọn là mặc định
        $address = Address::where('user_id', auth()->id())
            ->findOrFail($request->address_id);

        $address->is_default = true;
        $address->save();

        return back()->with('success', 'Cập nhật địa chỉ thành công');
    }

    public function addAddressFromCheckout(StoreAddress $request)
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
            'to_ward_code'=>$request->to_ward_code,
            'to_district_id'=>$request->to_district_id,

        ];

        Address::create($address);

        return back()->with('success','Thêm mới thành công');
    }
}
