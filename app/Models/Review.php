<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $order_id
 * @property int $restaurant_rating
 * @property int|null $rider_rating
 * @property int|null $rider_id
 * @property int|null $menu_item_id
 * @property int|null $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReviewItem> $reviewItems
 * @property-read int|null $review_items_count
 * @property-read \App\Models\User|null $rider
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRestaurantRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRiderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRiderRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'rider_id',
        'menu_item_id',
        'rating',
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
