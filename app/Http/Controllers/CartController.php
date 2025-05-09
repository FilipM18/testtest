<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Autentifikacia 
            $cart = Cart::where('user_id', Auth::id())->first();
            $cartItems = $cart ? CartItem::where('cart_id', $cart->cart_id)->get() : collect();
            
            if ($cart) {
                $cart->load('items.variant.product');
            } else {
                $cart = (object)['items' => collect()];
            }
        } else {
            // Guest- session
            $sessionCart = session()->get('cart', []);
            $cartItems = collect();
            
            foreach ($sessionCart as $variantId => $quantity) {
                $variant = ProductVariant::with('product')->find($variantId);
                if ($variant) {
                    $cartItems->push((object)[
                        'cart_item_id' => $variantId, // Use variant_id as cart_item_id for guests
                        'variant' => $variant,
                        'quantity' => $quantity
                    ]);
                }
            }

            $cart = (object)['items' => $cartItems];
        }
        
        return view('ShoppingCart', compact('cart', 'cartItems'));
    }
    public function add(Request $request)
    {
        $variantId = $request->variant_id;
        $quantity = $request->quantity ?? 1;
        
        if (Auth::check()) {
            // Authenticated user - store in database
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            $cartItem = CartItem::where('cart_id', $cart->cart_id)
                ->where('variant_id', $variantId)
                ->where('user_id', $user->id)
                ->first();
                
            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $cart->cart_id,
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'user_id' => $user->id,
                ]);
            }
        } else {
            // Guest user - store in session
            $cart = session()->get('cart', []);
            
            // Ak existuje zmeň množstvo
            if (isset($cart[$variantId])) {
                $cart[$variantId] += $quantity;
            } else {
                $cart[$variantId] = $quantity;
            }
            
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $items = $request->input('items', []);
        
        if (Auth::check()) {
            // Authenticated user - update database
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
        } else {
            // Guest user - update session
            $cart = session()->get('cart', []);
            
            foreach ($items as $id => $item) {
                if (isset($item['quantity']) && $item['quantity'] > 0) {
                    $cart[$id] = $item['quantity'];
                } elseif (isset($item['quantity']) && $item['quantity'] <= 0) {
                    unset($cart[$id]);
                }
            }
            
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove($id)
    {
        if (Auth::check()) {
            // Authenticated user - remove from database
            $cartItem = CartItem::where('cart_item_id', $id)
                ->where('user_id', Auth::id())
                ->first();
                
            if ($cartItem) {
                $cartItem->delete();
                return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
            }
        } else {
            // Guest user - remove from session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
            }
        }
        
        return redirect()->route('cart.index')->with('error', 'Item not found!');
    }
}
