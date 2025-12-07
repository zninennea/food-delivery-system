<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\OrderController as OwnerOrderController;
use App\Http\Controllers\Owner\ChatController as OwnerChatController;
use App\Http\Controllers\Owner\RiderController as OwnerRiderController;
use App\Http\Controllers\Owner\AnalyticsController as OwnerAnalyticsController;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    // Get restaurant data (first restaurant in database)
    $restaurant = \App\Models\Restaurant::first();
    return view('welcome', compact('restaurant'));
});

// Public Menu Routes (accessible without login)
Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu.public');
Route::get('/menu/{menuItem}', [\App\Http\Controllers\MenuController::class, 'show'])->name('menu.item');

// The rest of your existing routes...
// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Customer Routes - ONLY using 'auth' middleware (no custom middleware)
Route::middleware(['auth'])->prefix('customer')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');

    // Menu Routes
    Route::get('/menu', [DashboardController::class, 'menu'])->name('customer.menu');
    Route::get('/menu/{menuItem}', [DashboardController::class, 'showMenuItem'])->name('customer.menu-item');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/cart/add/{menuItem}', [CartController::class, 'add'])->name('customer.cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('customer.cart.destroy');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('customer.cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('customer.cart.checkout');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('customer.orders.store');
    Route::get('/track-order/{order}', [OrderController::class, 'show'])->name('customer.track-order');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('customer.orders.cancel');
    Route::post('/orders/{order}/request-modification', [OrderController::class, 'requestModification'])->name('customer.orders.request-modification');

    // Chat Routes
    Route::get('/orders/{order}/messages', [OrderController::class, 'getMessages']);
    Route::post('/orders/{order}/messages', [OrderController::class, 'sendMessage']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('customer.profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('customer.profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('customer.profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('customer.profile.update-password');

    // Reviews
    Route::get('/orders/{order}/reviews/create', [\App\Http\Controllers\Customer\ReviewController::class, 'create'])
        ->name('customer.reviews.create');
    Route::post('/orders/{order}/reviews', [\App\Http\Controllers\Customer\ReviewController::class, 'store'])
        ->name('customer.reviews.store');

    // In web.php, add these routes in the customer group:

    // All Reviews Page
    Route::get('/reviews', [\App\Http\Controllers\Customer\ReviewController::class, 'index'])->name('customer.reviews.index');
    Route::get('/reviews/{review}', [\App\Http\Controllers\Customer\ReviewController::class, 'show'])->name('customer.reviews.show');

    // Safety verification
    Route::post('/orders/{order}/verify-safety', function (Order $order) {
        try {
            $user = Auth::user();

            // Verify order belongs to user
            if ($order->customer_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Check if safety_verified column exists
            if (!Schema::hasColumn('orders', 'safety_verified')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Safety verification feature is not available yet'
                ]);
            }

            // Update safety verification
            $order->update([
                'safety_verified' => true,
                'safety_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Safety verification recorded'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });

    // Debug route
    Route::get('/debug-reviews-table', function () {
        $columns = \Schema::getColumnListing('reviews');
        $tableInfo = [];

        foreach ($columns as $column) {
            $type = \DB::select("SHOW COLUMNS FROM reviews WHERE Field = ?", [$column])[0];
            $tableInfo[$column] = [
                'type' => $type->Type,
                'null' => $type->Null,
                'default' => $type->Default,
                'key' => $type->Key,
            ];
        }

        return response()->json($tableInfo);
    });
});

Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [OwnerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [OwnerProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [OwnerProfileController::class, 'update'])->name('profile.update');

    // Menu routes
    Route::get('/menu', [OwnerMenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [OwnerMenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [OwnerMenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menuItem}/edit', [OwnerMenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menuItem}', [OwnerMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menuItem}', [OwnerMenuController::class, 'destroy'])->name('menu.delete');

    // Order routes
    Route::get('/orders', [OwnerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OwnerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OwnerOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/assign-rider', [OwnerOrderController::class, 'showAssignRiderForm'])->name('orders.assign-rider-form');
    Route::post('/orders/{order}/assign-rider', [OwnerOrderController::class, 'assignRider'])->name('orders.assign-rider');
    Route::delete('/orders/{order}', [OwnerOrderController::class, 'destroy'])->name('orders.destroy');

    // Owner GCash routes
    Route::get('/orders/{order}/gcash-receipt', [App\Http\Controllers\Owner\OrderController::class, 'viewGcashReceipt'])
        ->name('orders.gcash-receipt');

    Route::post('/orders/{order}/gcash-status', [App\Http\Controllers\Owner\OrderController::class, 'updateGcashStatus'])
        ->name('orders.gcash-status');

    // Chat routes
    Route::get('/orders/{order}/chat', [OwnerChatController::class, 'showChat'])->name('orders.chat');
    Route::post('/orders/{order}/chat/send', [OwnerChatController::class, 'sendMessage'])->name('orders.chat.send');
    Route::get('/orders/{order}/chat/messages', [OwnerChatController::class, 'getMessages'])->name('orders.chat.messages');
    Route::post('/orders/{order}/chat/read', [OwnerChatController::class, 'markAsRead'])->name('orders.chat.read');

    // Analytics routes
    Route::get('/analytics', [OwnerAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/sales-data', [OwnerAnalyticsController::class, 'getSalesData'])->name('analytics.sales-data');
    Route::get('/analytics/export', [App\Http\Controllers\Owner\AnalyticsController::class, 'export'])->name('analytics.export');

    // Owner Review routes
    Route::get('/reviews', [\App\Http\Controllers\Owner\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/stats', [\App\Http\Controllers\Owner\ReviewController::class, 'getReviewStats'])->name('reviews.stats');

    // Rider management routes
    Route::get('/riders', [OwnerRiderController::class, 'index'])->name('riders.index');
    Route::get('/riders/create', [OwnerRiderController::class, 'create'])->name('riders.create');
    Route::post('/riders', [OwnerRiderController::class, 'store'])->name('riders.store');
    Route::get('/riders/{rider}/edit', [OwnerRiderController::class, 'edit'])->name('riders.edit');
    Route::put('/riders/{rider}', [OwnerRiderController::class, 'update'])->name('riders.update');
    Route::delete('/riders/{rider}', [OwnerRiderController::class, 'destroy'])->name('riders.destroy');
});



// Rider Routes
Route::middleware(['auth'])->prefix('rider')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Rider\DashboardController::class, 'index'])->name('rider.dashboard');
    Route::get('/orders/{order}', [\App\Http\Controllers\Rider\DashboardController::class, 'showOrder'])->name('rider.orders.show');

    // FIXED: Use POST method for status updates (no PUT)
    Route::post('/orders/{order}/update-status', [\App\Http\Controllers\Rider\DashboardController::class, 'updateStatus'])->name('rider.orders.update-status');

    Route::get('/order-history', [\App\Http\Controllers\Rider\DashboardController::class, 'orderHistory'])->name('rider.order-history');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Rider\ProfileController::class, 'show'])->name('rider.profile.show');

    // Chat Routes
    Route::get('/orders/{order}/messages', [\App\Http\Controllers\Rider\ChatController::class, 'getMessages'])->name('rider.orders.messages');
    Route::post('/orders/{order}/messages', [\App\Http\Controllers\Rider\ChatController::class, 'sendMessage'])->name('rider.orders.send-message');
});

// Debug Routes (keep these)
Route::get('/debug-chat', function () {
    try {
        $user = Auth::user();
        $order = \App\Models\Order::with(['customer', 'rider', 'messages'])->first();

        if (!$order) {
            return "No orders found in database.";
        }

        return response()->json([
            'current_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ],
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer->name,
                'rider_id' => $order->rider_id,
                'rider_name' => $order->rider ? $order->rider->name : 'No rider assigned',
                'message_count' => $order->messages->count()
            ],
            'database_check' => [
                'messages_table_exists' => \Schema::hasTable('messages'),
                'orders_table_exists' => \Schema::hasTable('orders'),
                'users_table_exists' => \Schema::hasTable('users')
            ]
        ]);
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-chat-fix', function () {
    try {
        $order = \App\Models\Order::first();
        $user = \App\Models\User::first();

        if (!$order || !$user) {
            return "Need at least one order and user in database.";
        }

        $message = \App\Models\Message::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'receiver_id' => $user->id,
            'message' => 'Test message',
            'is_read' => false
        ]);

        return "Message created successfully. ID: " . $message->id;
    } catch (\Exception $e) {
        return "Error creating message: " . $e->getMessage();
    }
});

Route::get('/debug-chat-load/{orderId}', function ($orderId) {
    try {
        $user = Auth::user();
        $order = \App\Models\Order::findOrFail($orderId);

        $messages = \App\Models\Message::with('sender')
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

        return response()->json([
            'success' => true,
            'user_matches_order' => $order->customer_id === $user->id,
            'user_id' => $user->id,
            'order_customer_id' => $order->customer_id,
            'message_count' => $messages->count(),
            'messages' => $messages
        ]);
    } catch (\Exception $e) {
        \Log::error("Debug chat load error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::post('/debug-chat-send/{orderId}', function ($orderId) {
    try {
        $user = Auth::user();
        $order = \App\Models\Order::findOrFail($orderId);
        $messageText = request('message');

        $message = \App\Models\Message::create([
            'order_id' => $order->id,
            'sender_id' => $user->id,
            'receiver_id' => $order->rider_id,
            'message' => $messageText,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
            'user_id' => $user->id,
            'order_customer_id' => $order->customer_id
        ]);
    } catch (\Exception $e) {
        \Log::error("Debug chat send error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
// Test route for rider chat
Route::get('/test-rider-chat/{orderId}', function ($orderId) {
    try {
        $user = Auth::user();
        $order = \App\Models\Order::findOrFail($orderId);

        // Test if we can access messages
        $messages = \App\Models\Message::with('sender')
            ->where('order_id', $order->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'is_own_message' => $message->sender_id === $user->id,
                    'created_at' => $message->created_at->format('g:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'rider_id' => $order->rider_id,
            'current_user_id' => $user->id,
            'message_count' => $messages->count(),
            'messages' => $messages
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
/// Add to routes/web.php
Route::get('/debug-rider-status/{orderId}', function ($orderId) {
    try {
        $user = Auth::user();
        $order = \App\Models\Order::findOrFail($orderId);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'current_status' => $order->status,
            'rider_id' => $order->rider_id,
            'current_user_id' => $user->id,
            'user_is_rider' => $user->id === $order->rider_id,
            'route_exists' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});
Route::get('/rider/orders/{order}/update-status', function ($order) {
    return redirect()->back()->with('error', 'Please use the form buttons to update status');
});

Route::middleware(['auth'])->prefix('customer')->group(function () {
    Route::post('/orders/{order}/verify-safety', function (Order $order) {
        try {
            $user = Auth::user();

            // Verify order belongs to user
            if ($order->customer_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Check if safety_verified column exists
            if (!Schema::hasColumn('orders', 'safety_verified')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Safety verification feature is not available yet'
                ]);
            }

            // Update safety verification
            $order->update([
                'safety_verified' => true,
                'safety_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Safety verification recorded'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    });
});
Route::get('/api/orders/{order}/can-cancel', function (Order $order) {
    return response()->json([
        'can_cancel' => $order->canBeCancelled(),
        'status' => $order->status,
        'message' => $order->canBeCancelled()
            ? 'Order can be cancelled'
            : 'Order cannot be cancelled (status: ' . $order->status . ')'
    ]);
})->middleware('auth');
