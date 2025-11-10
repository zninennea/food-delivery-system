<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'menu_item_id', 
        'quantity',
        'special_instructions'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Computed attributes
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->menuItem->price;
    }

    // Scopes
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}