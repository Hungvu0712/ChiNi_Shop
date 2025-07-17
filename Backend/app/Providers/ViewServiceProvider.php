<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $colorMap = [
                    'red' => 'red',
                    'màu đỏ' => 'red',
                    'blue' => 'blue',
                    'màu xanh' => 'blue',
                    'green' => 'green',
                    'màu xanh lá' => 'green',
                    'black' => 'black',
                    'đen' => 'black',
                    'white' => 'white',
                    'trắng' => 'white',
                    'yellow' => 'yellow',
                    'vàng' => 'yellow',
                    'xám' => 'gray',
                    'gray' => 'gray',
                    // ... thêm tùy ý
                ];
                $user_id = Auth::id();
                $cart = Cart::with([
                    'cartitems',
                    'cartitems.product',
                    'cartitems.productvariant.attributes'
                ])->where('user_id', $user_id)->first();

                $sub_total = 0;

                if ($cart && $cart->cartitems) {
                    foreach ($cart->cartitems as $item) {
                        if ($item->productvariant) {
                            $sub_total += $item->productvariant->price * $item->quantity;
                        }
                    }
                }
                $countCart = isset($cart['cartitems']) ?count($cart['cartitems']):0;
                $view->with(compact('cart', 'sub_total','colorMap','countCart'));
            } else {
                $view->with(['cart' => null, 'sub_total' => 0]);
            }
        });
    }
}
