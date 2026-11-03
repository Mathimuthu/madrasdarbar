<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>@yield('title', 'Order Management')</title>
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --success-color: #10b981;
            --success-hover: #059669;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --background-color: #f8fafc;
            --surface-color: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }

        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-badge::before {
            content: '';
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
        }

        .status-ordered {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-ordered::before {
            background-color: #f59e0b;
        }

        .status-preparing {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-preparing::before {
            background-color: #3b82f6;
        }

        .status-ready_to_deliver {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        .status-ready_to_deliver::before {
            background-color: #6366f1;
        }

        .status-delivery_on_the_way {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-delivery_on_the_way::before {
            background-color: #f59e0b;
        }

        .status-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-delivered::before {
            background-color: #10b981;
        }

        .status-completed {
            background-color: #ccfbf1;
            color: #0d9488;
        }
        .status-completed::before {
            background-color: #14b8a6;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-cancelled::before {
            background-color: #ef4444;
        }

        .payment-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .payment-paid {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .payment-not-paid {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }

        @media (max-width: 768px) {
            .mobile-only {
                display: block !important;
            }
            .desktop-only {
                display: none !important;
            }
        }

        @media (min-width: 769px) {
            .mobile-only {
                display: none !important;
            }
            .desktop-only {
                display: block !important;
            }
        }

        .food-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin: 0.125rem;
        }

        .table-row-hover:hover {
            background-color: #f8fafc;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
            max-width: 24rem;
        }

        .toast-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .toast-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .assignment-dropdown {
            position: relative;
            display: inline-block;
        }

        .assignment-select {
            min-width: 180px;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: white;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .assignment-select:hover {
            border-color: #9ca3af;
        }

        .assignment-select:focus {
            outline: none;
            ring: 2px;
            ring-color: #3b82f6;
            border-color: #3b82f6;
        }
          .table-container {
            overflow-x: auto;
        }

        .table-cell-nowrap {
            white-space: nowrap;
        }

        .table-cell-food {
            min-width: 200px;
            max-width: 300px;
            white-space: nowrap;
            overflow-x: auto;
        }

        .table-cell-food::-webkit-scrollbar {
            height: 3px;
        }

        .table-cell-food::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .table-cell-food::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .table-cell-food::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .table-cell-address {
            min-width: 180px;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-cell-notes {
            min-width: 150px;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-cell-small {
            white-space: nowrap;
        }

        .food-tags-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 4px;
            padding: 2px 0;
            min-height: 32px;
            align-items: center;
        }

        .food-tags-container::-webkit-scrollbar {
            height: 3px;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="sidebarOverlay"></div>

    <div class="mobile-sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300" id="mobileSidebar">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                @if (Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class='bx bx-user text-blue-600 text-lg'></i>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-500">Staff Member</p>
                </div>
                <button onclick="closeMobileSidebar()" class="p-1 hover:bg-gray-100 rounded-lg">
                    <i class='bx bx-x text-xl text-gray-500'></i>
                </button>
            </div>
        </div>
        
        <div class="p-4 space-y-1">
            <a href="{{ route('customer-order') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('customer-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}"
               onclick="closeMobileSidebar()">
                <i class='bx bx-list-check text-xl'></i>
                <span class="font-medium">Manage Orders</span>
            </a>
            <a href="{{ route('completed-order') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('completed-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}"
               onclick="closeMobileSidebar()">
                <i class='bx bx-check text-xl'></i>
                <span class="font-medium">Completed Orders</span>
            </a>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="flex items-center justify-center space-x-2 w-full px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all font-medium">
                    <i class='bx bx-log-out-circle text-xl'></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <nav class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <button onclick="toggleMobileSidebar()" 
                        class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-all">
                    <i class='bx bx-menu text-2xl text-gray-700'></i>
                </button>

                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-package text-white text-lg'></i>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">OrderHub</span>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer-order') }}" 
                       class="px-4 py-2 rounded-lg transition-all {{ request()->routeIs('customer-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class='bx bx-list-check mr-2'></i>
                        Manage Orders
                    </a>
                    <a href="{{ route('completed-order') }}" 
                       class="px-4 py-2 rounded-lg transition-all {{ request()->routeIs('completed-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class='bx bx-check mr-2'></i>
                        Completed Orders
                    </a>
                </div>

                <div class="relative flex items-center">
                    @include('company.partials.order-notifications')
                    <button onclick="toggleProfileDropdown()"
                            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 transition-all">
                        @if (Auth::user()->photo)
                            <img src="{{ asset(Auth::user()->photo) }}" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class='bx bx-user text-blue-600'></i>
                            </div>
                        @endif
                        <i class='bx bx-chevron-down text-gray-500'></i>
                    </button>
                    
                    <div id="profileDropdown" 
                         class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 hidden">
                        <a href="{{ route('staff-profile-show', Auth::user()->id) }}" 
                           class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class='bx bx-user-circle'></i>
                            <span>Update Profile</span>
                        </a>
                        <a href="#" 
                           class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class='bx bx-key'></i>
                            <span>Change Password</span>
                        </a>
                        <div class="border-t border-gray-200 mt-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center space-x-2 w-full px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class='bx bx-log-out-circle'></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        @php
            $currency = $location->currency ?? '';
            $hour = now($currentLocationId == 4 ? 'Asia/Colombo' : 'Asia/Bangkok')->hour;
            $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
            $openOrders = $customerOrder->whereNull('assigned_staff_id');
            $assignedOrders = $customerOrder->whereNotNull('assigned_staff_id');
            $orderTypes = $openOrders->pluck('delivery_type')->filter()->unique()->values();
            $paymentTypes = $openOrders->pluck('payment_type')->filter()->unique()->values();
        @endphp

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500 flex items-center gap-1">
                    <i class='bx bx-map'></i> {{ $location->location_name ?? 'Your location' }}
                    <span class="mx-1">·</span>
                    <span id="liveClock">{{ now($currentLocationId == 4 ? 'Asia/Colombo' : 'Asia/Bangkok')->format('l, j M · g:i A') }}</span>
                </p>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mt-1">{{ $greeting }}, {{ Str::before(Auth::user()->name, ' ') }}</h1>
                <p class="text-gray-500 mt-1">New orders appear here automatically. Assign each one to a delivery rider.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openAssignedModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class='bx bx-cycling text-lg text-indigo-600'></i> With riders
                    <span class="rounded-full bg-indigo-50 text-indigo-700 px-2 text-xs">{{ $assignedOrders->count() }}</span>
                </button>
                <button id="refreshBtn"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-sm">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>

        @if (session('success-message'))
            <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800">
                <i class='bx bx-check-circle text-xl'></i>
                <span class="font-medium">{{ session('success-message') }}</span>
            </div>
        @endif

        @if (session('error-message'))
            <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                <i class='bx bx-error-circle text-xl'></i>
                <span class="font-medium">{{ session('error-message') }}</span>
            </div>
        @endif

        {{-- Summary cards --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl p-5 text-white bg-gradient-to-br from-rose-500 to-orange-500 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-white/90">New orders</p>
                    <i class='bx bx-bell text-2xl text-white/90'></i>
                </div>
                <p class="text-4xl font-bold mt-2">{{ $newCount }}</p>
                <p class="text-xs text-white/80 mt-1">{{ $availableCount }} waiting for a rider</p>
            </div>
            <div class="rounded-2xl p-5 bg-white border border-gray-200/70 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Out with riders</p>
                    <span class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 grid place-items-center"><i class='bx bx-cycling text-xl'></i></span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $withRidersCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Assigned, not delivered yet</p>
            </div>
            <div class="rounded-2xl p-5 bg-white border border-gray-200/70 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Completed today</p>
                    <span class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 grid place-items-center"><i class='bx bx-check-double text-xl'></i></span>
                </div>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $completedTodayCount }}</p>
                <p class="text-xs text-gray-500 mt-1">Delivered or completed</p>
            </div>
            <div class="rounded-2xl p-5 bg-white border border-gray-200/70 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">Today's sales</p>
                    <span class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 grid place-items-center"><i class='bx bx-wallet text-xl'></i></span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $currency }} {{ number_format($todaySales, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Excluding cancelled orders</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 shadow-sm p-4 space-y-3">
            <div class="flex flex-col lg:flex-row gap-3">
                <label class="relative flex-1">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Search order code, customer, phone or food…" autocomplete="off"
                           class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                </label>
                <div class="grid grid-cols-3 gap-2 lg:w-[480px]">
                    <select id="dateFilter" class="px-3 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">Any date</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">This week</option>
                        <option value="month">This month</option>
                    </select>
                    <select id="typeFilter" class="px-3 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">All order types</option>
                        @foreach ($orderTypes as $type)
                            <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <select id="paymentFilter" class="px-3 py-2.5 rounded-xl border border-gray-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                        <option value="">All payments</option>
                        @foreach ($paymentTypes as $type)
                            <option value="{{ strtolower($type) }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="clearFilters" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">Clear</button>
            </div>
            <div id="activeFilters" class="hidden">
                <div id="filterBadges" class="flex flex-wrap gap-2"></div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="bg-white rounded-2xl border border-gray-200/70 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    Orders to assign
                    <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-sm font-semibold rounded-full" id="resultCount">{{ $openOrders->count() }}</span>
                </h2>
                <p class="text-xs text-gray-500 hidden sm:block">Oldest first · pick a rider to send an order out</p>
            </div>

            <div class="table-container">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-500 bg-gray-50/70">
                            <th class="py-3 px-4 font-medium">Order</th>
                            <th class="py-3 px-4 font-medium">Items</th>
                            <th class="py-3 px-4 font-medium">Customer</th>
                            <th class="py-3 px-4 font-medium">Address</th>
                            <th class="py-3 px-4 font-medium text-right">Amount</th>
                            <th class="py-3 px-4 font-medium">Assign rider</th>
                            <th class="py-3 px-4"></th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody" class="divide-y divide-gray-100">
                        @foreach ($openOrders as $order)
                            @php
                                // Build the full address from all components
                                $fullAddress = collect([$order->customer->door_no ?? null, $order->customer->area ?? null, $order->customer->address ?? null])
                                    ->filter(fn ($part) => !empty($part) && $part !== 'NULL')
                                    ->implode(', ') ?: 'Address not available';

                                $latlon = $order->customer->latlon ?? '';
                                $hasValidCoordinates = !empty($latlon) && $latlon !== 'NULL' && strpos($latlon, ',') !== false;
                                $googleMapsUrl = $hasValidCoordinates ? 'https://www.google.com/maps?q=' . urlencode(trim($latlon)) : '';

                                $isAssignedToMe = $order->assignedStaff && $order->assignedStaff->id == $currentStaffId;
                                $type = strtolower($order->delivery_type ?? '');
                            @endphp
                            <tr class="align-top hover:bg-gray-50/80 transition"
                                data-order-id="{{ $order->id }}"
                                data-order-code="{{ $order->order_code }}"
                                data-search-text="{{ strtolower($order->order_code . ' ' . ($order->customer->name ?? '') . ' ' . ($order->customer->mobile ?? '') . ' ' . $order->customer_contact . ' ' . collect($order->customerOrderDetail)->pluck('foodMenu.name')->implode(' ')) }}"
                                data-date="{{ $order->created_at->format('Y-m-d') }}"
                                data-type="{{ $type }}"
                                data-payment="{{ strtolower($order->payment_type ?? '') }}">
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <p class="font-mono font-semibold text-gray-900">{{ $order->order_code }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('j M · g:i A') }}</p>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $order->order_status->badgeClasses() }}">
                                            {{ $order->order_status->label() }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-600">
                                            <i class='bx {{ str_contains($type, 'pick') ? 'bx-shopping-bag' : (str_contains($type, 'dine') ? 'bx-restaurant' : 'bx-cycling') }}'></i>
                                            {{ ucfirst($order->delivery_type) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1 max-w-[260px]">
                                        @foreach ($order->customerOrderDetail as $orderDetail)
                                            <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-800 px-2.5 py-1 text-[13px] font-medium">
                                                <span class="text-emerald-700 font-bold">{{ $orderDetail->quantity }}×</span> {{ $orderDetail->foodMenu->name ?? 'Item' }}
                                            </span>
                                        @endforeach
                                    </div>
                                    @if ($order->order_notes)
                                        <p class="mt-2 text-[13px] text-slate-700 bg-white border border-dashed border-slate-300 rounded-lg px-2.5 py-1 max-w-[260px]">
                                            <i class='bx bx-note'></i> {{ $order->order_notes }}
                                        </p>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-medium text-gray-900">{{ $order->customer->name ?? 'Guest' }}</p>
                                    <a href="tel:{{ $order->customer_contact }}" class="text-xs text-blue-600 hover:underline">
                                        <i class="bi bi-telephone"></i> {{ $order->customer_contact ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="py-4 px-4 max-w-[240px]">
                                    @if ($hasValidCoordinates)
                                        <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener noreferrer"
                                           class="flex items-start gap-1 text-blue-600 hover:text-blue-700" title="{{ $fullAddress }}">
                                            <i class="bi bi-geo-alt mt-0.5"></i><span class="line-clamp-2">{{ $fullAddress }}</span>
                                        </a>
                                    @else
                                        <div class="flex items-start gap-1 text-gray-600" title="{{ $fullAddress }}">
                                            <i class="bi bi-geo-alt mt-0.5 opacity-50"></i><span class="line-clamp-2">{{ $fullAddress }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">{{ $order->location->currency ?? $currency }} {{ number_format($order->order_total_price, 2) }}</p>
                                    <p class="text-xs mt-0.5">
                                        <span class="text-gray-500">{{ ucfirst($order->payment_type) }}</span> ·
                                        <span class="{{ $order->isPaid ? 'text-emerald-600' : 'text-amber-600' }} font-medium">{{ $order->isPaid ? 'Paid' : 'Unpaid' }}</span>
                                    </p>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="assignment-dropdown">
                                        <select class="assignment-select" data-order-id="{{ $order->id }}" onchange="assignDeliveryBoy(this)">
                                            <option value="">Choose rider…</option>
                                            @foreach ($deliveryboys as $deliveryboy)
                                                <option value="{{ $deliveryboy->id }}">
                                                    {{ $deliveryboy->name }}
                                                    @if ($deliveryboy->assigned_count > 0)
                                                        ({{ $deliveryboy->assigned_count }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($deliveryboys->isEmpty())
                                        <p class="text-xs text-gray-400 mt-1">No riders at this location</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    @if ($isAssignedToMe)
                                        <form action="{{ route('order.unaccept', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="action-btn bg-red-600 text-white hover:bg-red-700"
                                                    onclick="return confirm('Are you sure you want to unassign yourself from this order?')">
                                                <i class='bx bx-x'></i> Unassign
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="showOrderHistory('{{ $order->id }}')" title="Order history"
                                            class="p-2 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50">
                                        <i class='bx bx-history text-xl'></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="noResults" class="hidden px-6 py-16 text-center">
                @if ($openOrders->isEmpty())
                    <div class="w-16 h-16 mx-auto mb-4 bg-emerald-50 rounded-full grid place-items-center">
                        <i class='bx bx-check-circle text-emerald-500 text-3xl'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">All caught up</h3>
                    <p class="text-gray-500 mt-1">There are no orders waiting. New ones will pop up here automatically.</p>
                @else
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full grid place-items-center">
                        <i class="bi bi-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">No orders match your filters</h3>
                    <p class="text-gray-500 mt-1 mb-5">Try a different search or clear the filters.</p>
                    <button onclick="clearOrderFilters()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Clear filters</button>
                @endif
            </div>
        </div>
    </main>
<div id="assignedModal" class="fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="" onclick="closeAssignedModal()"></div>

        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl animate-slideIn">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-semibold">Orders with riders</h3>
                <button onclick="closeAssignedModal()">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-500 bg-gray-50">
                            <th class="p-3 font-medium">Order</th>
                            <th class="p-3 font-medium">Customer</th>
                            <th class="p-3 font-medium">Rider</th>
                            <th class="p-3 font-medium">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($customerOrder->whereNotNull('assigned_staff_id')->sortByDesc('created_at') as $order)
                            <tr>
                                <td class="p-3">
                                    <p class="font-mono font-semibold">{{ $order->order_code }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('j M · g:i A') }}</p>
                                </td>
                                <td class="p-3">{{ $order->customer->name ?? 'N/A' }}</td>
                                <td class="p-3">{{ $order->assignedStaff->name ?? 'N/A' }}</td>
                                <td class="p-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $order->order_status->badgeClasses() }}">
                                        {{ $order->order_status->label() }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    No assigned orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


    <div id="historyModal" 
         class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeHistoryModal()"></div>
            
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl animate-slideIn">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900">Order History</h3>
                        <button onclick="closeHistoryModal()" 
                                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <div id="historyTimeline"></div>
                </div>
                
                <div class="p-6 border-t border-gray-200">
                    <button onclick="closeHistoryModal()" 
                            class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmationModal" 
         class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeConfirmationModal()"></div>
            
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md animate-slideIn">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class='bx bx-user-plus text-blue-600 text-xl'></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Assign Delivery Boy</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-700 mb-4">
                        Assign order <span id="confirmationOrderCode" class="font-medium"></span> to 
                        <span id="confirmationStaffName" class="font-medium"></span>?
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <i class='bx bx-info-circle text-yellow-500 mr-2'></i>
                            <p class="text-sm text-yellow-700">
                                This will assign the delivery responsibility to the selected staff member.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 flex space-x-3">
                    <button onclick="closeConfirmationModal()" 
                            class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-medium">
                        Cancel
                    </button>
                    <button id="confirmAssignmentBtn" 
                            class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <span id="confirmButtonText">Confirm Assignment</span>
                        <span id="confirmButtonLoading" class="hidden">
                            <i class="bx bx-loader-alt animate-spin mr-2"></i>
                            Assigning...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.style.overflow = sidebar.classList.contains('-translate-x-full') ? '' : 'hidden';
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const profileBtn = document.querySelector('[onclick="toggleProfileDropdown()"]');
            
            if (dropdown && !dropdown.contains(event.target) && !profileBtn.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Delivery boy assignment
        let pendingAssignment = {
            orderId: null,
            staffId: null,
            orderCode: null,
            staffName: null,
            selectElement: null
        };

        function assignDeliveryBoy(selectElement) {
            const staffId = selectElement.value;
            
            if (!staffId) {
                return;
            }

            // Get order ID from data attribute
            const orderId = selectElement.getAttribute('data-order-id');
            const row = selectElement.closest('tr');
            const orderCode = row.querySelector('td:first-child span').textContent;
            const staffName = selectElement.options[selectElement.selectedIndex].text;

            // Store pending assignment
            pendingAssignment = {
                orderId,
                staffId,
                orderCode,
                staffName: staffName.split(' (')[0], // Remove order count from name
                selectElement
            };

            // Show confirmation modal
            document.getElementById('confirmationOrderCode').textContent = orderCode;
            document.getElementById('confirmationStaffName').textContent = staffName.split(' (')[0];
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        async function confirmAssignment() {
            const confirmBtn = document.getElementById('confirmAssignmentBtn');
            const confirmText = document.getElementById('confirmButtonText');
            const confirmLoading = document.getElementById('confirmButtonLoading');
        
            // Show loading state
            confirmText.classList.add('hidden');
            confirmLoading.classList.remove('hidden');
            confirmBtn.disabled = true;
        
            try {
                console.log('Assigning order:', {
                    orderId: pendingAssignment.orderId,
                    staffId: pendingAssignment.staffId,
                    orderCode: pendingAssignment.orderCode
                });
        
                const response = await fetch(`/staff/customer-order/${pendingAssignment.orderId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        staff_id: pendingAssignment.staffId
                    })
                });
        
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Non-JSON response received:', text.substring(0, 200));
                    throw new Error('Server returned an error page. Please check the console.');
                }
        
                const data = await response.json();
                console.log('Response:', data);
        
                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }
        
                if (data.success) {
                    showToast(data.message || 'Order assigned successfully!', 'success');
                    
                    pendingAssignment.selectElement.value = '';
                    
                    pendingAssignment.selectElement.disabled = true;
                    
                    const row = document.querySelector(`tr[data-order-id="${pendingAssignment.orderId}"]`);
                    if (row) {
                        row.remove();
                    }
                } else {
                    showToast(data.message || 'Failed to assign order', 'error');
                    pendingAssignment.selectElement.value = '';
                }
            } catch (error) {
                console.error('Assignment error:', error);
                showToast(error.message || 'Network error. Please try again.', 'error');
                pendingAssignment.selectElement.value = '';
            } finally {
                closeConfirmationModal();
            }
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            const confirmBtn = document.getElementById('confirmAssignmentBtn');
            const confirmText = document.getElementById('confirmButtonText');
            const confirmLoading = document.getElementById('confirmButtonLoading');

            modal.classList.add('hidden');
            
            // Reset button state
            confirmText.classList.remove('hidden');
            confirmLoading.classList.add('hidden');
            confirmBtn.disabled = false;

            // Reset pending assignment
            pendingAssignment = {
                orderId: null,
                staffId: null,
                orderCode: null,
                staffName: null,
                selectElement: null
            };
        }

        // History modal functions
        async function showOrderHistory(orderId) {
            const modal = document.getElementById('historyModal');
            const timeline = document.getElementById('historyTimeline');
            
            // Show loading state
            timeline.innerHTML = `
                <div class="flex items-center justify-center p-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            try {
                const response = await fetch(`/staff/customer-order/${orderId}/history/json`);
                const data = await response.json();
                
                if (data.histories && data.histories.length > 0) {
                    timeline.innerHTML = data.histories.map(history => `
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-1.5"></div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900 capitalize">${history.action.replace(/_/g, ' ')}</h4>
                                        <span class="text-sm text-gray-500">${history.created_at}</span>
                                    </div>
                                    ${history.old_status && history.new_status ? `
                                        <div class="mt-2 text-sm">
                                            <span class="text-gray-600">Status changed from </span>
                                            <span class="font-medium">${formatStatusDisplay(history.old_status)}</span>
                                            <span class="text-gray-600"> to </span>
                                            <span class="font-medium">${formatStatusDisplay(history.new_status)}</span>
                                        </div>
                                    ` : ''}
                                    ${history.notes ? `
                                        <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-600">${history.notes}</p>
                                        </div>
                                    ` : ''}
                                    <div class="mt-2 text-sm text-gray-500">
                                        By: ${history.staff_name}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    timeline.innerHTML = `
                        <div class="text-center p-8">
                            <div class="w-12 h-12 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class='bx bx-time text-gray-400 text-xl'></i>
                            </div>
                            <p class="text-gray-600">No history available for this order.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading order history:', error);
                timeline.innerHTML = `
                    <div class="text-center p-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                            <i class='bx bx-error text-red-400 text-xl'></i>
                        </div>
                        <p class="text-gray-600 mb-4">Failed to load order history.</p>
                        <button onclick="showOrderHistory('${orderId}')" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Retry
                        </button>
                    </div>
                `;
            }
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        // Utility functions
        function formatStatusDisplay(status) {
            if (!status) return 'N/A';
            return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} mr-3 text-lg'></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('dateFilter');
            const typeFilter = document.getElementById('typeFilter');
            const paymentFilter = document.getElementById('paymentFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const refreshBtn = document.getElementById('refreshBtn');
            const resultCount = document.getElementById('resultCount');
            const noResults = document.getElementById('noResults');
            const ordersTableBody = document.getElementById('ordersTableBody');
            const rows = ordersTableBody.querySelectorAll('tr');
            const activeFilters = document.getElementById('activeFilters');
            const filterBadges = document.getElementById('filterBadges');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedDate = dateFilter.value;
                let visibleCount = 0;

                rows.forEach(row => {
                    let visible = true;

                    // Search filter
                    if (searchTerm) {
                        const searchText = row.getAttribute('data-search-text') || '';
                        if (!searchText.includes(searchTerm)) {
                            visible = false;
                        }
                    }

                    // Date filter
                    if (selectedDate && visible) {
                        // Read Y-m-d as a local date (new Date('Y-m-d') would be UTC midnight)
                        const [y, m, d] = row.getAttribute('data-date').split('-').map(Number);
                        const rowDate = new Date(y, m - 1, d);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        const yesterday = new Date(today);
                        yesterday.setDate(yesterday.getDate() - 1);

                        const weekStart = new Date(today);
                        weekStart.setDate(weekStart.getDate() - weekStart.getDay());

                        const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);

                        switch(selectedDate) {
                            case 'today':
                                if (rowDate.getTime() !== today.getTime()) visible = false;
                                break;
                            case 'yesterday':
                                if (rowDate.getTime() !== yesterday.getTime()) visible = false;
                                break;
                            case 'week':
                                if (rowDate < weekStart) visible = false;
                                break;
                            case 'month':
                                if (rowDate < monthStart) visible = false;
                                break;
                        }
                    }

                    // Order type and payment filters
                    if (typeFilter.value && row.getAttribute('data-type') !== typeFilter.value) {
                        visible = false;
                    }
                    if (paymentFilter.value && row.getAttribute('data-payment') !== paymentFilter.value) {
                        visible = false;
                    }

                    // Update visibility
                    if (visible) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update result count
                resultCount.textContent = visibleCount;

                // Show/hide no results message
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    ordersTableBody.parentElement.classList.add('hidden');
                } else {
                    noResults.classList.add('hidden');
                    ordersTableBody.parentElement.classList.remove('hidden');
                }

                // Update active filters display
                updateActiveFilters();
            }

            function updateActiveFilters() {
                const filters = [];

                if (searchInput.value.trim()) {
                    filters.push({ label: 'Search: ' + searchInput.value.trim(), clear: () => searchInput.value = '' });
                }
                [[dateFilter, 'Date'], [typeFilter, 'Type'], [paymentFilter, 'Payment']].forEach(([select, name]) => {
                    if (select.value) {
                        filters.push({ label: name + ': ' + select.options[select.selectedIndex].text, clear: () => select.value = '' });
                    }
                });

                filterBadges.innerHTML = '';
                activeFilters.classList.toggle('hidden', filters.length === 0);
                filters.forEach(filter => {
                    const badge = document.createElement('button');
                    badge.type = 'button';
                    badge.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100';
                    badge.textContent = filter.label;
                    badge.insertAdjacentHTML('beforeend', ' <i class="bi bi-x"></i>');
                    badge.addEventListener('click', () => { filter.clear(); applyFilters(); });
                    filterBadges.appendChild(badge);
                });
            }

            function clearFilters() {
                searchInput.value = '';
                dateFilter.value = '';
                typeFilter.value = '';
                paymentFilter.value = '';
                applyFilters();
            }
            window.clearOrderFilters = clearFilters;

            // Event listeners
            searchInput.addEventListener('input', applyFilters);
            dateFilter.addEventListener('change', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            paymentFilter.addEventListener('change', applyFilters);
            clearFiltersBtn.addEventListener('click', clearFilters);

            refreshBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                
                // Add spinning animation
                icon.className = 'bi bi-arrow-clockwise animate-spin';
                this.disabled = true;

                setTimeout(() => {
                    location.reload();
                }, 500);
            });

            // Confirmation modal button
            document.getElementById('confirmAssignmentBtn').addEventListener('click', confirmAssignment);

            // Initialize filters
            applyFilters();

        });
        function openAssignedModal() {
            document.getElementById('assignedModal').classList.remove('hidden');
        }
        
        function closeAssignedModal() {
            document.getElementById('assignedModal').classList.add('hidden');
        }
        // Handle escape key for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeHistoryModal();
                closeConfirmationModal();
                document.getElementById('profileDropdown').classList.add('hidden');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const foodContainers = document.querySelectorAll('.food-tags-container');
            
            foodContainers.forEach(container => {
                // Add hover effect to indicate scrollability
                container.addEventListener('mouseenter', function() {
                    if (this.scrollWidth > this.clientWidth) {
                        this.style.boxShadow = 'inset -10px 0 10px -10px rgba(0,0,0,0.1)';
                    }
                });
                
                container.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
                
                // Auto-scroll on wheel horizontally
                container.addEventListener('wheel', function(e) {
                    if (e.deltaY > 0) {
                        this.scrollLeft += 50;
                    } else {
                        this.scrollLeft -= 50;
                    }
                    e.preventDefault();
                });
            });
        });
    </script>
</body>
</html>