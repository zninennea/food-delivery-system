<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $menu_item_id
 * @property int $quantity
 * @property string|null $special_instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read mixed $subtotal
 * @property-read \App\Models\MenuItem $menuItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart byCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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