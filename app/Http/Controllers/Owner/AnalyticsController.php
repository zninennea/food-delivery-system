<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $monthlySales = $this->getMonthlySales($restaurant->id);
        $topSellingItems = $this->getTopSellingItems($restaurant->id);
        $salesStats = $this->getSalesStatistics($restaurant->id);
        $orderStatusDistribution = $this->getOrderStatusDistribution($restaurant->id);
        $reviewAnalytics = $this->getReviewAnalytics($restaurant->id);
        $peakHours = $this->getPeakHours($restaurant->id);
        $salesByCategory = $this->getSalesByCategory($restaurant->id);

        return view('owner.analytics.index', compact(
            'monthlySales',
            'topSellingItems',
            'salesStats',
            'orderStatusDistribution',
            'reviewAnalytics',
            'peakHours',
            'salesByCategory'
        ));
    }

    /**
     * Export Reports to CSV
     */
    public function export(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        $type = $request->query('type', 'monthly');
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"nani_{$type}_report_{$year}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($restaurant, $type, $year, $month) {
            $file = fopen('php://output', 'w');

            if ($type === 'monthly') {
                fputcsv($file, ['Month', 'Total Orders', 'Total Revenue']);

                $data = Order::where('restaurant_id', $restaurant->id)
                    ->whereYear('created_at', $year)
                    ->where('status', 'delivered')
                    ->selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(total_amount) as total')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                for ($m = 1; $m <= 12; $m++) {
                    $record = $data->firstWhere('month', $m);
                    fputcsv($file, [
                        date("F", mktime(0, 0, 0, $m, 1)),
                        $record ? $record->count : 0,
                        $record ? number_format($record->total, 2, '.', '') : '0.00'
                    ]);
                }
            } elseif ($type === 'daily') {
                fputcsv($file, ['Date', 'Time', 'Order Number', 'Customer', 'Items', 'Total Amount', 'Payment Method', 'Ref Number']);

                $orders = Order::with(['items.menuItem', 'customer'])
                    ->where('restaurant_id', $restaurant->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('status', 'delivered')
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($orders as $order) {
                    $itemsString = $order->items->map(function ($item) {
                        return $item->quantity . 'x ' . ($item->menuItem ? $item->menuItem->name : 'Deleted');
                    })->implode(', ');

                    $paymentMethod = ucwords(str_replace('_', ' ', $order->payment_method));
                    $refNumber = $order->payment_method === 'gcash' ? ($order->gcash_reference_number ?? 'N/A') : '-';

                    fputcsv($file, [
                        $order->created_at->format('Y-m-d'),
                        $order->created_at->format('H:i:s'),
                        $order->order_number,
                        $order->customer ? $order->customer->name : 'Guest',
                        $itemsString,
                        number_format($order->total_amount, 2, '.', ''),
                        $paymentMethod,
                        $refNumber
                    ]);
                }
            } elseif ($type === 'top_selling') {
                fputcsv($file, ['Rank', 'Item Name', 'Category', 'Total Quantity Sold']);

                $items = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                    ->where('orders.restaurant_id', $restaurant->id)
                    ->where('orders.status', 'delivered')
                    ->select('menu_items.name', 'menu_items.category', DB::raw('SUM(order_items.quantity) as total_quantity'))
                    ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.category')
                    ->orderByDesc('total_quantity')
                    ->get();

                foreach ($items as $index => $item) {
                    fputcsv($file, [
                        $index + 1,
                        $item->name,
                        ucfirst($item->category),
                        $item->total_quantity
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getMonthlySales($restaurantId)
    {
        $currentYear = date('Y');
        $salesData = Order::where('restaurant_id', $restaurantId)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlySales = array_fill(1, 12, 0);
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
            ->select('menu_items.name', 'menu_items.category', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.category')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
    }

    private function getSalesStatistics($restaurantId)
    {
        // Base query for delivered orders
        $query = Order::where('restaurant_id', $restaurantId)->where('status', 'delivered');

        // Today
        $today = (clone $query)->whereDate('created_at', today())->sum('total_amount');

        // This Month
        $thisMonth = (clone $query)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->sum('total_amount');

        // This Year
        $thisYear = (clone $query)
            ->whereYear('created_at', date('Y'))
            ->sum('total_amount');

        // Totals
        $totalOrders = (clone $query)->count();
        $avgOrderValue = $totalOrders > 0 ? $thisYear / $totalOrders : 0;

        return [
            'today' => $today,
            'this_month' => $thisMonth,
            'this_year' => $thisYear,
            'total_orders' => $totalOrders,
            'average_order_value' => $avgOrderValue
        ];
    }

    private function getOrderStatusDistribution($restaurantId)
    {
        return Order::where('restaurant_id', $restaurantId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    private function getReviewAnalytics($restaurantId)
    {
        $reviewsQuery = Review::whereHas('order', function ($q) use ($restaurantId) {
            $q->where('restaurant_id', $restaurantId);
        });

        return [
            'stats' => [
                'total_reviews' => (clone $reviewsQuery)->count(),
                'avg_rating' => (clone $reviewsQuery)->avg('restaurant_rating') ?? 0,
                'positive_reviews' => (clone $reviewsQuery)->where('restaurant_rating', '>=', 4)->count(),
                'reviews_this_month' => (clone $reviewsQuery)->whereMonth('created_at', now()->month)->count(),
            ]
        ];
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
            ->get();

        $formattedData = [];
        for ($m = 1; $m <= 12; $m++) {
            $record = $salesData->firstWhere('month', $m);
            $formattedData[] = ['month' => $m, 'total' => $record ? floatval($record->total) : 0];
        }
        return response()->json($formattedData);
    }
    private function getPeakHours($restaurantId)
    {
        // Group orders by hour of the day (0-23)
        return Order::where('restaurant_id', $restaurantId)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30)) // Last 30 days only for relevance
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
    }

    private function getSalesByCategory($restaurantId)
    {
        // Join orders -> order_items -> menu_items to sum total revenue per category
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->where('orders.restaurant_id', $restaurantId)
            ->where('orders.status', 'delivered')
            ->select('menu_items.category', DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'))
            ->groupBy('menu_items.category')
            ->orderByDesc('total_revenue')
            ->get();
    }
}
