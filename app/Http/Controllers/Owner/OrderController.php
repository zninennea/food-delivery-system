<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $orders = Order::with(['customer', 'rider', 'items.menuItem'])
            ->where('restaurant_id', $restaurant->id)
            ->latest()
            ->paginate(10);

        $statusCounts = [
            'pending' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', Order::STATUS_PENDING)
                ->count(),
            'preparing' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', Order::STATUS_PREPARING)
                ->count(),
            'ready' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', Order::STATUS_READY)
                ->count(),
            'on_the_way' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', Order::STATUS_ON_THE_WAY)
                ->count(),
            'delivered' => Order::where('restaurant_id', $restaurant->id)
                ->where('status', Order::STATUS_DELIVERED)
                ->count(),
        ];

        return view('owner.orders.index', compact('orders', 'statusCounts', 'restaurant'));
    }

    public function show(Order $order)
    {
        // Authorization - ensure the order belongs to owner's restaurant
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['customer', 'rider', 'items.menuItem', 'messages.sender']);

        return view('owner.orders.show', compact('order', 'restaurant'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,on_the_way,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // Log status change or send notification
        $this->logStatusChange($order, $oldStatus, $newStatus); {
            Log::info("Order #{$order->order_number} status changed from {$oldStatus} to {$newStatus}");
        }
        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function assignRider(Request $request, Order $order)
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        if ($order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $request->validate([
            'rider_id' => 'required|exists:users,id'
        ]);

        $order->update(['rider_id' => $request->rider_id]);

        return redirect()->back()->with('success', 'Rider assigned successfully!');
    }

    private function logStatusChange(Order $order, $oldStatus, $newStatus)
    {
        // You can implement logging, notifications, or other business logic here
        // For example, send notification to customer when status changes
        Log::info("Order #{$order->order_number} status changed from {$oldStatus} to {$newStatus}");
    }

    public function getTodayOrders()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $todayOrders = Order::with(['customer', 'items.menuItem'])
            ->where('restaurant_id', $restaurant->id)
            ->today()
            ->latest()
            ->get();

        return response()->json($todayOrders);
    }

    public function getActiveOrders()
    {
        $restaurant = Restaurant::where('owner_id', Auth::id())->firstOrFail();

        $activeOrders = Order::with(['customer', 'rider', 'items.menuItem'])
            ->where('restaurant_id', $restaurant->id)
            ->active()
            ->latest()
            ->get();

        return response()->json($activeOrders);
    }
}
