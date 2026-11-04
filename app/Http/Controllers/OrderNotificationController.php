<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Models\CustomerOrder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderNotificationController extends Controller
{
    /**
     * Latest new (Ordered) orders for the logged-in admin / staff.
     * Polled by the notification bell in the admin and staff layouts.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Admin (1) sees every location, staff (2) only their own
        $query = CustomerOrder::with(['customer', 'location'])
            ->where('order_status', OrderStatusEnum::Ordered)
            ->when($user->role != 1, function ($query) use ($user) {
                return $query->where('location_id', $user->location_id);
            });

        $pendingCount = (clone $query)->count();
        $ordersUrl = $user->role == 1 ? route('delivery-management') : route('customer-order');

        $orders = $query->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) use ($ordersUrl) {
                // Orders are saved in the location's local time (see LocationController::checkout)
                $timezone = $order->location_id == 4 ? 'Asia/Colombo' : 'Asia/Bangkok';
                $createdAt = Carbon::parse($order->getRawOriginal('created_at'), $timezone);

                return [
                    'id' => $order->id,
                    'order_code' => $order->order_code,
                    'customer' => $order->customer->name ?? $order->customer_contact ?? 'Customer',
                    'total' => number_format((float) $order->order_total_price, 2),
                    'currency' => $order->location->currency ?? '',
                    'location' => $order->location->location_name ?? '',
                    'delivery_type' => $order->delivery_type,
                    'created_at' => $createdAt->toIso8601String(),
                    'created_human' => $createdAt->diffForHumans() . ' · ' . $createdAt->format('H:i'),
                    'url' => $ordersUrl . '?highlight=' . $order->id,
                ];
            });

        return response()->json([
            'pending_count' => $pendingCount,
            'orders' => $orders,
            'orders_url' => $ordersUrl,
        ]);
    }

    /**
     * Turn new order alerts on or off for the logged-in admin / staff.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        $user = Auth::user();
        $user->update(['order_notifications' => $validated['enabled']]);

        return response()->json(['enabled' => (bool) $user->order_notifications]);
    }
}
