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
        View::composer('client.partials.header', function ($view) {
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

                $view->with(compact('cart', 'sub_total'));
            } else {
                $view->with(['cart' => null, 'sub_total' => 0]);
            }
        });
    }
}
