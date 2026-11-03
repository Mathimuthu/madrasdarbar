<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    
    protected $table = 'location';
    protected $primaryKey = 'location_id';
     protected $fillable = [
        'location_name',
        'country',
        'currency',
    ];
    
    public function foodMenus()
        {
            return $this->belongsToMany(FoodMenu::class, 'food_price', 'location_id', 'food_id')
                        ->withPivot('price')
                        ->withTimestamps();
        }
        
     public function charges(): HasMany
    {
        return $this->hasMany(Charges::class, 'location_id', 'location_id');
    }

}
