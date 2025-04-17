<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page with items.
     */
    public function index()
{
    // Get the user's cart with its items
        $cart = Cart::where('user_id', Auth::id())->first();
        
        // If no cart exists, create a new one
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'created_at' => now()
            ]);
        }
        
        // Eager load the cart items and their related data
        $cart->load('items.variant.product');
        
        // Pass the cart to the view
        return view('ShoppingCart', compact('cart'));
    }

    
    /**
     * Add an item to the cart.
     */
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
        
        // Check if item already exists in cart
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();
            
        if ($existingItem) {
            // Update quantity if item exists
            $existingItem->quantity += $validated['quantity'];
            $existingItem->save();
        } else {
            // Create new cart item
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
    
    /**
     * Update cart items.
     */
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
    
    /**
     * Remove an item from the cart.
     */
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
    
    /**
     * Add sample items to cart (for testing).
     */
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
