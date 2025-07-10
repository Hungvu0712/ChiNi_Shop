<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        $menus = Menu::where('parent_id', null)->orderBy('order_index', 'asc')->get();
        return view('auth.change-password', compact('menus'));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

    // Kiểm tra mật khẩu cũ
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])->withInput();
    }

    // Cập nhật mật khẩu mới
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('profile.show')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
