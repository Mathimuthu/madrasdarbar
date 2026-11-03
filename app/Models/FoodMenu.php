<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price', // This might be a base price, but location-specific prices are in food_price table
        'category_id',
        'image',
    ];

    public function customerOrderDetail(): HasMany
    {
        return $this->hasMany(CustomerOrderDetail::class, 'food_id');
    }

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }
    
    public function foodLocations(): HasMany
    {
        return $this->hasMany(FoodLocation::class, 'food_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'food_price', 'food_id', 'location_id')
                    ->withPivot('price')
                    ->withTimestamps();
    }

    // Helper method to get price for a specific location
    public function getPriceForLocation($locationId)
    {
        $foodLocation = $this->foodLocations()
                            ->where('location_id', $locationId)
                            ->first();
        
        return $foodLocation ? $foodLocation->price : $this->price;
    }
}