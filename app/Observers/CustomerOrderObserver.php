<?php

namespace App\Observers;

use App\Models\CustomerOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CustomerOrderObserver
{
    public function creating(CustomerOrder $order)
    {
        // Map location_id to prefix
        $prefixes = [
            1 => 'MDPHU',  // Phuket
            2 => 'MDBAN',  // Bangkok
            3 => 'MDPAT',  // Pattaya
            4 => 'MDCOL',  // Colombo
        ];

        $prefix = $prefixes[$order->location_id] ?? 'MDLOC';

        // We'll attempt to compute the next available numeric suffix by reading existing order_codes.
        // Loop until we find a code that does not already exist.
        // Note: this can still race under extreme concurrency; see controller retry below to handle duplicate-key errors.

        // Helper closure to get max numeric suffix for a prefix
        $getMaxNumeric = function () use ($prefix) {
            // Extract numeric part after the '-' and convert to unsigned integer.
            // MYSQL expression: CAST(SUBSTRING(order_code, LOCATE('-', order_code) + 1) AS UNSIGNED)
            $row = DB::table('customer_orders')
                ->where('order_code', 'like', $prefix . '-%')
                ->selectRaw("MAX(CAST(SUBSTRING(order_code, LOCATE('-', order_code) + 1) AS UNSIGNED)) as maxnum")
                ->first();

            return intval($row->maxnum ?? 0);
        };

        // Start from max + 1 and keep incrementing until we find a free code
        $attempt = 0;
        $maxAttempts = 1000; // safety cap to avoid infinite loop in pathological cases
        $nextNum = $getMaxNumeric() + 1;

        do {
            if ($attempt++ > $maxAttempts) {
                throw new Exception('Unable to generate unique order code after ' . $maxAttempts . ' attempts.');
            }

            $code = $prefix . '-' . str_pad($nextNum, 6, '0', STR_PAD_LEFT);

            // If code not exists, break and use it
            $exists = DB::table('customer_orders')->where('order_code', $code)->exists();

            if (! $exists) {
                // set it on the model and return
                $order->order_code = $code;
                return;
            }

            // otherwise increment and try again
            $nextNum++;

            // small micro-sleep could reduce race in high concurrency (optional)
            // usleep(1000);
        } while (true);
    }
}
