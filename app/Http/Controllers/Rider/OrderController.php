<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the specified order for rider
     */
    public function showOrder(Order $order)
    {
        if (Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized access. Rider role required.');
        }

        $rider = Auth::user();

        // Verify the order is assigned to this rider
        if ($order->rider_id !== $rider->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['customer', 'restaurant', 'items.menuItem', 'messages.sender']);

        return view('rider.order-details', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized access. Rider role required.');
        }

        $rider = Auth::user();

        // Verify the order is assigned to this rider
        if ($order->rider_id !== $rider->id) {
            return redirect()->back()->with('error', 'Unauthorized access to this order.');
        }

        $request->validate([
            'status' => 'required|in:on_the_way,delivered'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);

        // If delivered, update delivered_at timestamp
        if ($newStatus === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return redirect()->back()->with('success', "Order status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . "!");
    }

    /**
     * Display order history
     */
    public function orderHistory()
    {
        if (Auth::user()->role !== 'rider') {
            abort(403, 'Unauthorized access. Rider role required.');
        }

        $rider = Auth::user();

        $orders = Order::with(['customer', 'restaurant'])
            ->where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->latest()
            ->paginate(15);

        return view('rider.order-history', compact('orders'));
    }
}