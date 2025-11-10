<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::with('menuItem')
            ->where('customer_id', $user->id)
            ->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });

        return view('customer.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request, MenuItem $menuItem)
    {
        $user = Auth::user();
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'special_instructions' => 'nullable|string|max:500'
        ]);

        $cartItem = Cart::where('customer_id', $user->id)
            ->where('menu_item_id', $menuItem->id)
            ->first();

        if ($cartItem) {
            // Use update method correctly
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
                'special_instructions' => $request->special_instructions ?? $cartItem->special_instructions
            ]);
        } else {
            Cart::create([
                'customer_id' => $user->id,
                'menu_item_id' => $menuItem->id,
                'quantity' => $request->quantity,
                'special_instructions' => $request->special_instructions
            ]);
        }

        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'message' => 'Item added to cart!'
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        $user = Auth::user();
        
        // Check if cart belongs to user
        if ($cart->customer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:0|max:10', // Allow 0 to remove
            'special_instructions' => 'nullable|string|max:500'
        ]);

        if ($request->quantity == 0) {
            $cart->delete();
            $message = 'Item removed from cart!';
        } else {
            $cart->update([
                'quantity' => $request->quantity,
                'special_instructions' => $request->special_instructions
            ]);
            $message = 'Cart updated!';
        }

        $cartTotal = $this->calculateCartTotal($user);
        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
            'message' => $message
        ]);
    }

    public function destroy(Cart $cart)
    {
        $user = Auth::user();
        
        // Check if cart belongs to user
        if ($cart->customer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'message' => 'Item removed from cart!'
        ]);
    }

    public function clear()
    {
        $user = Auth::user();
        Cart::where('customer_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Cart cleared!');
    }

    private function calculateCartTotal($user)
    {
        return Cart::where('customer_id', $user->id)
            ->with('menuItem')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * $item->menuItem->price;
            });
    }
}