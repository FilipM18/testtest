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
            // Ak je overený používateľ, načítaj košík z databázy
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)
                ->with(['items.variant.product'])
                ->first();
            
            if (!$cart || count($cart->items) == 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }
        } else {
            // AK je hosť, načítaj košík zo session
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
        
        // Načítaj položky z košíka 
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

        DB::beginTransaction();

        try {
            // 1. Ulož adresu
            $address = new Address();
            $address->country = $validated['country'];
            $address->city = $validated['city'];
            $address->street = $validated['address'];
            $address->zip_code = $validated['postal_code'];
            
            // Ak je overený používateľ, nastav user_id čiže optional
            if (Auth::check()) {
                $address->user_id = Auth::id();
            }
            
            $address->save();
            
            // 2. SPočítaj celkovú cenu
            $subtotal = $items->sum(function($item) {
                return $item->variant->product->price * $item->quantity;
            });
            
            $shipping = $validated['delivery'] == 'standard' ? 5.00 : 16.00;
            $taxes = $subtotal * 0.1; 
            $total = $subtotal + $shipping + $taxes;
            
            // 3. Vytvori objednávku (order)
            $order = new Order();
            
            if (Auth::check()) {
                $order->user_id = Auth::id();
            }
            
            $order->address_id = $address->address_id;
            $order->total_amount = $total;
            $order->status = 'pending';
            $order->payment_method = $validated['payment'];
            $order->payment_status = 'pending';
            $order->save();
            
            // 4. Vytvor položky objednávky (orderitems)
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->order_id;
                $orderItem->variant_id = $item->variant->variant_id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $item->variant->product->price;
                $orderItem->save();
            }
            
            // 5. Vyprázdni košík
            if (Auth::check()) {
                $cart->items()->delete();
            } else {
                session()->forget('cart');
            }
            
            DB::commit();
            
            // Ak úspech, presmeruj na stránku s potvrdením objednávky
            return redirect()->route('order.confirmation', ['order' => $order->order_id])->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function showConfirmation($orderId)
    {
        $order = Order::with('orderItems.variant.product')->findOrFail($orderId);
        
        if (Auth::check() && $order->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }
        
        return view('checkout.Confirmation', compact('order'));
    }
}
