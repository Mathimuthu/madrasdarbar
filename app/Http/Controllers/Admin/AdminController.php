<?php
namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    // Orders are saved in the restaurant's local time, so date ranges are worked out in that time too
    private const LOCAL_TIMEZONE = 'Asia/Bangkok';

    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    public function index(Request $request): View
    {
        $user = Auth::user();

        // ================================
        // FILTERS
        // ================================
        // Admin picks a location, everyone else is locked to their own
        $selectedLocation = $user->role == 1 ? $request->input('location_id') : $user->location_id;
        [$range, $dateFrom, $dateTo] = $this->resolveDateRange($request);

        $orders = fn () => CustomerOrder::query()
            ->when($selectedLocation, fn ($q) => $q->where('customer_orders.location_id', $selectedLocation))
            ->whereBetween('customer_orders.created_at', [$dateFrom->copy()->startOfDay(), $dateTo->copy()->endOfDay()]);

        // ================================
        // HEADLINE NUMBERS
        // ================================
        $totalOrders = $orders()->count();

        $statusCounts = $orders()
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $statusBreakdown = collect(OrderStatusEnum::cases())->map(fn ($status) => [
            'status' => $status,
            'total' => (int) ($statusCounts[$status->value] ?? 0),
        ]);

        $inProgressStatuses = [OrderStatusEnum::Preparing, OrderStatusEnum::ReadyToDeliver, OrderStatusEnum::DeliveryOnTheWay];
        $inProgressCount = $statusBreakdown->whereIn('status', $inProgressStatuses)->sum('total');
        $doneCount = $statusBreakdown->whereIn('status', [OrderStatusEnum::Delivered, OrderStatusEnum::Completed])->sum('total');
        $cancelledCount = $statusBreakdown->firstWhere('status', OrderStatusEnum::Cancelled)['total'];

        // Revenue per currency (cancelled orders excluded)
        $revenue = $orders()
            ->join('location', 'customer_orders.location_id', '=', 'location.location_id')
            ->where('order_status', '!=', OrderStatusEnum::Cancelled)
            ->select(
                'location.currency',
                DB::raw('SUM(order_total_price) as total'),
                DB::raw('SUM(CASE WHEN isPaid = 1 THEN order_total_price ELSE 0 END) as paid'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('location.currency')
            ->get();

        // Live numbers (not limited by the date range): what needs action right now
        $liveOrders = CustomerOrder::query()
            ->when($selectedLocation, fn ($q) => $q->where('location_id', $selectedLocation));
        $newOrdersNow = (clone $liveOrders)->where('order_status', OrderStatusEnum::Ordered)->count();
        $unassignedNow = (clone $liveOrders)->whereNull('assigned_staff_id')
            ->whereNotIn('order_status', [OrderStatusEnum::Completed, OrderStatusEnum::Delivered, OrderStatusEnum::Cancelled])
            ->count();

        // ================================
        // CHARTS
        // ================================
        $perDay = $orders()
            ->select(DB::raw('DATE(customer_orders.created_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->pluck('total', 'day');

        $dailyOrders = collect();
        for ($day = $dateFrom->copy(); $day->lte($dateTo); $day->addDay()) {
            $dailyOrders->push([
                'label' => $day->format('d M'),
                'total' => (int) ($perDay[$day->toDateString()] ?? 0),
            ]);
        }

        $topItems = CustomerOrderDetail::query()
            ->join('customer_orders', 'customer_order_details.order_id', '=', 'customer_orders.id')
            ->join('food_menus', 'customer_order_details.food_id', '=', 'food_menus.id')
            ->when($selectedLocation, fn ($q) => $q->where('customer_orders.location_id', $selectedLocation))
            ->whereBetween('customer_orders.created_at', [$dateFrom->copy()->startOfDay(), $dateTo->copy()->endOfDay()])
            ->where('customer_orders.order_status', '!=', OrderStatusEnum::Cancelled)
            ->select('food_menus.name', DB::raw('SUM(customer_order_details.quantity) as qty'))
            ->groupBy('food_menus.id', 'food_menus.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // ================================
        // LOCATION PERFORMANCE
        // ================================
        $perLocation = $orders()
            ->select(
                'customer_orders.location_id',
                DB::raw('COUNT(*) as orders'),
                DB::raw("SUM(CASE WHEN order_status != 'cancelled' THEN order_total_price ELSE 0 END) as revenue"),
                DB::raw("SUM(CASE WHEN order_status = 'Ordered' THEN 1 ELSE 0 END) as new_orders")
            )
            ->groupBy('customer_orders.location_id')
            ->get()
            ->keyBy('location_id');

        $locationList = $user->role == 1
            ? Location::orderBy('location_name')->get()
            : Location::where('location_id', $user->location_id)->get();

        $locationStats = $locationList
            ->when($selectedLocation, fn ($list) => $list->where('location_id', $selectedLocation))
            ->map(fn ($location) => [
                'location' => $location,
                'orders' => (int) ($perLocation[$location->location_id]->orders ?? 0),
                'revenue' => (float) ($perLocation[$location->location_id]->revenue ?? 0),
                'new_orders' => (int) ($perLocation[$location->location_id]->new_orders ?? 0),
            ])
            ->sortByDesc('orders')
            ->values();

        // ================================
        // TEAM & RECENT ORDERS
        // ================================
        $staffcount = User::where('role', 2)
            ->when($selectedLocation, fn ($q) => $q->where('location_id', $selectedLocation))->count();
        $deliveryGuyCount = User::where('role', 3)
            ->when($selectedLocation, fn ($q) => $q->where('location_id', $selectedLocation))->count();

        $recentOrders = $orders()
            ->with(['customer', 'location', 'assignedStaff'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('company.admin.dashboard', [
            'range'            => $range,
            'dateFrom'         => $dateFrom,
            'dateTo'           => $dateTo,
            'locationList'     => $locationList,
            'selectedLocation' => $selectedLocation,
            'totalOrders'      => $totalOrders,
            'statusBreakdown'  => $statusBreakdown,
            'inProgressCount'  => $inProgressCount,
            'doneCount'        => $doneCount,
            'cancelledCount'   => $cancelledCount,
            'revenue'          => $revenue,
            'newOrdersNow'     => $newOrdersNow,
            'unassignedNow'    => $unassignedNow,
            'dailyOrders'      => $dailyOrders,
            'topItems'         => $topItems,
            'locationStats'    => $locationStats,
            'staffcount'       => $staffcount,
            'deliveryGuyCount' => $deliveryGuyCount,
            'recentOrders'     => $recentOrders,
        ]);
    }

    /**
     * @return array{0: string, 1: Carbon, 2: Carbon}
     */
    private function resolveDateRange(Request $request): array
    {
        $today = Carbon::now(self::LOCAL_TIMEZONE)->startOfDay();
        $range = $request->input('range', '7d');

        switch ($range) {
            case 'today':
                return [$range, $today->copy(), $today->copy()];
            case 'yesterday':
                return [$range, $today->copy()->subDay(), $today->copy()->subDay()];
            case '30d':
                return [$range, $today->copy()->subDays(29), $today->copy()];
            case 'month':
                return [$range, $today->copy()->startOfMonth(), $today->copy()];
            case 'custom':
                try {
                    $from = Carbon::parse($request->input('date_from'), self::LOCAL_TIMEZONE)->startOfDay();
                    $to = Carbon::parse($request->input('date_to', $today->toDateString()), self::LOCAL_TIMEZONE)->startOfDay();
                } catch (\Exception $e) {
                    break;
                }
                if ($from->gt($to)) {
                    [$from, $to] = [$to, $from];
                }
                // Keep the daily chart readable
                if ($from->diffInDays($to) > 366) {
                    $from = $to->copy()->subDays(366);
                }
                return [$range, $from, $to];
        }

        return ['7d', $today->copy()->subDays(6), $today->copy()];
    }

    public function DeliveryManagement(Request $request): View
    {
        // Get filter values
        $selectedStaff         = $request->input('staff_id');
        $selectedLocation      = $request->input('location_id');
        $selectedStatus        = $request->input('status');
        $selectedPaymentStatus = $request->input('payment_status');
        $selectedDeliveryType  = $request->input('delivery_type');
        $dateFrom              = $request->input('date_from');
        $dateTo                = $request->input('date_to');
        $searchQuery           = $request->input('search'); // This will now search order_code too!

        // Build query with relationships
        $ordersQuery = CustomerOrder::with([
            'customer',
            'assignedStaff',
            'location',
            'customerOrderDetail.foodMenu',
            'histories' => function($query) {
                $query->with('staff')->orderBy('created_at', 'desc');
            }
        ]);

        // Apply filters
        if ($selectedStaff) {
            $ordersQuery->where('assigned_staff_id', $selectedStaff);
        }

        if ($selectedLocation) {
            $ordersQuery->where('location_id', $selectedLocation);
        }

        if ($selectedPaymentStatus !== null && $selectedPaymentStatus !== '') {
            $ordersQuery->where('isPaid', (bool)$selectedPaymentStatus);
        }

        if ($selectedDeliveryType) {
            $ordersQuery->where('delivery_type', $selectedDeliveryType);
        }

        if ($dateFrom) {
            $ordersQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $ordersQuery->whereDate('created_at', '<=', $dateTo);
        }

        // SEARCH BY ORDER CODE (MDPHU-000123), CUSTOMER NAME, OR PHONE
        if ($searchQuery) {
            $searchQuery = trim($searchQuery);

            $ordersQuery->where(function($query) use ($searchQuery) {
                // Search in order_code (main one now!
                $query->where('order_code', 'LIKE', '%' . $searchQuery . '%')

                    // Also search in customer contact (phone)
                    ->orWhere('customer_contact', 'LIKE', '%' . $searchQuery . '%')

                    // Search in customer name
                    ->orWhereHas('customer', function($q) use ($searchQuery) {
                        $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                            ->orWhere('mobile', 'LIKE', '%' . $searchQuery . '%');
                    });
            });
        }

        // Counts for the status tabs (every filter except status itself)
        $statusCounts = (clone $ordersQuery)
            ->reorder()
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        if ($selectedStatus) {
            $ordersQuery->where('order_status', $selectedStatus);
        }

        // Final: order by latest first + paginate
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Dropdown data
        $staffList     = User::whereIn('role', [2, 3])->orderBy('name')->get();
        $locationList  = Location::orderBy('location_name')->get();
        $statusList    = OrderStatusEnum::cases();
        $deliveryTypes = CustomerOrder::whereNotNull('delivery_type')->distinct()->orderBy('delivery_type')->pluck('delivery_type');

        return view('company.admin.delivery-management', compact(
            'orders',
            'statusCounts',
            'staffList',
            'locationList',
            'statusList',
            'deliveryTypes',
            'selectedStaff',
            'selectedLocation',
            'selectedStatus',
            'selectedPaymentStatus',
            'selectedDeliveryType',
            'dateFrom',
            'dateTo',
            'searchQuery'
        ));
    }
}
