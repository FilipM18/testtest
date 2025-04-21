<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Authenticated user - get cart from database
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
                ->with(['items.variant.product'])
                ->first();
            
            if (!$cart || count($cart->items) == 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            // Guest user - get cart from session
            $sessionCart = session()->get('cart', []);
            
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $cartItems = collect();
            foreach ($sessionCart as $variantId => $quantity) {
                $variant = ProductVariant::with('product')->find($variantId);
                if ($variant) {
                    $cartItems->push((object)[
                        'cart_item_id' => $variantId,
                        'variant' => $variant,
                        'quantity' => $quantity
                    ]);
                }
            }
            
            $cart = (object)['items' => $cartItems];
        }
        
        return view('CheckoutPage', compact('cart'));
    }
    
    public function completeOrder(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'apartment' => 'nullable',
            'city' => 'required',
            'country' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'delivery' => 'required|in:standard,express',
            'payment' => 'required|in:creditCard,paypal,transfer',
        ]);
        
        // Get cart items (for authenticated or guest)
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
                ->with(['items.variant.product'])
                ->first();
                
            if (!$cart || count($cart->items) == 0) {
                return redirect()->route('checkout')->with('error', 'Your cart is empty');
            }
            
            $items = $cart->items;
        } else {
            // Guest user - get cart from session
            $sessionCart = session()->get('cart', []);
            
            if (empty($sessionCart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
            
            $items = collect();
            foreach ($sessionCart as $variantId => $quantity) {
                $variant = ProductVariant::with('product')->find($variantId);
                if ($variant) {
                    $items->push((object)[
                        'variant' => $variant,
                        'quantity' => $quantity
                    ]);
                }
            }
        }
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // 1. Save address (for both guests and users)
            $address = new Address();
            $address->country = $validated['country'];
            $address->city = $validated['city'];
            $address->street = $validated['address'];
            $address->zip_code = $validated['postal_code'];
            
            // Set user_id if authenticated
            if (Auth::check()) {
                $address->user_id = Auth::id();
            }
            
            $address->save();
            
            // 2. Calculate totals
            $subtotal = $items->sum(function($item) {
                return $item->variant->product->price * $item->quantity;
            });
            
            $shipping = $validated['delivery'] == 'standard' ? 5.00 : 16.00;
            $taxes = $subtotal * 0.1; // 10% tax
            $total = $subtotal + $shipping + $taxes;
            
            // 3. Create the order
            $order = new Order();
            
            // Set user_id if authenticated
            if (Auth::check()) {
                $order->user_id = Auth::id();
            }
            
            // Link to the address we just created
            $order->address_id = $address->address_id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->payment_method = $validated['payment'];
            $order->payment_status = 'pending';
            $order->save();
            
            // 4. Create order items
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->order_id;
                $orderItem->variant_id = $item->variant->variant_id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $item->variant->product->price;
                $orderItem->save();
            }
            
            // 5. Clear the cart
            if (Auth::check()) {
                $cart->items()->delete();
            } else {
                session()->forget('cart');
            }
            
            DB::commit();
            
            // Return success message
            return redirect()->route('order.confirmation', ['order' => $order->order_id])->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function showConfirmation($orderId)
    {
        $order = Order::with('orderItems.variant.product')->findOrFail($orderId);
        
        // For guest orders, don't check user_id
        if (Auth::check() && $order->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }
        
        return view('checkout.Confirmation', compact('order'));
    }
}
