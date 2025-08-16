<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Kiểm tra nếu người dùng đã có trong cơ sở dữ liệu
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt("Vuhung@2206"),  // Mật khẩu tự động cho người dùng mới
                    'is_active' => 1,
                ]
            );
            // 🚨 Check user có bị khóa không
    if (! $user->is_active) {
        return redirect()->route('login')->withErrors([
            'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin!',
        ]);
    }
            $user->assignRole('user');

            // Đăng nhập người dùng
            Auth::login($user);

            return redirect()->to('/')->with('success', 'Đăng nhập thành công');
        } catch (\Exception $e) {
            return redirect()->to('/login')->withErrors('Đăng nhập thất bại');
        }
    }
}
