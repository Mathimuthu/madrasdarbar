<?php

namespace App\Http\Controllers\Delivery;
use App\Services\FirebaseNotificationService;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\DiningTable;
use App\Models\RestaurantItem;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    public function delivery_order(): View
    {
        $user = Auth::user();

        if (!$user || !$user->location_id) {
            return view('company.auth.login')->withErrors('User is not authenticated or location is not assigned.');
        }

        $locationId = $user->location_id;
        $staffId = $user->id;

        $orders = CustomerOrder::with(['customer', 'diningTable', 'customerOrderDetail.foodMenu', 'assignedStaff','location'])
            ->when($locationId, function($query) use ($locationId) {
                return $query->where('location_id', $locationId);
            })
            ->where('assigned_staff_id', '!=', 'null')
            ->where('order_status', '!=', 'completed')
            ->visibleToStaff($staffId)
            ->orderByRaw("
                CASE 
                    WHEN order_status = 'preparing' THEN 1
                    WHEN order_status = 'ready_to_deliver' THEN 2
                    WHEN order_status = 'delivery_on_the_way' THEN 3
                    WHEN order_status = 'delivered' THEN 4
                    WHEN order_status = 'cancelled' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('created_at', 'asc')
            ->get();


        $todayOrderCount = CustomerOrder::where('location_id', $locationId)
            ->where('assigned_staff_id', $staffId)
            ->where('order_status', '!=', 'completed')
            ->whereDate('created_at', Carbon::today())
            ->count();
             
        return view('company.delivery.index', [
            'customerOrder' => $orders,
            'currentLocationId' => $locationId,
            'currentStaffId' => $staffId,
            'todayOrderCount' => $todayOrderCount
        ]);
    }
 
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:ordered,preparing,ready_to_deliver,delivery_on_the_way,delivered,completed,cancelled'
        ]);
    
        $staffId = Auth::id();
        $order = CustomerOrder::with('customer')->findOrFail($id);
        $oldStatus = $order->order_status;
    
        if ($validated['order_status'] === 'completed' && !$order->isPaid) {
            return response()->json([
                'success' => false,
                'message' => 'Payment must be completed first'
            ], 422);
        }
    
        $order->update(['order_status' => $validated['order_status']]);
    
        OrderHistory::create([
            'order_id' => $order->id,
            'staff_id' => $staffId,
            'action' => 'status_changed',
            'old_status' => $oldStatus,
            'new_status' => $validated['order_status'],
            'notes' => 'Order status updated'
        ]);
    
        // 🔔 SEND NOTIFICATION ONLY FOR 3 STATUS
        $notifyStatuses = [
            'preparing' => 'Your order is being prepared 🍳',
            'delivery_on_the_way' => 'Your order is on the way 🚚',
            'delivered' => 'Your order has been delivered 🎉',
        ];
    
        if (isset($notifyStatuses[$validated['order_status']])) {
    
            $customer = $order->customer;

            if ($customer && $customer->fcm_token) {
                app(FirebaseNotificationService::class)->sendToToken(
                    $customer->fcm_token,
                    'Order Update',
                    $notifyStatuses[$validated['order_status']],
                    [
                        'order_id' => (string) $order->id,
                        'status' => $validated['order_status']
                    ]
                );
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'new_status' => $validated['order_status']
        ]);
    }
    
    // Add similar method for payment update
    public function updatePaymentStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'is_paid' => 'required|in:0,1'
        ]);
        
        $staffId = Auth::id();
        $order = CustomerOrder::findOrFail($id);
        $oldPaymentStatus = $order->isPaid;
        
        $order->update(['isPaid' => $validated['is_paid']]);
        
        // Log history
        OrderHistory::create([
            'order_id' => $order->id,
            'staff_id' => $staffId,
            'action' => 'payment_status_changed',
            'old_status' => $oldPaymentStatus ? 'paid' : 'not_paid',
            'new_status' => $validated['is_paid'] ? 'paid' : 'not_paid',
            'notes' => 'Payment status updated'
        ]);
        
        // Return JSON response for AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully to ' . ($validated['is_paid'] ? 'Paid' : 'Not Paid') . '.',
                'is_paid' => $validated['is_paid'],
                'order_id' => $order->id
            ]);
        }
        
        // Fallback for non-AJAX requests
        return back()->with('success-message', 'Payment status updated successfully.');
    }

}