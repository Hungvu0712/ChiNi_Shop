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
        // Chia sẻ colorMap từ file config cho tất cả các view
        // Bất cứ view nào cũng có thể gọi biến $colorMap
        View::share('colorMap', config('custom.color_map', []));

        // Composer để xử lý dữ liệu cho người dùng đã đăng nhập (giỏ hàng,...)
        View::composer('*', function ($view) {
            if (Auth::check()) {
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

                // ✅ SỬA LỖI TẠI ĐÂY
                // Kiểm tra xem $cart và $cart->cartitems có tồn tại không trước khi đếm
                $countCart = ($cart && $cart->cartitems) ? $cart->cartitems->count() : 0;

                $view->with(compact('cart', 'sub_total', 'countCart'));
            } else {
                $view->with(['cart' => null, 'sub_total' => 0, 'countCart' => 0]);
            }
        });
    }
}
