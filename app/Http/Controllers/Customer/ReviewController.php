<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Add this method to your ReviewController.php
    public function index()
    {
        $user = Auth::user();

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        // Get ALL reviews with pagination
        $reviews = Review::with(['customer', 'order.items.menuItem'])
            ->whereHas('order')
            ->latest()
            ->paginate(10);

        // Calculate statistics
        $averageRating = Review::avg('restaurant_rating');
        $totalReviews = Review::count();
        $ratingDistribution = Review::selectRaw('restaurant_rating, COUNT(*) as count')
            ->groupBy('restaurant_rating')
            ->orderBy('restaurant_rating', 'desc')
            ->get();

        return view('customer.reviews.index', compact(
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'profilePictureUrl'
        ));
    }
    public function create(Order $order)
    {
        $user = Auth::user();

        $profilePictureUrl = null;
        if ($user->profile_picture) {
            $profilePictureUrl = asset('storage/' . $user->profile_picture);
        } elseif ($user->oauth_provider === 'google' && $user->avatar) {
            $profilePictureUrl = $user->avatar;
        }

        // Check if order belongs to user and is delivered
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->status !== 'delivered') {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You can only review delivered orders.');
        }

        // Check if review already exists
        $existingReview = Review::where('order_id', $order->id)->first();
        if ($existingReview) {
            return redirect()->route('customer.orders.index')
                ->with('info', 'You have already reviewed this order.');
        }

        return view('customer.reviews.create', compact('order', 'profilePictureUrl'));
    }

    public function store(Request $request, Order $order)
    {
        // Check authorization
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        if ($order->status !== 'delivered') {
            return redirect()->route('customer.orders.index')
                ->with('error', 'You can only review delivered orders.');
        }

        // Check if review already exists
        $existingReview = Review::where('order_id', $order->id)->first();
        if ($existingReview) {
            return redirect()->route('customer.orders.index')
                ->with('info', 'You have already reviewed this order.');
        }

        // Validation
        $request->validate([
            'restaurant_rating' => 'required|integer|min:1|max:5',
            'rider_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Create review
        // Use restaurant_rating as the main rating value
        Review::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'rating' => $request->restaurant_rating, // Add this line
            'restaurant_rating' => $request->restaurant_rating,
            'rider_rating' => $request->rider_rating,
            'rider_id' => $order->rider_id,
            'comment' => $request->comment
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Thank you for your review!');
    }
}
