<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        $cartItems = $cart ? CartItem::where('cart_id', $cart->cart_id)->get() : collect();
        
        // Ak existuje, ziskaj ho
        $cart = Cart::where('user_id', Auth::id())->first();
        
        // Ak neexistuje, vytvor novy
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'created_at' => now()
            ]);
        }
        
        // Nacitaj polozky kosika
        $cart->load('items.variant.product');
        
        return view('ShoppingCart', compact('cart' , 'cartItems'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to add to cart.');
        }

        // 1. Find or create the user's cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // 2. Check if this variant is already in the cart
        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('variant_id', $request->variant_id)
            ->where('user_id', $user->id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Insert new cart item
            CartItem::create([
                'cart_id'   => $cart->cart_id,
                'variant_id'=> $request->variant_id,
                'quantity'  => $request->quantity,
                'user_id'   => $user->id,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }
    
    public function update(Request $request)
    {
        $items = $request->input('items', []);
        
        foreach ($items as $id => $item) {
            $cartItem = CartItem::where('cart_item_id', $id)
                ->where('user_id', Auth::id())
                ->first();
                
            if ($cartItem) {
                if (isset($item['quantity']) && $item['quantity'] > 0) {
                    $cartItem->quantity = $item['quantity'];
                    $cartItem->save();
                } elseif (isset($item['quantity']) && $item['quantity'] <= 0) {
                    $cartItem->delete();
                }
            }
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }
    
    public function remove($id)
    {
        $cartItem = CartItem::where('cart_item_id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
        }
        
        return redirect()->route('cart.index')->with('error', 'Item not found!');
    }

}
