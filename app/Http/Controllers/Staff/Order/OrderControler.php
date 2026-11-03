<?php

namespace App\Http\Controllers\Staff\Order;

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

class OrderControler extends Controller
{
    public function index(): View
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
            ->orderBy('created_at', 'asc')
            ->get();

        $deliveryboys = User::where('location_id',$locationId)->where('role',3)->get();

        $myAssignedCount = CustomerOrder::where('location_id', $locationId)
            ->where('assigned_staff_id', $staffId)
            ->count();
            
        $availableCount = CustomerOrder::where('location_id', $locationId)
            ->whereNull('assigned_staff_id')
            ->count();

        $totalCount = CustomerOrder::where('location_id', $locationId)
            ->where('assigned_staff_id', $staffId)
            ->where('order_status', 'completed')
            ->count();


        // Today's numbers for the summary cards (orders are saved in the restaurant's local time)
        $today = now($locationId == 4 ? 'Asia/Colombo' : 'Asia/Bangkok')->toDateString();
        $todayOrders = CustomerOrder::where('location_id', $locationId)->whereDate('created_at', $today);

        return view('company.staff.order.index', [
            'customerOrder' => $orders,
            'currentLocationId' => $locationId,
            'currentStaffId' => $staffId,
            'myAssignedCount' => $myAssignedCount,
            'availableCount' => $availableCount,
            'totalCount' => $totalCount,
            'deliveryboys' => $deliveryboys,
            'location' => $user->location,
            'newCount' => CustomerOrder::where('location_id', $locationId)->where('order_status', 'Ordered')->count(),
            'withRidersCount' => CustomerOrder::where('location_id', $locationId)->whereNotNull('assigned_staff_id')
                ->whereNotIn('order_status', ['delivered', 'completed', 'cancelled'])->count(),
            'completedTodayCount' => (clone $todayOrders)->whereIn('order_status', ['delivered', 'completed'])->count(),
            'todaySales' => (clone $todayOrders)->where('order_status', '!=', 'cancelled')->sum('order_total_price'),
        ]);
    }
    
    public function assignOrder(Request $request, $orderId)
    {
        try {
            $request->validate([
                'staff_id' => 'required|exists:users,id'
            ]);
            
            // Find the order by ID (string ID)
            $order = CustomerOrder::find($orderId);
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            
            \Log::info('Assigning order', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'staff_id' => $request->staff_id,
                'current_staff' => Auth::id()
            ]);
            
            // Get the staff member
            $staff = User::findOrFail($request->staff_id);
            
            // Check if staff can accept more orders (optional limit)
            $assignedOrdersCount = CustomerOrder::where('assigned_staff_id', $staff->id)
                ->whereIn('order_status', ['ordered', 'preparing', 'ready_to_deliver', 'delivery_on_the_way'])
                ->count();
                
            if ($assignedOrdersCount >= 10) { // Adjust limit as needed
                return response()->json([
                    'success' => false,
                    'message' => $staff->name . ' already has ' . $assignedOrdersCount . ' assigned orders. Please select another staff member.'
                ], 400);
            }
            
            // Assign the order
            $order->assigned_staff_id = $request->staff_id;
            $order->save();
            
            // Create history record
            OrderHistory::create([
                'order_id' => $order->id,
                'staff_id' => Auth::id(),
                'action' => 'assigned',
                'notes' => 'Order assigned to ' . $staff->name,
                'old_status' => null,
                'new_status' => $order->order_status,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order #' . $order->order_code . ' assigned to ' . $staff->name . ' successfully',
                'order_code' => $order->order_code,
                'staff_name' => $staff->name
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors()['staff_id'] ?? [])
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Order assignment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    
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


        return view('company.staff.order.accept_order', [
            'customerOrder' => $orders,
            'currentLocationId' => $locationId,
            'currentStaffId' => $staffId,
        ]);
    }
    
    public function completed_order(): View
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
            ->where('order_status','completed')
            ->orderBy('created_at', 'asc')
            ->get();


        return view('company.staff.order.completed_order', [
            'customerOrder' => $orders,
            'currentLocationId' => $locationId,
            'currentStaffId' => $staffId,
        ]);
    }

    /*
    *  Funtion to view create file
    */
    public function create() : View
    {
        $table = DiningTable::paginate(10);

        return view('company.staff.order.create', ['diningTable' => $table]);
    }

    /*
    *  Function to store data of dining table number
    */
    public function store(Request $request) : RedirectResponse
    {
        $validator = Validator::make($request->only('table_number'), [
            'table_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Retrieve a submitted input of table_number
        $validated = $validator->safe()->only('table_number');

        $tableName = 'Dining Table';

        // Get dining table
        $diningTable = RestaurantItem::where('item_name', 'like', $tableName)->first();

        // Get quantity
        $tableQuantity = $diningTable->quantity;

        // Get number of table no. registered
        $tableRegistered = DiningTable::count();

        // Check if all dining table is registered or not
        if ($tableRegistered > $tableQuantity) {
            return back()->withErrors([
                'validation-error-message' => 'All table registered already. Cannot registered more than table have.'
            ])->withInput();
        }

        // Check if table_name already exists
        $exists = DiningTable::where('table_name', $validated)->first();

        if ($exists) {
            return back()->withErrors([
                'validation-error-message' => 'Table number is already registered. Please enter other number.'
            ])->withInput();
        }

        $created = DiningTable::create([
            'table_name' => $validated['table_number'],
            'isOccupied' => false,
        ]);

        Log::info($created);

        return back()->with('success-message', 'Table number successfully registered.');
    }

    /*
    *  Function to accept order (assign to current staff)
    */
    public function acceptOrder($id): RedirectResponse
    {
        $staffId = Auth::id();
        $order = CustomerOrder::findOrFail($id);

        // Check if order is already assigned to someone else
        if ($order->assigned_staff_id && $order->assigned_staff_id != $staffId) {
            return back()->withErrors(['error-message' => 'This order is already accepted by another staff member.']);
        }

        $order->update([
            'assigned_staff_id' => $staffId
        ]);

        // Log history
        OrderHistory::create([
            'order_id' => $order->id,
            'staff_id' => $staffId,
            'action' => 'accepted',
            'notes' => 'Order accepted by staff'
        ]);

        Log::info("Order {$order->id} accepted by staff {$staffId}");

        return redirect()->route('accept-order')->with('success-message', 'Order successfully accepted.');

    }

    /*
    *  Function to unaccept/cancel assignment
    */
    public function unacceptOrder($id): RedirectResponse
    {
        $staffId = Auth::id();
        $order = CustomerOrder::findOrFail($id);

        // Check if the order is assigned to current staff
        if ($order->assigned_staff_id != $staffId) {
            return back()->withErrors(['error-message' => 'You can only unaccept orders assigned to you.']);
        }

        $order->update([
            'assigned_staff_id' => null
        ]);

        // Log history
        OrderHistory::create([
            'order_id' => $order->id,
            'staff_id' => $staffId,
            'action' => 'unaccepted',
            'notes' => 'Order unaccepted by staff'
        ]);

        Log::info("Order {$order->id} unaccepted by staff {$staffId}");

        return redirect()->route('customer-order')->with('success-message', 'Order successfully accepted.');
    }
    
    public function updateStatus(Request $request, $id)
    {   
        $validated = $request->validate([
            'order_status' => 'required|in:ordered,preparing,ready_to_deliver,delivery_on_the_way,delivered,completed,cancelled'
        ]);
        
        $staffId = Auth::id();
        $order = CustomerOrder::findOrFail($id);
        $oldStatus = $order->order_status;
        // Check if trying to mark as completed without payment
        if ($validated['order_status'] === 'completed' && !$order->isPaid) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot mark order as completed. Payment status must be "Paid" first.',
                    'error' => 'payment_required'
                ], 422);
            }
            return back()->with('error-message', 'Cannot mark order as completed. Payment status must be "Paid" first.');
        }
        
        $order->update(['order_status' => $validated['order_status']]);
        
        // Log history
        OrderHistory::create([
            'order_id' => $order->id,
            'staff_id' => $staffId,
            'action' => 'status_changed',
            'old_status' => $oldStatus,
            'new_status' => $validated['order_status'],
            'notes' => 'Order status updated'
        ]);
        
        // Return JSON response for AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully to ' . ucwords(str_replace('_', ' ', $validated['order_status'])) . '.',
                'new_status' => $validated['order_status'],
                'order_id' => $order->id
            ]);
        }
        
        // Fallback for non-AJAX requests
        return back()->with('success-message', 'Order status updated successfully to ' . ucwords(str_replace('_', ' ', $validated['order_status'])) . '.');
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
    public static function getStatusClass($status)
    {
        // Handle both string and OrderStatusEnum cases
        if ($status instanceof OrderStatusEnum) {
            $statusValue = $status->value;
        } else {
            $statusValue = $status;
        }

        // Normalize the status to handle case inconsistencies
        $normalizedStatus = strtolower($statusValue);
        
        switch($normalizedStatus) {
            case 'ordered': return 'status-ordered';
            case 'preparing': return 'status-preparing';
            case 'ready_to_deliver': return 'status-ready';
            case 'delivery_on_the_way': return 'status-delivery';
            case 'delivered': return 'status-delivered';
            case 'completed': return 'status-completed';
            case 'cancelled': return 'status-cancelled';
            default: return 'status-ordered';
        }
    }

    // Helper method to get display text for status
    public static function getStatusDisplay($status)
    {
        // Handle both string and OrderStatusEnum cases
        if ($status instanceof OrderStatusEnum) {
            $statusValue = $status->value;
        } else {
            $statusValue = $status;
        }

        return ucwords(str_replace('_', ' ', $statusValue));
    }

    /*
    *  Function to get order history as JSON
    */
    public function getOrderHistoryJson($id)
    {
        $histories = OrderHistory::with('staff')
            ->where('order_id', $id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($history) {
                return [
                    'action' => $history->action,
                    'old_status' => $history->old_status,
                    'new_status' => $history->new_status,
                    'staff_name' => $history->staff->name ?? 'System',
                    'created_at' => $history->created_at->format('j M Y, g:i A'),
                    'notes' => $history->notes
                ];
            });

        return response()->json(['histories' => $histories]);
    }
}