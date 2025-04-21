<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        //Načítanie produktu podľa ID 
        $product = Product::with(['brand', 'reviews.user', 'variants'])
            ->where('product_id', $id)
            ->first();
        
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }
        
        // Vypočítaj priemerné hodnotenie a počet recenzií
        $avgRating = $product->reviews->avg('rating') ?: 0;
        $reviewCount = $product->reviews->count();
        
        // Pridaj tieto hodnoty do objektu produktu
        $product->average_rating = $avgRating;
        $product->review_count = $reviewCount;

        $variants = ProductVariant::where('product_id', $id)->get();
        
        return view('ProductInfo', compact('product', 'variants'));
    }

    public function add(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login')->with('error', 'You must be logged in to add to cart.');
    }

    // Nájdi alebo vytvor prázdny košík pre používateľa
    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    // Skontroluj, či je produkt už existuje v košíku
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $request->product_id)
        ->where('variant_id', $request->variant_id)
        ->first();

    if ($cartItem) {
        // Zmeň množstvo 
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        // Vytvor nový záznam v košíku
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
            'quantity' => $request->quantity,
        ]);
    }

    return redirect()->back()->with('success', 'Product added to cart!');
}
}
