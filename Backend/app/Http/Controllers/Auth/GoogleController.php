<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Carbon;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // $user = Socialite::driver('google')->user();

        // $user->token;

        try{
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->email
                ],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => encrypt('123456dummy'),
                    'email_verified_at' => now(),
                ]
            );
            auth()->login($user, true);
            return redirect()->to('/')->with('success', 'Đăng nhập thành công');
        }catch(\Exception $e){
            return redirect()->to('/login')->withErrors('Đăng nhập thất bại');
        }
    }
}
