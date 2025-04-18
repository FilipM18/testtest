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
        
        return view('ShoppingCart', compact('cart'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|string',
            'status' => 'nullable|string'
        ]);
        
        // Skoontroluj, ci uz polozka existuje v kosiku
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();
            
        if ($existingItem) {
            // Zmen mnozstvo existujucej polozky
            $existingItem->quantity += $validated['quantity'];
            $existingItem->save();
        } else {
            // Vytvori novu polozku
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'image' => $validated['image'] ?? null,
                'status' => $validated['status'] ?? 'in-stock'
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item added to cart successfully!');
    }
    
    public function update(Request $request)
    {
        $items = $request->input('items', []);
        
        foreach ($items as $id => $item) {
            $cartItem = CartItem::where('id', $id)
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
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
        }
        
        return redirect()->route('cart.index')->with('error', 'Item not found!');
    }
    
    // Po testovaní treba vymazať
    public function populate()
    {
        $sampleItems = [
            [
                'product_id' => 1,
                'name' => 'Summer Dress',
                'price' => 49.99,
                'quantity' => 1,
                'image' => 'images/products/dress1.jpg',
                'status' => 'in-stock'
            ],
            [
                'product_id' => 2,
                'name' => 'Casual Jeans',
                'price' => 39.99,
                'quantity' => 1,
                'image' => 'images/products/jeans1.jpg',
                'status' => 'shipping'
            ]
        ];
        
        foreach ($sampleItems as $item) {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'status' => $item['status']
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Sample items added to cart!');
    }
}
