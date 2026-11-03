<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charges extends Model
{
    use HasFactory;

    protected $table = 'charges';

    protected $fillable = [
        'name',
        'amount',
        'type',
        'location_id',
        'status'
    ];
    
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

}
