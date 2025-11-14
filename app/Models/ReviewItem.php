<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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