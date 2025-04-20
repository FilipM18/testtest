<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)
            ->with(['items.variant.product']) 
            ->first();
        
        if (!$cart || count($cart->items) == 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
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

    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)
        ->with(['items.variant.product'])
        ->first();

    if (!$cart || count($cart->items) == 0) {
        return redirect()->route('checkout')->with('error', 'Your cart is empty');
    }

    // Begin transaction
    DB::beginTransaction();
    
    try {
        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->variant->product->price * $item->quantity;
        });
        $shipping = $validated['delivery'] == 'standard' ? 5.00 : 16.00;
        $taxes = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shipping + $taxes;

        // Create address record if needed
        $address = DB::table('addresses')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'country' => $validated['country'],
                'city' => $validated['city'],
                'street' => $validated['address'],
                'zip_code' => $validated['postal_code'],
            ]
        );

        // Create the order
        $order = new Order();
        $order->user_id = $user->id;
        $order->total_amount = $total;
        $order->status = 'pending';
        $order->payment_method = $validated['payment'];
        $order->payment_status = 'pending';
        $order->save();

        // Create order items
        foreach ($cart->items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->order_id;
            $orderItem->variant_id = $item->variant->variant_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->variant->product->price;
            $orderItem->save();
        }

        // Clear the cart
        $cart->items()->delete();
        
        DB::commit();
        
        // Just return a simple success message instead of redirecting to confirmation
        return back()->with('success', 'Order placed successfully! Order #' . $order->order_id);
    }
    catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}


    public function showConfirmation($orderId)
    {
        $order = Order::with('orderItems.variant.product')->findOrFail($orderId);
        
        // Ensure the order belongs to the current user
        if ($order->user_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }
        
        return view('checkout.confirmation', compact('order'));
    }
}
