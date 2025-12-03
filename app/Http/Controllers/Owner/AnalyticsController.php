<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        // Existing analytics...
        $monthlySales = $this->getMonthlySales($restaurant->id);
        $topSellingItems = $this->getTopSellingItems($restaurant->id);
        $salesStats = $this->getSalesStatistics($restaurant->id);
        $orderStatusDistribution = $this->getOrderStatusDistribution($restaurant->id);

        // New review analytics
        $reviewAnalytics = $this->getReviewAnalytics($restaurant->id);

        return view('owner.analytics.index', compact(
            'monthlySales',
            'topSellingItems',
            'salesStats',
            'orderStatusDistribution',
            'reviewAnalytics'
        ));
    }

    private function getMonthlySales($restaurantId)
    {
        $currentYear = date('Y');

        $salesData = Order::where('restaurant_id', $restaurantId)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered') // Only count delivered orders
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize array with all months set to 0
        $monthlySales = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlySales[$month] = 0;
        }

        // Fill in actual sales data
        foreach ($salesData as $data) {
            $monthlySales[$data->month] = floatval($data->total);
        }

        return $monthlySales;
    }

    private function getTopSellingItems($restaurantId)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->where('orders.restaurant_id', $restaurantId)
            ->where('orders.status', 'delivered')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
    }

    private function getSalesStatistics($restaurantId)
    {
        $today = Order::where('restaurant_id', $restaurantId)
            ->whereDate('created_at', today())
            ->where('status', 'delivered')
            ->sum('total_amount');

        $thisMonth = Order::where('restaurant_id', $restaurantId)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->where('status', 'delivered')
            ->sum('total_amount');

        $thisYear = Order::where('restaurant_id', $restaurantId)
            ->whereYear('created_at', date('Y'))
            ->where('status', 'delivered')
            ->sum('total_amount');

        $totalOrders = Order::where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $thisYear / $totalOrders : 0;

        return [
            'today' => $today,
            'this_month' => $thisMonth,
            'this_year' => $thisYear,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
        ];
    }

    private function getOrderStatusDistribution($restaurantId)
    {
        return Order::where('restaurant_id', $restaurantId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->status => $item->count
                ];
            })
            ->toArray();
    }

    public function getSalesData(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $year = $request->get('year', date('Y'));

        $salesData = Order::where('restaurant_id', $restaurant->id)
            ->whereYear('created_at', $year)
            ->where('status', 'delivered')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Format data for chart
        $formattedData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthData = $salesData->firstWhere('month', $month);
            $formattedData[] = [
                'month' => $month,
                'total' => $monthData ? floatval($monthData->total) : 0
            ];
        }

        return response()->json($formattedData);
    }

    // New method to get daily sales for the current month
    public function getDailySales(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $dailySales = Order::where('restaurant_id', $restaurant->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('status', 'delivered')
            ->selectRaw('DAY(created_at) as day, SUM(total_amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json($dailySales);
    }
    // Add this method to your AnalyticsController.php
    private function getReviewAnalytics($restaurantId)
    {
        // Monthly review count
        $monthlyReviews = Review::whereHas('order', function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        })
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize array with all months set to 0
        $monthlyReviewCounts = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyReviewCounts[$month] = 0;
        }

        // Fill in actual review data
        foreach ($monthlyReviews as $data) {
            $monthlyReviewCounts[$data->month] = intval($data->count);
        }

        // Average rating over time
        $monthlyAvgRatings = Review::whereHas('order', function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        })
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, AVG(restaurant_rating) as avg_rating')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize array with all months set to 0
        $monthlyAvgRatingData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyAvgRatingData[$month] = 0;
        }

        // Fill in actual rating data
        foreach ($monthlyAvgRatings as $data) {
            $monthlyAvgRatingData[$month] = floatval($data->avg_rating);
        }

        // Get review statistics
        $reviewStats = [
            'total_reviews' => Review::whereHas('order', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            })->count(),

            'avg_rating' => Review::whereHas('order', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            })->avg('restaurant_rating'),

            'positive_reviews' => Review::whereHas('order', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            })->where('restaurant_rating', '>=', 4)->count(),

            'reviews_this_month' => Review::whereHas('order', function ($query) use ($restaurantId) {
                $query->where('restaurant_id', $restaurantId);
            })->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return [
            'monthly_reviews' => $monthlyReviewCounts,
            'monthly_avg_ratings' => $monthlyAvgRatingData,
            'stats' => $reviewStats
        ];
    }
}
