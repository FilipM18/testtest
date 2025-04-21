<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }
    
    public function boot()
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            
            if (Auth::check()) {
                // Authenticated user - get count from database
                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cartCount = $cart->items()->sum('quantity');
                }
            } else {
                // Guest user - get count from session
                $sessionCart = session()->get('cart', []);
                $cartCount = array_sum($sessionCart);
            }
            
            $view->with('cartCount', $cartCount);
        });
    }
}
