<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'customer_id',
        'rider_id',
        'order_number',
        'status',
        'total_amount',
        'delivery_address',
        'customer_phone',
        'special_instructions'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_ON_THE_WAY = 'on_the_way';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PREPARING, self::STATUS_READY, self::STATUS_ON_THE_WAY]);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Helpers
    public function getStatusColor()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PREPARING => 'blue',
            self::STATUS_READY => 'green',
            self::STATUS_ON_THE_WAY => 'purple',
            self::STATUS_DELIVERED => 'gray',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }
}