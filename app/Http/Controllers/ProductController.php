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

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'gender' => 'required|string',
        'type' => 'required|string',
        'brand_id' => 'required|exists:brands,brand_id',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Initialize image paths array
    $imagePaths = [];
    
    // Handle multiple image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            $imagePaths[] = $path; // Store only the path
        }
    }
    \Log::info('Image paths being saved:', $imagePaths);

    // Create product with image paths array
    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'gender' => $request->gender,
        'type' => $request->type,
        'brand_id' => $request->brand_id,
        'image_url' => $imagePaths,
        'active' => true,
    ]);
    
    return redirect()->route('products.index')->with('success', 'Product created successfully');
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'gender' => 'required|string',
        'type' => 'required|string',
        'brand_id' => 'required|exists:brands,brand_id',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    $imagePaths = is_array($product->image_url) ? $product->image_url : [];
    
    // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products', $filename, 'public');
            $imagePaths[] = $path;
        }
    }
    
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'gender' => $request->gender,
        'type' => $request->type,
        'brand_id' => $request->brand_id,
        'image_url' => $imagePaths,
    ]);
    
    return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }
}
