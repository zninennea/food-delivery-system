<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $profile_picture
 * @property string|null $delivery_address
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $vehicle_type
 * @property string|null $vehicle_plate
 * @property string|null $drivers_license
 * @property string $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read mixed $cart_count
 * @property-read mixed $cart_total
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Restaurant|null $restaurant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $riderOrders
 * @property-read int|null $rider_orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User customers()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User owners()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User riders()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDriversLicense($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVehiclePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVehicleType($value)
 * @mixin \Eloquent
 */
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
        'vehicle_type',     // For riders
        'vehicle_plate',    // For riders  
        'status',           // For riders
        'delivery_address', // Add this for customers
        'drivers_license',
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
    public function getDefaultDeliveryAddress()
    {
        return $this->delivery_address ?? 'No delivery address set';
    }
}
