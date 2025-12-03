<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        // Get ALL reviews for this restaurant
        $reviews = Review::with(['customer', 'order.items.menuItem'])
            ->whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })
            ->latest()
            ->paginate(10);

        // Calculate statistics
        $averageRating = Review::whereHas('order', function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        })->avg('restaurant_rating');

        $totalReviews = Review::whereHas('order', function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        })->count();

        $ratingDistribution = Review::whereHas('order', function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        })
            ->selectRaw('restaurant_rating, COUNT(*) as count')
            ->groupBy('restaurant_rating')
            ->orderBy('restaurant_rating', 'desc')
            ->get();

        // Get popular menu items based on reviews
        $popularMenuItems = $this->getPopularMenuItems($restaurant->id);

        return view('owner.reviews.index', compact(
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'popularMenuItems',
            'restaurant'
        ));
    }

    private function getPopularMenuItems($restaurantId)
    {
        // Get menu items with reviews through order items
        return MenuItem::where('restaurant_id', $restaurantId)
            ->whereHas('orderItems')
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(10)
            ->get();
    }

    public function getReviewStats()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $stats = [
            'total_reviews' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->count(),

            'average_rating' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->avg('restaurant_rating'),

            'reviews_this_month' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),

            'positive_reviews' => Review::whereHas('order', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->where('restaurant_rating', '>=', 4)->count(),
        ];

        return response()->json($stats);
    }
}
