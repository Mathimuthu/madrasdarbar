<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCharges extends Model
{
    use HasFactory;

    protected $table = 'order_charges';

    protected $fillable = [
        'order_id',
        'charge_id',
        'amount',
        'calculated_amount',
        'type',
        'name',
    ];
}
