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

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        $cartItems = Cart::with('menuItem')
            ->where('customer_id', $user->id)
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });
        // 1. Define the delivery fee (same as you did in the checkout method)
        $deliveryFee = 50.00;

        // 2. Pass 'deliveryFee' to the view using compact()
        return view('customer.cart', compact(
            'cartItems',
            'total',
            'deliveryFee',
            'profilePictureUrl'
        ));
    }

    public function add(Request $request, MenuItem $menuItem)
    {
        $user = Auth::user();

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'special_instructions' => 'nullable|string|max:500',
            'modify_order_id' => 'nullable|exists:orders,id' // Add this for modification
        ]);

        $cartItem = Cart::where('customer_id', $user->id)
            ->where('menu_item_id', $menuItem->id)
            ->first();

        if ($cartItem) {
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

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'message' => 'Item added to cart!'
            ]);
        }

        // For regular form submissions, redirect back
        return redirect()->back()->with('success', 'Item added to cart!');
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

    public function clear(Request $request)
    {
        $user = Auth::user();
        Cart::where('customer_id', $user->id)->delete();

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0
            ]);
        }

        // For regular requests, redirect back
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
    public function checkout()
    {
        $user = Auth::user();

        // Check if user has required profile data
        if (empty($user->delivery_address) || empty($user->phone)) {
            return redirect()->route('customer.profile.edit')
                ->with('error', 'Please complete your delivery information before checkout.');
        }

        $cartItems = Cart::with('menuItem')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty!');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });

        $deliveryFee = 50.00;

        return view('customer.checkout', compact('cartItems', 'total', 'deliveryFee'));
    }
}
