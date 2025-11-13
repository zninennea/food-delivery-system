<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\OrderItem; // Add this import
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        
        // Monthly sales data for the current year
        $monthlySales = $this->getMonthlySales($restaurant->id);
        
        // Top selling menu items
        $topSellingItems = $this->getTopSellingItems($restaurant->id);
        
        // Sales statistics
        $salesStats = $this->getSalesStatistics($restaurant->id);
        
        // Order status distribution
        $orderStatusDistribution = $this->getOrderStatusDistribution($restaurant->id);

        return view('owner.analytics.index', compact(
            'monthlySales', 
            'topSellingItems', 
            'salesStats',
            'orderStatusDistribution'
        ));
    }

    private function getMonthlySales($restaurantId)
    {
        return Order::where('restaurant_id', $restaurantId)
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->month => $item->total
                ];
            })
            ->toArray();
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

        return [
            'today' => $today,
            'this_month' => $thisMonth,
            'this_year' => $thisYear,
            'total_orders' => $totalOrders,
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

        return response()->json($salesData);
    }
}