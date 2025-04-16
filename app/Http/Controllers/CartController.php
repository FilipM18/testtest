<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart_items', []);
        return view('ShoppingCart', ['cartItems' => collect($cartItems)]);
    }
    
    public function add(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
            'size' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'status' => 'nullable|string',
            'status_message' => 'nullable|string',
        ]);
        
        $cartItems = session()->get('cart_items', []);
        $validated['id'] = uniqid(); // Generate a unique ID
        $cartItems[] = $validated;
        session()->put('cart_items', $cartItems);
        
        return redirect()->route('cart.index')->with('success', 'Item added to cart');
    }
    
    public function update(Request $request)
    {
        $cartItems = session()->get('cart_items', []);
        
        if ($request->has('items')) {
            foreach ($request->items as $id => $details) {
                foreach ($cartItems as $key => $item) {
                    if ($item['id'] == $id) {
                        $cartItems[$key]['quantity'] = $details['quantity'];
                    }
                }
            }
        }
        
        session()->put('cart_items', $cartItems);
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
    
    public function remove($id)
    {
        $cartItems = session()->get('cart_items', []);
        
        $cartItems = array_filter($cartItems, function($item) use ($id) {
            return $item['id'] != $id;
        });
        
        session()->put('cart_items', array_values($cartItems));
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
    
    public function populate()
    {
        $sampleItems = [
            [
                'id' => uniqid(),
                'name' => 'Basic Tee',
                'color' => 'Blue',
                'size' => 'Large',
                'price' => 32.00,
                'image' => 'images/blueGant.png',
                'quantity' => 1,
                'status' => 'in-stock',
                'status_message' => 'In stock'
            ],
            [
                'id' => uniqid(),
                'name' => 'Basic Tee',
                'color' => 'White',
                'size' => 'Large',
                'price' => 32.00,
                'image' => 'images/whiteGant.avif',
                'quantity' => 1,
                'status' => 'shipping',
                'status_message' => 'Ships in 3–4 weeks'
            ],
            [
                'id' => uniqid(),
                'name' => 'Basic Tee',
                'color' => 'Green',
                'size' => 'Large',
                'price' => 35.00,
                'image' => 'images/greenGant.avif',
                'quantity' => 1,
                'status' => 'in-stock',
                'status_message' => 'In stock'
            ]
        ];
        
        session()->put('cart_items', $sampleItems);
        return redirect()->route('cart.index')->with('success', 'Sample items added to cart');
    }
}
