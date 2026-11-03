<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case Ordered = 'Ordered';                 // New order placed
    case Preparing = 'preparing';             // Kitchen is preparing the food
    case ReadyToDeliver = 'ready_to_deliver'; // Food ready for delivery or serving
    case DeliveryOnTheWay = 'delivery_on_the_way'; // Delivery person picked up
    case Delivered = 'delivered';             // Order delivered to customer
    case Completed = 'completed';             // Order finalized / paid
    case Cancelled = 'cancelled';             // Order cancelled

    public function label(): string
    {
        return match ($this) {
            self::Ordered => 'New',
            self::Preparing => 'Preparing',
            self::ReadyToDeliver => 'Ready',
            self::DeliveryOnTheWay => 'On the way',
            self::Delivered => 'Delivered',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    // Tailwind classes for the status badge
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Ordered => 'bg-rose-50 text-rose-700 ring-rose-600/20',
            self::Preparing => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::ReadyToDeliver => 'bg-sky-50 text-sky-700 ring-sky-600/20',
            self::DeliveryOnTheWay => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
            self::Delivered => 'bg-teal-50 text-teal-700 ring-teal-600/20',
            self::Completed => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::Cancelled => 'bg-gray-100 text-gray-600 ring-gray-500/20',
        };
    }

    // Hex colour for charts
    public function color(): string
    {
        return match ($this) {
            self::Ordered => '#e11d48',
            self::Preparing => '#d97706',
            self::ReadyToDeliver => '#0284c7',
            self::DeliveryOnTheWay => '#4f46e5',
            self::Delivered => '#0d9488',
            self::Completed => '#059669',
            self::Cancelled => '#9ca3af',
        };
    }
}
