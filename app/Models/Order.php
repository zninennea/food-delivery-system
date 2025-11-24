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
        'special_instructions',
        'payment_method',
        'cash_provided',
        'gcash_reference_number',
        'gcash_receipt_path',
        'gcash_payment_status',
        'payment_status',
        'delivery_fee'
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
    const PAYMENT_CASH_ON_DELIVERY = 'cash_on_delivery';
    const PAYMENT_GCASH = 'gcash';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_APPROVED = 'approved';
    const PAYMENT_STATUS_REJECTED = 'rejected';

    // Add modification support
    const STATUS_MODIFICATION_REQUESTED = 'modification_requested';

    // Add these constants to your Order model
    const GCASH_STATUS_PENDING = 'pending';
    const GCASH_STATUS_VERIFIED = 'verified';
    const GCASH_STATUS_REJECTED = 'rejected';


    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PREPARING,
            self::STATUS_READY,
            self::STATUS_ON_THE_WAY,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::STATUS_MODIFICATION_REQUESTED
        ];
    }

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
    // Add computed attribute for grand total
    public function getGrandTotalAttribute()
    {
        return $this->total_amount + $this->delivery_fee;
    }

    // Add payment status helper
    public function getPaymentStatusColor()
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_APPROVED => 'green',
            self::PAYMENT_STATUS_REJECTED => 'red',
            default => 'yellow'
        };
    }

    // Helpers
    public function getStatusColor()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PREPARING => 'blue',
            self::STATUS_READY => 'green',
            self::STATUS_ON_THE_WAY => 'purple',
            self::STATUS_DELIVERED => 'gray',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }
    // Check if order can be modified
    public function canBeModified()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PREPARING,
            self::STATUS_MODIFICATION_REQUESTED
        ]);
    }

    // Check if order can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_PREPARING
        ]);
    }
    public function getProgressPercentage()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 10,
            self::STATUS_PREPARING => 40,
            self::STATUS_READY => 70,
            self::STATUS_ON_THE_WAY => 90,
            self::STATUS_DELIVERED => 100,
            default => 0
        };
    }
    public function isGcashVerified()
    {
        return $this->payment_method === 'gcash' &&
            $this->gcash_payment_status === self::GCASH_STATUS_VERIFIED;
    }

    public function isGcashPending()
    {
        return $this->payment_method === 'gcash' &&
            $this->gcash_payment_status === self::GCASH_STATUS_PENDING;
    }
}
