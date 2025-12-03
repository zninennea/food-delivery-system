<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MenuItem;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $restaurant = Restaurant::first();

        // Featured items
        $featuredItems = MenuItem::limit(6)->get();

        // All menu items grouped by category
        $menuItemsByCategory = MenuItem::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        // Recent orders for this customer
        $recentOrders = Order::where('customer_id', $user->id)
            ->with(['items.menuItem'])
            ->latest()
            ->limit(3)
            ->get();

        // Get recent reviews from the database
        $recentReviews = Review::with(['customer', 'order.items.menuItem'])
            ->whereHas('order', function ($query) use ($user) {
                $query->where('customer_id', $user->id);
            })
            ->latest()
            ->limit(5)
            ->get();

        // Calculate average restaurant rating for display
        $averageRating = Review::avg('restaurant_rating');
        $totalReviews = Review::count();

        // Cart count
        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return view('customer.dashboard', compact(
            'featuredItems',
            'menuItemsByCategory',
            'recentOrders',
            'recentReviews',
            'cartCount',
            'restaurant',
            'averageRating',
            'totalReviews'

        ));
    }

    public function menu(Request $request)
    {
        $user = Auth::user();
        $restaurant = Restaurant::first();

        // Check if we're in modification mode
        $modifyOrderId = $request->get('modify_order');
        $modifyOrder = null;

        if ($modifyOrderId) {
            $modifyOrder = Order::where('id', $modifyOrderId)
                ->where('customer_id', $user->id)
                ->first();
        }

        // All menu items grouped by category
        $menuItemsByCategory = MenuItem::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        return view('customer.menu', compact(
            'menuItemsByCategory',
            'cartCount',
            'restaurant',
            'modifyOrder'
        ));
    }

    public function showMenuItem(MenuItem $menuItem)
    {
        $user = Auth::user();
        $cartCount = Cart::where('customer_id', $user->id)->sum('quantity');

        // Get similar items
        $similarItems = MenuItem::where('category', $menuItem->category)
            ->where('id', '!=', $menuItem->id)
            ->limit(4)
            ->get();

        return view('customer.menu-item', compact('menuItem', 'similarItems', 'cartCount'));
    }
}
