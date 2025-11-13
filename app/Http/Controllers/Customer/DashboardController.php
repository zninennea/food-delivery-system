<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MenuItem;
use App\Models\Cart;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Featured items - simple heuristic
        $featuredItems = MenuItem::limit(9)->get();

        // All menu items
        $menuItems = MenuItem::orderBy('name')->get();

        // Recent orders for this customer
        $recentOrders = Order::where('customer_id', $user->id)->latest()->limit(5)->get();

        // Cart count
        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return view('customer.dashboard', compact('featuredItems', 'menuItems', 'recentOrders', 'cartCount'));
    }
}