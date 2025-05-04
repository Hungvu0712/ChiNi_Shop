<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
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
