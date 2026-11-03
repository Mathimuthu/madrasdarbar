<?php
// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'food_id', 
        'quantity', 
        'delivery_type', 
        'location_id'
    ];

    public function food()
    {
        return $this->belongsTo(FoodMenu::class, 'food_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    // Get the price for this cart item
    public function getPrice()
    {
        return $this->food->getPriceForLocation($this->location_id);
    }

    // Get the total price for this cart item
    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }

    // Helper method to get cart total for a customer
    public static function getCartTotal($customerId, $locationId = null)
    {
        $query = self::where('user_id', $customerId)
            ->with(['food.locations', 'location']);
        
        if ($locationId) {
            $query->where('location_id', $locationId);
        }
        
        return $query->get()->sum(function ($cartItem) {
            return $cartItem->getTotalPrice();
        });
    }
}