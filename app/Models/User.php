<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_picture',
        'vehicle_type',     
        'vehicle_plate',   
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function riderOrders()
    {
        return $this->hasMany(Order::class, 'rider_id');
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    // Scopes
    public function scopeOwners($query)
    {
        return $query->where('role', 'owner');
    }

    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    public function scopeRiders($query)
    {
        return $query->where('role', 'rider');
    }

    // Helpers
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isRider()
    {
        return $this->role === 'rider';
    }

    // Cart helpers
    public function getCartCountAttribute()
    {
        return $this->cartItems->sum('quantity');
    }

    public function getCartTotalAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->menuItem->price;
        });
    }
}
