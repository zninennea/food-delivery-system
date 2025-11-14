<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'rider_id',
        'restaurant_rating',
        'rider_rating',
        'comment'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function reviewItems()
    {
        return $this->hasMany(ReviewItem::class);
    }

    // Helper methods
    public function getAverageItemRating()
    {
        return $this->reviewItems()->avg('rating');
    }
}