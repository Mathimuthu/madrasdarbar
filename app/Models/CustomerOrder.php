<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerOrder extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'customer_id',
        'dining_table_id',
        'delivery_type',
        'order_total_price',
        'payment_type',
        'isPaid',
        'order_status',
        'order_notes',
        'customer_contact',
        'delivery_charge',
    'delivery_charge_added_by',
        'location_id',
        'assigned_staff_id', // Add this
        'order_code'
    ];

    protected $casts = [
        'order_status' => OrderStatusEnum::class,
    ];

    public function customerOrderDetail() : HasMany
    {
        return $this->hasMany(CustomerOrderDetail::class, 'order_id');
    }

    public function diningTable() : BelongsTo
    {
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function location() : BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    // New relationships
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class, 'order_id');
    }

    // Scopes
    public function scopeVisibleToStaff($query, $staffId)
    {
        return $query->where(function($q) use ($staffId) {
            $q->where('assigned_staff_id', $staffId)
              ->orWhereNull('assigned_staff_id');
        });
    }

    public function scopeAssignedToMe($query, $staffId)
    {
        return $query->where('assigned_staff_id', $staffId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_staff_id');
    }
        public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_code = $order->generateOrderCode();
        });
    }

    public function generateOrderCode(): string
    {
        $prefixes = [
            1 => 'MDPHU',  // Phuket
            2 => 'MDBAN',  // Bangkok
            3 => 'MDPAT',  // Pattaya
            4 => 'MDCOL',  // Colombo
        ];

        $prefix = $prefixes[$this->location_id] ?? 'MDXXX';

        // Daily sequential number (resets every day per location)
        $today = now()->format('Y-m-d');

        $count = CustomerOrder::where('location_id', $this->location_id)
            ->whereDate('created_at', $today)
            ->count();

        $sequence = str_pad($count + 1, 6, '0', STR_PAD_LEFT); // 000001, 000002...

        return $prefix . '-' . $sequence;
    }

    // Optional: Accessor so you can use $order->pretty_code
    public function getPrettyCodeAttribute()
    {
        return $this->order_code;
    }
    // In CustomerOrder model or a helper/trait
public function getFinalTotalAttribute()
{
    $foodTotal     = (float) $this->order_total_price;
    $delivery      = (float) $this->delivery_charge ?? 0;
    $extraCharges  = $this->charges()->sum('calculated_amount'); // only other charges

    return $foodTotal + $delivery + $extraCharges;
}

// Optional accessor
public function getDisplayTotalAttribute()
{
    return number_format($this->final_total, 2);
}
}