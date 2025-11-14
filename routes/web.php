<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\OrderController as OwnerOrderController;
use App\Http\Controllers\Owner\ChatController as OwnerChatController;
use App\Http\Controllers\Owner\RiderController as OwnerRiderController;
use App\Http\Controllers\Owner\AnalyticsController as OwnerAnalyticsController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController; // Add this
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
//use App\Http\Controllers\Customer\ReviewController as CustomerReviewController; // Comment out for now

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Owner Routes
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

    // Chat routes
    Route::get('/orders/{order}/chat', [OwnerChatController::class, 'showChat'])->name('orders.chat');
    Route::post('/orders/{order}/chat/send', [OwnerChatController::class, 'sendMessage'])->name('orders.chat.send');
    Route::get('/orders/{order}/chat/messages', [OwnerChatController::class, 'getMessages'])->name('orders.chat.messages');
    Route::post('/orders/{order}/chat/read', [OwnerChatController::class, 'markAsRead'])->name('orders.chat.read');

    // Analytics routes
    Route::get('/analytics', [OwnerAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/sales-data', [OwnerAnalyticsController::class, 'getSalesData'])->name('analytics.sales-data');

    // Rider management routes
    Route::get('/riders', [OwnerRiderController::class, 'index'])->name('riders.index');
    Route::get('/riders/create', [OwnerRiderController::class, 'create'])->name('riders.create');
    Route::post('/riders', [OwnerRiderController::class, 'store'])->name('riders.store');
    Route::get('/riders/{rider}/edit', [OwnerRiderController::class, 'edit'])->name('riders.edit');
    Route::put('/riders/{rider}', [OwnerRiderController::class, 'update'])->name('riders.update');
    Route::delete('/riders/{rider}', [OwnerRiderController::class, 'destroy'])->name('riders.destroy');
});

// Customer Routes - REMOVE 'customer' from middleware array
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // Menu
    Route::get('/menu', [CustomerDashboardController::class, 'menu'])->name('menu');
    Route::get('/menu/{menuItem}', [CustomerDashboardController::class, 'showMenuItem'])->name('menu.item');

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [CustomerProfileController::class, 'editPassword'])->name('profile.password');
    Route::post('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Cart routes
    Route::get('/cart', [CustomerCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{menuItem}', [CustomerCartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CustomerCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CustomerCartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/clear', [CustomerCartController::class, 'clear'])->name('cart.clear');

    // Order routes
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');
    Route::get('/track-order/{order}', [CustomerOrderController::class, 'show'])->name('track-order');

    // Comment out review routes for now
    // Route::get('/orders/{order}/review', [CustomerReviewController::class, 'create'])->name('reviews.create');
    // Route::post('/orders/{order}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');
});

// Rider Routes
Route::middleware(['auth'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/dashboard', function () {
        return view('rider.dashboard');
    })->name('dashboard');
});

// Test route for chat
Route::get('/test-chat', function () {
    $order = \App\Models\Order::with('rider')->first();
    if (!$order) {
        return "No orders found. Please create an order with a rider first.";
    }
    return redirect()->route('owner.orders.chat', $order);
});
