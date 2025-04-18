<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    
    public function index(Request $request)
    {
        // Ak existuje, ziskaj ho
        $cart = \App\Models\Cart::where('user_id', Auth::id())->first();
        // Ak neexistuje, vytvor novy
        if (!$cart) {
            $cart = \App\Models\Cart::create([
                'user_id' => Auth::id(),
                'created_at' => now()
            ]);
        }
        // Nacitaj polozky kosika
        $cart->load('items.variant.product');
        return view('CheckoutPage', compact('cart'));
    }

}