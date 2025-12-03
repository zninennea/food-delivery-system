<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check role in controller instead of middleware
        $user = Auth::user();
        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $restaurant = Restaurant::where('owner_id', $user->id)->firstOrFail();

        $stats = [
            'today_orders' => Order::where('restaurant_id', $restaurant->id)
                ->whereDate('created_at', today())
                ->count(),
            'active_orders' => Order::where('restaurant_id', $restaurant->id)
                ->whereIn('status', ['preparing', 'ready', 'on_the_way'])
                ->count(),
            'pending_orders' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'pending')
                ->count(),
            'revenue' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'delivered')
                ->sum('total_amount'),
            'average_rating' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->avg('restaurant_rating'),
            'total_reviews' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->count()
        ];

        $activeOrders = Order::with(['rider', 'customer'])
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('status', ['preparing', 'ready', 'on_the_way'])
            ->latest()
            ->get();

        // Get recent reviews
        $recentReviews = Review::with(['customer', 'order'])
            ->whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
            ->latest()
            ->limit(3)
            ->get();

        return view('owner.dashboard', compact('stats', 'activeOrders', 'restaurant', 'recentReviews'));
    }
}
