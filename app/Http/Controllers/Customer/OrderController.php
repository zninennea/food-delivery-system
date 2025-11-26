<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User; // Add this import
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with(['items.menuItem', 'rider'])
            ->where('customer_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('menuItem')
            ->where('customer_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        // Validation rules
        $validationRules = [
            'delivery_address' => 'required|string|max:500',
            'customer_phone' => 'required|string|max:20',
            'special_instructions' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash_on_delivery,gcash',
            'cash_provided' => 'nullable|numeric|min:0',
        ];

        // Only require GCash fields if GCash is selected
        if ($request->payment_method === 'gcash') {
            $validationRules['gcash_reference_number'] = 'required|string|max:100';
            $validationRules['gcash_receipt'] = 'required|image|max:2048';
        }

        $request->validate($validationRules);

        // Calculate totals
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });

        $deliveryFee = 50.00;
        $grandTotal = $totalAmount + $deliveryFee;

        $orderData = [
            'restaurant_id' => 1,
            'customer_id' => $user->id,
            'order_number' => 'NANI' . Str::random(8),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'delivery_fee' => $deliveryFee,
            'delivery_address' => $request->delivery_address,
            'customer_phone' => $request->customer_phone,
            'special_instructions' => $request->special_instructions,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'pending'
        ];

        // Handle GCash payment
        if ($request->payment_method === 'gcash') {
            if ($request->hasFile('gcash_receipt')) {
                $receiptPath = $request->file('gcash_receipt')->store('gcash-receipts', 'public');
                $orderData['gcash_receipt_path'] = $receiptPath;
            }
            $orderData['gcash_reference_number'] = $request->gcash_reference_number;
        }

        // Handle Cash on Delivery
        if ($request->payment_method === 'cash_on_delivery' && $request->cash_provided) {
            $orderData['cash_provided'] = $request->cash_provided;
        }

        // Create the order
        $order = Order::create($orderData);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $cartItem->menu_item_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->menuItem->price
            ]);
        }

        // AUTO-ASSIGN RIDER
        $this->assignRiderToOrder($order);

        // Clear cart
        Cart::where('customer_id', $user->id)->delete();

        return redirect()->route('customer.track-order', $order)
            ->with('success', 'Order placed successfully! Payment ' .
                ($request->payment_method === 'cash_on_delivery' ? 'will be collected on delivery.' : 'is pending approval.'));
    }

    /**
     * Auto-assign a rider to the order
     */
    private function assignRiderToOrder(Order $order)
    {
        // Find an available active rider
        $availableRider = User::where('role', 'rider')
            ->where('status', 'active')
            ->inRandomOrder() // Simple assignment - you can make this smarter
            ->first();

        if ($availableRider) {
            $order->update(['rider_id' => $availableRider->id]);

            // Log the assignment
            \Log::info("Order #{$order->order_number} assigned to rider: {$availableRider->name}");
        } else {
            \Log::warning("No available riders found for order #{$order->order_number}");
        }

        return $availableRider;
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        if ($order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load('items.menuItem', 'rider', 'messages.sender');

        return view('customer.track-order', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order can no longer be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason
        ]);

        return redirect()->route('customer.track-order', $order)
            ->with('success', 'Order cancelled successfully.');
    }

    public function requestModification(Order $order)
    {
        $user = Auth::user();

        if ($order->customer_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->canBeModified()) {
            return redirect()->back()->with('error', 'This order can no longer be modified.');
        }

        $order->update([
            'status' => Order::STATUS_MODIFICATION_REQUESTED
        ]);

        return redirect()->back()->with('success', 'Modification request sent to restaurant.');
    }

    // Add these methods to your existing OrderController class
    public function getMessages(Order $order)
    {
        try {
            $user = Auth::user();

            if ($order->customer_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $messages = Message::with('sender')
                ->where('order_id', $order->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_id' => $message->sender_id,
                        'created_at' => $message->created_at->format('g:i A'),
                        'sender_name' => $message->sender->name,
                    ];
                });

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Error getting messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load messages'], 500);
        }
    }

    public function sendMessage(Request $request, Order $order)
    {
        try {
            $user = Auth::user();

            if ($order->customer_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            // Create message
            $message = Message::create([
                'order_id' => $order->id,
                'sender_id' => $user->id,
                'receiver_id' => $order->rider_id,
                'message' => $request->message,
                'is_read' => false
            ]);

            return response()->json([
                'success' => true,
                'message_id' => $message->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}
