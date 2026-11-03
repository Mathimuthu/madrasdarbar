<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StaffAccount extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    // Add these two lines
    protected $primaryKey = 'staff_account_id';
    public $incrementing = false; // Set to true if it's an auto-incrementing integer
    protected $keyType = 'string'; // Add this if staff_account_id is a string

    protected $fillable = [
        'staff_account_id',
        'location_id'
    ];

    public function user() : HasOne
    {
        return $this->hasOne(User::class, 'staff_id', 'staff_account_id');
    }

    public function location() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }
}