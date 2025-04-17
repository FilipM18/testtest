<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartControllerDB extends Controller
{
    /**
     * Display the cart page with items.
     */
    public function index()
    {
        // Get or create the user's cart
        $cart = $this->getOrCreateCart();
        
        // Eager load cart items with their variants and products
        $cart->load(['items.variant.product']);
        
        return view('cart', ['cart' => $cart]);
    }
    
    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|integer|exists:productvariants,variant_id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        // Get or create the user's cart
        $cart = $this->getOrCreateCart();
        
        // Check if the variant exists in the cart
        $existingItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('variant_id', $validated['variant_id'])
            ->first();
            
        if ($existingItem) {
            // Update quantity if item exists
            $existingItem->quantity += $validated['quantity'];
            $existingItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'variant_id' => $validated['variant_id'],
                'quantity' => $validated['quantity']
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
        $cart = $this->getOrCreateCart();
        
        foreach ($items as $cartItemId => $item) {
            $cartItem = CartItem::where('cart_item_id', $cartItemId)
                ->where('cart_id', $cart->cart_id)
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
        $cart = $this->getOrCreateCart();
        
        $cartItem = CartItem::where('cart_item_id', $id)
            ->where('cart_id', $cart->cart_id)
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
        $cart = $this->getOrCreateCart();
        
        // Get some random variants to add to cart
        $variants = ProductVariant::inRandomOrder()->limit(2)->get();
        
        foreach ($variants as $variant) {
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'variant_id' => $variant->variant_id,
                'quantity' => rand(1, 3)
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Sample items added to cart!');
    }
    
    /**
     * Get the current user's cart or create a new one.
     */
    private function getOrCreateCart()
    {
        $userId = Auth::id();
        
        $cart = Cart::where('user_id', $userId)->first();
        
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'created_at' => now()
            ]);
        }
        
        return $cart;
    }
}
