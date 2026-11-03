<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerOrder;
use App\Models\OrderHistory;
use App\Models\Charges;
use App\Models\OrderCharges;
use Illuminate\Support\Facades\Log;



class DeliveryOrderController extends Controller
{
    /**
     * Get orders assigned to logged-in delivery staff
     * Returns ALL assigned orders (the frontend will filter by status)
     */
 public function myOrders()
{
    $staffId = Auth::id();
    
    $orders = CustomerOrder::with([
        'customer:id,name,mobile,address,latlon',
        'customerOrderDetail:id,order_id,food_id,quantity',
        'customerOrderDetail.foodMenu:id,name',
        'location:location_id,currency'
    ])
    ->where('assigned_staff_id', $staffId)
    ->whereIn('order_status', ['ordered', 'ready_to_deliver', 'delivery_on_the_way', 'delivered', 'completed'])
    ->orderBy('created_at', 'desc')
    ->get();
    
    return response()->json([
        'success' => true,
        'orders' => $orders
    ]);
}

    /**
     * Update order status
     */
    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:ordered,preparing,ready_to_deliver,delivery_on_the_way,delivered,completed,cancelled'
        ]);

        // Ensure only assigned staff can update
        if ($order->assigned_staff_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - This order is not assigned to you'
            ], 403);
        }

        $oldStatus = $order->order_status;

        // Prevent completion without payment
        if ($validated['order_status'] === 'completed' && !$order->isPaid) {
            return response()->json([
                'success' => false,
                'message' => 'Payment must be completed first'
            ], 422);
        }

        // Update order status
        $order->update(['order_status' => $validated['order_status']]);

        // Log the status change in order history
        OrderHistory::create([
            'order_id'   => $order->id,
            'staff_id'   => Auth::id(),
            'action'     => 'status_changed',
            'old_status' => $oldStatus,
            'new_status' => $validated['order_status'],
            'notes'      => 'Order status updated by delivery staff'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'new_status' => $validated['order_status'],
            'order' => $order->load([
                'customer:id,name,mobile,address,latlon',
                'customerOrderDetail:id,order_id,food_id,quantity',
                'customerOrderDetail.foodMenu:id,name',
                'location:location_id,currency'
            ])
        ]);
    }

    /**
     * Get single order details
     */
    public function show(CustomerOrder $order)
    {
        // Ensure only assigned staff can view
        if ($order->assigned_staff_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load([
            'customer:id,name,mobile,address,latlon',
            'customerOrderDetail:id,order_id,food_id,quantity',
            'customerOrderDetail.foodMenu:id,name',
            'location:location_id,currency'
        ]);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
    
    /**
     * Update payment status
     */
 public function updatePaymentStatus(Request $request, CustomerOrder $order)
{
    $validated = $request->validate([
        'is_paid' => 'required|in:0,1',
        'payment_type' => 'nullable|in:cash,card'
    ]);

    // 🔐 Ensure assigned delivery staff only
    if ($order->assigned_staff_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }

    $oldPaymentStatus = $order->isPaid;

    $updateData = ['isPaid' => $validated['is_paid']];
    
    // Add payment_type if provided
    if (isset($validated['payment_type'])) {
        $updateData['payment_type'] = $validated['payment_type'];
    }

    $order->update($updateData);

    OrderHistory::create([
        'order_id'   => $order->id,
        'staff_id'   => Auth::id(),
        'action'     => 'payment_status_changed',
        'old_status' => $oldPaymentStatus ? 'paid' : 'not_paid',
        'new_status' => $validated['is_paid'] ? 'paid' : 'not_paid',
        'notes'      => 'Payment status updated by delivery staff' . 
                       (isset($validated['payment_type']) ? " ({$validated['payment_type']})" : '')
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Payment status updated successfully',
        'is_paid' => $validated['is_paid'],
        'payment_type' => $validated['payment_type'] ?? null,
        'order'   => $order->fresh()
    ]);
}
    
    public function addChargeToOrder(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'charge_id' => 'required|exists:charges,id',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Verify order belongs to authenticated staff
            $order = CustomerOrder::where('id', $orderId)
                ->where('assigned_staff_id', auth()->id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or not assigned to you',
                ], 404);
            }

            // Get charge details
            $charge = Charges::find($request->charge_id);

            // Calculate amount based on type
            $calculatedAmount = $request->amount;
            if ($charge->type == 2) { // Percentage
                $calculatedAmount = ($order->order_total_price * $charge->amount) / 100;
            }

            // Check if charge already exists for this order
            $existingCharge = OrderCharges::where('order_id', $orderId)
                ->where('charge_id', $request->charge_id)
                ->first();

            if ($existingCharge) {
                return response()->json([
                    'success' => false,
                    'message' => 'This charge is already added to the order',
                ], 400);
            }

            // Add charge to order
            OrderCharges::create([
                'order_id' => $orderId,
                'charge_id' => $request->charge_id,
                'amount' => $charge->amount,
                'calculated_amount' => $calculatedAmount,
                'type' => $charge->type,
                'name' => $charge->name,
            ]);

            // Update order total
            $this->updateOrderTotal($order);

            return response()->json([
                'success' => true,
                'message' => 'Charge added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add charge: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add custom charge to order
     */
    public function addCustomChargeToOrder(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:1,2', // 1 = Fixed, 2 = Percentage
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Verify order belongs to authenticated staff
            $order = CustomerOrder::where('id', $orderId)
                ->where('assigned_staff_id', auth()->id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or not assigned to you',
                ], 404);
            }

            // Calculate amount based on type
            $calculatedAmount = $request->amount;
            if ($request->type == 2) { // Percentage
                $calculatedAmount = ($order->order_total_price * $request->amount) / 100;
            }

            // Add custom charge to order
            OrderCharges::create([
                'order_id' => $orderId,
                'charge_id' => null, // Custom charge has no charge_id
                'amount' => $request->amount,
                'calculated_amount' => $calculatedAmount,
                'type' => $request->type,
                'name' => $request->name,
            ]);

            // Update order total
            $this->updateOrderTotal($order);

            return response()->json([
                'success' => true,
                'message' => 'Custom charge added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add custom charge: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove charge from order
     */
   public function addDeliveryCharge(Request $request, $orderId)
    {
        Log::info('addDeliveryCharge called', [
            'order_id' => $orderId,
            'user_id'  => auth()->id(),
            'input'    => $request->all(),
        ]);

        $validator = Validator::make($request->all(), [
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Find order by ULID string, not numeric ID
            $order = CustomerOrder::where('id', $orderId)
                ->where('assigned_staff_id', auth()->id())
                ->first();

            if (!$order) {
                Log::warning('Order not found or not assigned', [
                    'order_id' => $orderId,
                    'auth_id'  => auth()->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or not assigned to you',
                ], 404);
            }

            // Update delivery charge
            $deliveryCharge = (float) $request->delivery_charge;
            $foodTotal = (float) $order->order_total_price;
            $finalTotal = $foodTotal + $deliveryCharge;

            $order->delivery_charge = $deliveryCharge;
            $order->delivery_charge_added_by = auth()->id();
            // If you have a final_total column:
            // $order->final_total = $finalTotal;
            $order->save();

            Log::info('Delivery charge updated successfully', [
                'order_id' => $orderId,
                'delivery_charge' => $deliveryCharge,
                'food_total' => $foodTotal,
                'final_total' => $finalTotal,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Delivery charge updated successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_total_price' => $foodTotal,
                    'delivery_charge' => $deliveryCharge,
                    'final_total' => $finalTotal,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in addDeliveryCharge', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery charge: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove delivery charge from order
     */
    public function removeDeliveryCharge($orderId)
    {
        try {
            $order = CustomerOrder::where('id', $orderId)
                ->where('assigned_staff_id', auth()->id())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or not assigned to you',
                ], 404);
            }

            $order->delivery_charge = 0;
            $order->delivery_charge_added_by = null;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Delivery charge removed successfully',
                'data' => [
                    'order_id' => $order->id,
                    'order_total_price' => (float) $order->order_total_price,
                    'delivery_charge' => 0,
                    'final_total' => (float) $order->order_total_price,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove delivery charge: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Update order total with charges
     */
    private function updateOrderTotal($order)
    {
        $orderCharges = OrderCharges::where('order_id', $order->id)->get();
        $totalCharges = $orderCharges->sum('calculated_amount');
        
        // You might want to store this in a separate field like order_final_total
        // or recalculate on the fly when displaying
        // For now, we'll just return the charges with the order
    }
    
}

