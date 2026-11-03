<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'staff_id',
        'action',
        'old_status',
        'new_status',
        'notes'
    ];

    // Since order_id is string (ULID), we need to specify the key type
    protected $keyType = 'string';
    public $incrementing = false;

    // Relationships
    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'order_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}