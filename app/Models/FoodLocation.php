<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodLocation extends Model
{
    use HasFactory;

    protected $table = 'food_price';

    protected $fillable = [
        'food_id',
        'location_id',
        'price',
    ];

    public function foodMenu() : BelongsTo
    {
        return $this->belongsTo(FoodMenu::class, 'food_id');
    }
    
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
