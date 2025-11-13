<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function showChat(Order $order)
    {
        $user = Auth::user();
        
        // Check if user is owner and order belongs to their restaurant
        if ($user->role !== 'owner') {
            abort(403, 'Unauthorized access.');
        }

        $restaurant = $user->restaurant;
        
        if (!$restaurant || $order->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        if (!$order->rider) {
            return redirect()->route('owner.orders.show', $order)
                ->with('error', 'No rider assigned to this order.');
        }

        $order->load(['rider', 'customer', 'messages.sender', 'messages.receiver']);
        
        return view('owner.chat', compact('order'));
    }

    public function sendMessage(Request $request, Order $order)
    {
        $user = Auth::user();
        
        if ($user->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $restaurant = $user->restaurant;
        
        if (!$restaurant || $order->restaurant_id !== $restaurant->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Create message
        Message::create([
            'order_id' => $order->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $order->rider_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json(['success' => true]);
    }

    public function getMessages(Order $order)
    {
        $user = Auth::user();
        
        if ($user->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $restaurant = $user->restaurant;
        
        if (!$restaurant || $order->restaurant_id !== $restaurant->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::with('sender')
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function markAsRead(Order $order)
    {
        Message::where('order_id', $order->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}