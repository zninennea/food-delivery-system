<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMessages(Order $order)
    {
        try {
            $user = Auth::user();

            // Verify the rider is assigned to this order
            if ($order->rider_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized access to this order chat.'], 403);
            }

            $messages = Message::with('sender')
                ->where('order_id', $order->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) use ($user) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_id' => $message->sender_id,
                        'created_at' => $message->created_at->format('g:i A'),
                        'sender_name' => $message->sender->name,
                        'is_own_message' => $message->sender_id === $user->id,
                    ];
                });

            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Error getting rider messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load messages'], 500);
        }
    }

    public function sendMessage(Request $request, Order $order)
    {
        try {
            $user = Auth::user();

            // Verify the rider is assigned to this order
            if ($order->rider_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized access to this order chat.'], 403);
            }

            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            // Create message - rider sends to customer
            $message = Message::create([
                'order_id' => $order->id,
                'sender_id' => $user->id,
                'receiver_id' => $order->customer_id, // Send to customer
                'message' => $request->message,
                'is_read' => false
            ]);

            return response()->json([
                'success' => true,
                'message_id' => $message->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending rider message: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send message'], 500);
        }
    }
}
