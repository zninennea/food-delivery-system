<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Owner\MenuController as OwnerMenuController;
use App\Http\Controllers\Owner\OrderController as OwnerOrderController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

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

    // Profile routes - use POST for update
    Route::get('/profile', [OwnerProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [OwnerProfileController::class, 'update']); // Changed to POST

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
});

// Customer Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
});

// Rider Routes
Route::middleware(['auth'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/dashboard', function () {
        return view('rider.dashboard');
    })->name('dashboard');
});
