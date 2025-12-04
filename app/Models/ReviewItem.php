<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\MenuItem|null $menuItem
 * @property-read \App\Models\Review|null $review
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewItem query()
 * @mixin \Eloquent
 */
class ReviewItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'menu_item_id',
        'rating',
        'comment'
    ];

    // Relationships
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}