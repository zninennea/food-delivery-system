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
    /**
     * Export Order Data (Quarterly/Monthly/Detailed)
     */
    public function exportOrderData(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        $type = $request->query('type', 'monthly');
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));

        $filename = "nani_orders_{$type}";
        if ($type === 'monthly' || $type === 'detailed') {
            $filename .= "_{$year}_{$month}";
        } else {
            $filename .= "_{$year}";
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($restaurant, $type, $year, $month) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 compatibility
            fwrite($file, "\xEF\xBB\xBF");

            if ($type === 'quarterly') {
                $this->exportQuarterlyData($file, $restaurant, $year);
            } elseif ($type === 'monthly') {
                $this->exportMonthlyData($file, $restaurant, $year, $month);
            } elseif ($type === 'detailed') {
                $this->exportDetailedData($file, $restaurant, $year, $month);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Item Analytics
     */
    public function exportItemAnalytics(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="nani_menu_performance_' . date('Y_m_d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($restaurant) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            // Header
            fputcsv($file, ['Menu Item Performance Report', 'Nani Restaurant', 'Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, ['Note: All personally identifiable information has been removed']);
            fputcsv($file, []);

            // Item data
            fputcsv($file, [
                'item_id',
                'item_name',
                'category',
                'price',
                'total_quantity_sold',
                'total_revenue',
                'orders_count',
                'avg_quantity_per_order',
                'most_common_order_day',
                'most_common_order_hour',
                'last_sold_date'
            ]);

            $items = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
                ->where('orders.restaurant_id', $restaurant->id)
                ->where('orders.status', 'delivered')
                ->select(
                    'menu_items.id',
                    'menu_items.name',
                    'menu_items.category',
                    'menu_items.price',
                    DB::raw('SUM(order_items.quantity) as total_quantity'),
                    DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue'),
                    DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                    DB::raw('AVG(order_items.quantity) as avg_quantity'),
                    DB::raw('DAYNAME(orders.created_at) as popular_day'),
                    DB::raw('HOUR(orders.created_at) as popular_hour'),
                    DB::raw('MAX(orders.created_at) as last_sold')
                )
                ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.category', 'menu_items.price')
                ->orderByDesc('total_revenue')
                ->get();

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->category,
                    number_format($item->price, 2, '.', ''),
                    $item->total_quantity,
                    number_format($item->total_revenue, 2, '.', ''),
                    $item->order_count,
                    round($item->avg_quantity, 2),
                    $item->popular_day ?? 'N/A',
                    $item->popular_hour ? $item->popular_hour . ':00' : 'N/A',
                    $item->last_sold ? date('Y-m-d', strtotime($item->last_sold)) : 'N/A'
                ]);
            }

            // Add data dictionary
            fputcsv($file, []);
            fputcsv($file, ['DATA DICTIONARY']);
            fputcsv($file, []);
            fputcsv($file, ['Field', 'Description', 'Data Type']);
            fputcsv($file, ['item_id', 'Unique identifier for menu item', 'Integer']);
            fputcsv($file, ['item_name', 'Name of the menu item', 'String']);
            fputcsv($file, ['category', 'Category of the menu item', 'String']);
            fputcsv($file, ['price', 'Current price of the item', 'Decimal (₱)']);
            fputcsv($file, ['total_quantity_sold', 'Total number sold', 'Integer']);
            fputcsv($file, ['total_revenue', 'Total revenue generated', 'Decimal (₱)']);
            fputcsv($file, ['orders_count', 'Number of orders containing this item', 'Integer']);
            fputcsv($file, ['avg_quantity_per_order', 'Average quantity per order', 'Decimal']);
            fputcsv($file, ['most_common_order_day', 'Day with most orders for this item', 'String']);
            fputcsv($file, ['most_common_order_hour', 'Hour with most orders for this item', 'String']);
            fputcsv($file, ['last_sold_date', 'Most recent sale date', 'Date']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper methods for exportOrderData
    private function exportQuarterlyData($file, $restaurant, $year)
    {
        fputcsv($file, ['Quarterly Order Data Report', 'Nani Restaurant', 'Year: ' . $year]);
        fputcsv($file, ['Note: All personally identifiable information has been removed']);
        fputcsv($file, []);
        fputcsv($file, [
            'Quarter',
            'Total Orders',
            'Delivered Orders',
            'Cancelled Orders',
            'Total Revenue (₱)',
            'Avg Order Value (₱)',
            'Avg Preparation Time (mins)',
            'Customer Satisfaction (%)'
        ]);

        $quarters = [
            1 => ['Jan', 'Feb', 'Mar'],
            2 => ['Apr', 'May', 'Jun'],
            3 => ['Jul', 'Aug', 'Sep'],
            4 => ['Oct', 'Nov', 'Dec']
        ];

        foreach ($quarters as $quarter => $months) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $startMonth + 2;

            $data = Order::where('restaurant_id', $restaurant->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->selectRaw('
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(CASE WHEN status = "delivered" THEN total_amount ELSE 0 END) as revenue,
                AVG(CASE WHEN status = "delivered" THEN total_amount ELSE NULL END) as avg_order_value,
                AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_prep_time
            ')
                ->first();

            // Get average rating for the quarter
            $avgRating = Review::whereHas('order', function ($q) use ($restaurant, $year, $startMonth, $endMonth) {
                $q->where('restaurant_id', $restaurant->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', '>=', $startMonth)
                    ->whereMonth('created_at', '<=', $endMonth);
            })
                ->avg('restaurant_rating') ?? 0;

            $satisfaction = $avgRating > 0 ? ($avgRating / 5) * 100 : 0;

            fputcsv($file, [
                'Q' . $quarter . ' ' . implode('-', $months),
                $data->total_orders ?? 0,
                $data->delivered_orders ?? 0,
                $data->cancelled_orders ?? 0,
                number_format($data->revenue ?? 0, 2, '.', ''),
                number_format($data->avg_order_value ?? 0, 2, '.', ''),
                $data->avg_prep_time ? round($data->avg_prep_time, 1) : 0,
                round($satisfaction, 1) . '%'
            ]);
        }

        // Add summary
        fputcsv($file, []);
        fputcsv($file, ['DATA DICTIONARY:']);
        fputcsv($file, ['Column', 'Description', 'Data Type', 'Example']);
        fputcsv($file, ['Quarter', 'Quarter of the year', 'String', 'Q1 Jan-Mar']);
        fputcsv($file, ['Total Orders', 'All orders received', 'Integer', '150']);
        fputcsv($file, ['Delivered Orders', 'Successfully delivered orders', 'Integer', '140']);
        fputcsv($file, ['Cancelled Orders', 'Cancelled by customer or restaurant', 'Integer', '10']);
        fputcsv($file, ['Total Revenue', 'Total amount from delivered orders', 'Decimal', '45000.50']);
        fputcsv($file, ['Avg Order Value', 'Average revenue per delivered order', 'Decimal', '321.43']);
        fputcsv($file, ['Avg Preparation Time', 'Average time from order to completion', 'Decimal', '25.5']);
        fputcsv($file, ['Customer Satisfaction', 'Based on average rating (1-5 stars)', 'Percentage', '92.5%']);
    }

    private function exportMonthlyData($file, $restaurant, $year, $month = null)
    {
        fputcsv($file, ['Monthly Order Data Report', 'Nani Restaurant', 'Year: ' . $year]);
        fputcsv($file, ['Note: All personally identifiable information has been removed']);
        fputcsv($file, []);
        fputcsv($file, [
            'Month',
            'Date Range',
            'Total Orders',
            'Delivered',
            'In Progress',
            'Cancelled',
            'Revenue (₱)',
            'Avg Rating',
            'Peak Hour'
        ]);

        $months = $month ? [$month] : range(1, 12);

        foreach ($months as $m) {
            $monthName = date("F", mktime(0, 0, 0, $m, 1));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $year);

            // Get order statistics
            $stats = Order::where('restaurant_id', $restaurant->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status IN ("preparing","ready","on_the_way") THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = "delivered" THEN total_amount ELSE 0 END) as revenue
            ')
                ->first();

            // Get average rating
            $avgRating = Review::whereHas('order', function ($q) use ($restaurant, $year, $m) {
                $q->where('restaurant_id', $restaurant->id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m);
            })
                ->avg('restaurant_rating') ?? 0;

            // Get peak hour
            $peakHour = Order::where('restaurant_id', $restaurant->id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $m)
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->orderByDesc('count')
                ->first();

            fputcsv($file, [
                $monthName,
                $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-01 to ' . $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-' . $daysInMonth,
                $stats->total ?? 0,
                $stats->delivered ?? 0,
                $stats->in_progress ?? 0,
                $stats->cancelled ?? 0,
                number_format($stats->revenue ?? 0, 2, '.', ''),
                number_format($avgRating, 1),
                $peakHour ? $peakHour->hour . ':00 (' . $peakHour->count . ' orders)' : 'N/A'
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['DATA DICTIONARY:']);
        fputcsv($file, ['Field', 'Description', 'Data Type']);
        fputcsv($file, ['Month', 'Month name', 'String']);
        fputcsv($file, ['Date Range', 'Full date range for the month', 'String']);
        fputcsv($file, ['Total Orders', 'All orders received', 'Integer']);
        fputcsv($file, ['Delivered', 'Successfully delivered orders', 'Integer']);
        fputcsv($file, ['In Progress', 'Orders being prepared or delivered', 'Integer']);
        fputcsv($file, ['Cancelled', 'Cancelled orders', 'Integer']);
        fputcsv($file, ['Revenue', 'Total revenue from delivered orders', 'Decimal (₱)']);
        fputcsv($file, ['Avg Rating', 'Average customer rating (1-5)', 'Decimal']);
        fputcsv($file, ['Peak Hour', 'Busiest hour for orders', 'String']);
    }

    private function exportDetailedData($file, $restaurant, $year, $month)
    {
        fputcsv($file, ['Detailed Order Data (No PII)', 'Nani Restaurant', date('F Y', mktime(0, 0, 0, $month, 1, $year))]);
        fputcsv($file, ['Note: All personally identifiable information has been removed']);
        fputcsv($file, []);

        // Main order data (no PII)
        fputcsv($file, [
            'order_id',
            'order_number',
            'order_date',
            'order_time',
            'order_day',
            'order_hour',
            'order_status',
            'total_amount',
            'delivery_fee',
            'payment_method',
            'payment_status',
            'item_count',
            'preparation_time_minutes',
            'has_review',
            'restaurant_rating',
            'rider_rating',
            'special_instructions_present'
        ]);

        $orders = Order::with(['items', 'reviews'])
            ->where('restaurant_id', $restaurant->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at')
            ->get();

        foreach ($orders as $order) {
            $preparationTime = $order->delivered_at
                ? round((strtotime($order->delivered_at) - strtotime($order->created_at)) / 60, 1)
                : null;

            $review = $order->reviews->first();

            fputcsv($file, [
                $order->id,
                $order->order_number,
                $order->created_at->format('Y-m-d'),
                $order->created_at->format('H:i:s'),
                $order->created_at->format('l'),
                $order->created_at->format('H'),
                $order->status,
                number_format($order->total_amount, 2, '.', ''),
                number_format($order->delivery_fee, 2, '.', ''),
                $order->payment_method,
                $order->payment_status,
                $order->items->count(),
                $preparationTime ?? '',
                $review ? 'yes' : 'no',
                $review ? $review->restaurant_rating : '',
                $review ? $review->rider_rating : '',
                !empty($order->special_instructions) ? 'yes' : 'no'
            ]);
        }

        // Add data dictionary section
        fputcsv($file, []);
        fputcsv($file, ['DATA DICTIONARY']);
        fputcsv($file, []);
        fputcsv($file, ['Field', 'Description', 'Data Type', 'Possible Values']);
        fputcsv($file, ['order_id', 'Unique identifier for the order', 'Integer', '1, 2, 3, ...']);
        fputcsv($file, ['order_number', 'Public order reference number', 'String', 'ORD-2024-001']);
        fputcsv($file, ['order_date', 'Date when order was placed', 'Date', 'YYYY-MM-DD']);
        fputcsv($file, ['order_time', 'Time when order was placed', 'Time', 'HH:MM:SS (24h)']);
        fputcsv($file, ['order_day', 'Day of the week', 'String', 'Monday, Tuesday, ...']);
        fputcsv($file, ['order_hour', 'Hour of the day (24h)', 'Integer', '0-23']);
        fputcsv($file, ['order_status', 'Current status of order', 'String', 'pending, preparing, ready, on_the_way, delivered, cancelled']);
        fputcsv($file, ['total_amount', 'Subtotal amount', 'Decimal', '150.50']);
        fputcsv($file, ['delivery_fee', 'Delivery fee charged', 'Decimal', '25.00']);
        fputcsv($file, ['payment_method', 'Payment method used', 'String', 'cash_on_delivery, gcash']);
        fputcsv($file, ['payment_status', 'Payment status', 'String', 'pending, approved, rejected']);
        fputcsv($file, ['item_count', 'Number of items in order', 'Integer', '1, 2, 3, ...']);
        fputcsv($file, ['preparation_time_minutes', 'Time from order to delivery', 'Decimal', '25.5 (minutes)']);
        fputcsv($file, ['has_review', 'Whether order has review', 'Boolean', 'yes/no']);
        fputcsv($file, ['restaurant_rating', 'Rating given to restaurant', 'Integer', '1-5 (empty if no review)']);
        fputcsv($file, ['rider_rating', 'Rating given to rider', 'Integer', '1-5 (empty if no review)']);
        fputcsv($file, ['special_instructions_present', 'Special instructions provided', 'Boolean', 'yes/no']);
    }
    // Add to AnalyticsController.php
    /**
     * Export Order Trip Data (Simple CSV like bike-sharing)
     */
    public function exportTripData(Request $request)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();
        $type = $request->query('type', 'monthly');
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('m'));
        $quarter = $request->query('quarter', ceil(date('m') / 3));

        // Determine date range based on type
        $dateRange = $this->getDateRange($type, $year, $month, $quarter);

        $filename = "nani_order_data_";
        if ($type === 'quarterly') {
            $filename .= "Q{$quarter}_{$year}";
        } elseif ($type === 'monthly') {
            $filename .= date('F', mktime(0, 0, 0, $month, 1)) . "_{$year}";
        } else {
            $filename .= date('Y_m_d');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($restaurant, $dateRange) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Header row (simple like bike-sharing)
            fputcsv($file, [
                'order_id',
                'order_number',
                'duration_minutes',
                'start_time',
                'end_time',
                'order_status',
                'total_amount',
                'delivery_fee',
                'payment_method',
                'item_count',
                'has_review',
                'restaurant_rating',
                'rider_rating',
                'preparation_time_minutes'
            ]);

            // Get orders within date range
            $orders = Order::with(['items', 'reviews'])
                ->where('restaurant_id', $restaurant->id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->orderBy('created_at')
                ->get();

            foreach ($orders as $order) {
                // Calculate duration (order to completion)
                $duration = $order->delivered_at
                    ? round((strtotime($order->delivered_at) - strtotime($order->created_at)) / 60, 1)
                    : null;

                // Calculate preparation time (order to ready/on_the_way)
                $preparationTime = null;
                if ($order->status === 'delivered' || $order->status === 'on_the_way') {
                    // Find when status changed to 'ready' or 'on_the_way'
                    $preparationTime = $duration ? round($duration * 0.7, 1) : null; // Estimate 70% of total time
                }

                $review = $order->reviews->first();

                fputcsv($file, [
                    $order->id,
                    $order->order_number,
                    $duration ?? '',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->delivered_at ? date('Y-m-d H:i:s', strtotime($order->delivered_at)) : '',
                    $order->status,
                    number_format($order->total_amount, 2, '.', ''),
                    number_format($order->delivery_fee, 2, '.', ''),
                    $order->payment_method,
                    $order->items->count(),
                    $review ? 'yes' : 'no',
                    $review ? $review->restaurant_rating : '',
                    $review ? $review->rider_rating : '',
                    $preparationTime ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Data Dictionary
     */
    public function exportDataDictionary()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="nani_data_dictionary.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Header
            fputcsv($file, ['NaNi Restaurant - Data Dictionary']);
            fputcsv($file, ['Generated: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Data Dictionary (like bike-sharing system)
            fputcsv($file, ['Field', 'Description', 'Data Type', 'Example', 'Notes']);

            fputcsv($file, [
                'order_id',
                'Unique identifier for the order',
                'Integer',
                '12345',
                'Internal ID, not shown to customers'
            ]);

            fputcsv($file, [
                'order_number',
                'Public order reference number',
                'String',
                'ORD-2025-001',
                'Displayed to customers'
            ]);

            fputcsv($file, [
                'duration_minutes',
                'Total time from order to delivery',
                'Decimal',
                '25.5',
                'In minutes, only for completed orders'
            ]);

            fputcsv($file, [
                'start_time',
                'When the order was placed',
                'DateTime',
                '2025-07-01 14:30:00',
                'YYYY-MM-DD HH:MM:SS format'
            ]);

            fputcsv($file, [
                'end_time',
                'When the order was delivered',
                'DateTime',
                '2025-07-01 14:55:30',
                'Empty for non-delivered orders'
            ]);

            fputcsv($file, [
                'order_status',
                'Current status of order',
                'String',
                'delivered',
                'pending, preparing, ready, on_the_way, delivered, cancelled'
            ]);

            fputcsv($file, [
                'total_amount',
                'Subtotal amount',
                'Decimal',
                '450.75',
                'In Philippine Peso (₱), before delivery fee'
            ]);

            fputcsv($file, [
                'delivery_fee',
                'Delivery fee charged',
                'Decimal',
                '25.00',
                'In Philippine Peso (₱)'
            ]);

            fputcsv($file, [
                'payment_method',
                'Payment method used',
                'String',
                'gcash',
                'cash_on_delivery or gcash'
            ]);

            fputcsv($file, [
                'item_count',
                'Number of items in order',
                'Integer',
                '3',
                'Count of menu items ordered'
            ]);

            fputcsv($file, [
                'has_review',
                'Whether order has a review',
                'Boolean',
                'yes',
                'yes or no'
            ]);

            fputcsv($file, [
                'restaurant_rating',
                'Rating given to restaurant',
                'Integer',
                '5',
                '1-5 stars, empty if no review'
            ]);

            fputcsv($file, [
                'rider_rating',
                'Rating given to delivery rider',
                'Integer',
                '4',
                '1-5 stars, empty if no review'
            ]);

            fputcsv($file, [
                'preparation_time_minutes',
                'Estimated preparation time',
                'Decimal',
                '18.5',
                'Time from order to ready for delivery'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getDateRange($type, $year, $month, $quarter)
    {
        $now = now();

        switch ($type) {
            case 'quarterly':
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $startMonth + 2;
                return [
                    'start' => "{$year}-{$startMonth}-01 00:00:00",
                    'end' => date('Y-m-t 23:59:59', strtotime("{$year}-{$endMonth}-01"))
                ];

            case 'monthly':
                return [
                    'start' => "{$year}-{$month}-01 00:00:00",
                    'end' => date('Y-m-t 23:59:59', strtotime("{$year}-{$month}-01"))
                ];

            default: // daily
                return [
                    'start' => $now->format('Y-m-d 00:00:00'),
                    'end' => $now->format('Y-m-d 23:59:59')
                ];
        }
    }
}
