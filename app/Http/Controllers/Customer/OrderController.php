<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('menuItem')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
            'special_instructions' => 'nullable|string|max:1000'
        ]);

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });

        $order = Order::create([
            'restaurant_id' => 1, // Assuming one restaurant
            'customer_id' => $user->id,
            'order_number' => 'NANI' . Str::random(8),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'delivery_address' => $request->delivery_address,
            'customer_phone' => $request->customer_phone,
            'special_instructions' => $request->special_instructions
        ]);

        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $cartItem->menu_item_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->menuItem->price
            ]);
        }

        // Clear cart
        Cart::where('customer_id', $user->id)->delete();

        return redirect()->route('customer.track-order', $order)
            ->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        
        if ($order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('items.menuItem', 'rider');
        
        return view('customer.order-details', compact('order'));
    }
}