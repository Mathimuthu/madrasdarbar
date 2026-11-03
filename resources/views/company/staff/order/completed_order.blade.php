<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>@yield('title', 'Completed Orders')</title>
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
        .status-completed { background-color: #ccfbf1; color: #0d9488; }
        .status-completed::before { background-color: #14b8a6; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-delivered::before { background-color: #10b981; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .status-cancelled::before { background-color: #ef4444; }

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
        .toast-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .toast-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-1rem); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
    <!-- Overlay -->
    <div class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="sidebarOverlay"></div>

    <!-- Mobile Sidebar -->
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
                <i class='bx bx-check-circle text-xl'></i>
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

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <button onclick="toggleMobileSidebar()" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-menu text-2xl text-gray-700'></i>
                </button>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-package text-white text-lg'></i>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">OrderHub</span>
                </div>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('customer-order') }}" class="px-4 py-2 rounded-lg transition-all {{ request()->routeIs('customer-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class='bx bx-list-check mr-2'></i> Manage Orders
                    </a>
                    <a href="{{ route('completed-order') }}" class="px-4 py-2 rounded-lg transition-all {{ request()->routeIs('completed-order') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class='bx bx-check-circle mr-2'></i> Completed Orders
                    </a>
                </div>
                <div class="relative flex items-center">
                    @include('company.partials.order-notifications')
                    <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
                        @if (Auth::user()->photo)
                            <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class='bx bx-user text-blue-600'></i>
                            </div>
                        @endif
                        <i class='bx bx-chevron-down text-gray-500'></i>
                    </button>
                    <div id="profileDropdown" class="absolute right-0 top-full mt-2 z-50 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 hidden">
                        <a href="{{ route('staff-profile-show', Auth::user()->id) }}" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class='bx bx-user-circle'></i> <span>Update Profile</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 w-full px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class='bx bx-log-out-circle'></i> <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Completed Orders</h1>
            <p class="text-gray-600 mt-1">View all successfully delivered and completed orders</p>
        </div>

        @if (session('success-message'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class='bx bx-check-circle text-green-500 text-xl mr-3'></i>
                    <div>
                        <p class="font-medium text-green-800">Success</p>
                        <p class="text-green-700">{{ session('success-message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error-message'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class='bx bx-error-circle text-red-500 text-xl mr-3'></i>
                    <div>
                        <p class="font-medium text-red-800">Error</p>
                        <p class="text-red-700">{{ session('error-message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="bi bi-funnel text-gray-600"></i>
                        <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                    </div>
                    <button id="clearFilters" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                        Clear All
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="searchInput" placeholder="Order code, food, contact..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select id="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>
                </div>
                <div id="activeFilters" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Active filters:</p>
                    <div id="filterBadges" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <h2 class="text-lg font-semibold text-gray-900">Completed Orders</h2>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-sm font-medium rounded-full" id="resultCount">{{ count($customerOrder) }}</span>
                    </div>
                    <button id="refreshBtn" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-300 rounded-lg flex items-center space-x-2">
                        <i class="bi bi-arrow-clockwise"></i> <span>Refresh</span>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order Code</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Food Items</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Total Price</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Payment Type</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Delivery Type</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Delivery Charge</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Customer</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Address</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Notes</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Assigned To</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">History</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Completed At</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @foreach ($customerOrder as $order)
                            @php
                                $fullAddress = trim(collect([$order->customer->door_no, $order->customer->area, $order->customer->address])->filter()->implode(', '));
                                $fullAddress = $fullAddress ?: 'Address not available';
                                $latlon = $order->customer->latlon ?? '';
                                $hasCoords = !empty($latlon) && $latlon !== 'NULL' && strpos($latlon, ',') !== false;
                                $mapsUrl = $hasCoords ? 'https://www.google.com/maps?q=' . urlencode(trim($latlon)) : '';
                                $assignedStaff = $order->assignedStaff ?? null;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50"
                                data-order-id="{{ $order->id }}"
                                data-order-code="{{ $order->order_code }}"
                                data-search-text="{{ strtolower($order->order_code . ' ' . $order->customer->name . ' ' . $order->customer->mobile . ' ' . collect($order->customerOrderDetail)->pluck('foodMenu.name')->implode(' ')) }}"
                                data-date="{{ $order->created_at->format('Y-m-d') }}">
                                <td class="py-4 px-4"><span class="font-mono font-medium">#{{ $order->order_code }}</span></td>
                                <td class="py-4 px-4 table-cell-food">
                                    <div class="food-tags-container">
                                        @foreach ($order->customerOrderDetail as $orderDetail)
                                            <span class="food-tag">{{ $orderDetail->foodMenu->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium">
                                    {{ $order->location->currency ?? '₹' }} {{ number_format($order->order_total_price, 2) }}
                                </td>
                                
                                 <td class="py-4 px-4 table-cell-small">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        {{ ucfirst($order->payment_type) }}
                                    </span>
                                </td>
                                
                                  <!-- Delivery Type -->
                                <td class="py-4 px-4 table-cell-small">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        {{ ucfirst($order->delivery_type) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-medium">
                                    {{ $order->delivery_charge}}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium">{{ $order->customer->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->customer_contact ?? 'N/A' }}</div>
                                </td>
                                <td class="py-4 px-4 max-w-xs">
                                    @if($hasCoords)
                                        <a href="{{ $mapsUrl }}" target="_blank" class="flex items-center space-x-1 text-blue-600 hover:text-blue-700">
                                            <i class="bi bi-geo-alt"></i>
                                            <span class="truncate">{{ $fullAddress }}</span>
                                        </a>
                                    @else
                                        <div class="text-gray-600 truncate">{{ $fullAddress }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-700">{{ $order->order_notes ?: '-' }}</td>
                                <td class="py-4 px-4">
                                    @if($assignedStaff)
                                        <div class="flex items-center space-x-2">
                                            @if($assignedStaff->photo)
                                                <img src="{{ asset($assignedStaff->photo) }}" class="w-6 h-6 rounded-full object-cover">
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class='bx bx-user text-blue-600 text-xs'></i>
                                                </div>
                                            @endif
                                            <span class="text-sm">{{ $assignedStaff->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <button onclick="showOrderHistory('{{ $order->id }}')" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <i class='bx bx-history text-lg'></i>
                                    </button>
                                </td>
                                <td class="py-4 px-4 text-sm">
                                    <div>{{ $order->created_at->format('j M Y') }}</div>
                                    <div class="text-gray-600">{{ $order->created_at->format('g:i A') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No completed orders found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your filters</p>
                <button onclick="clearFilters()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Clear Filters</button>
            </div>
        </div>
    </main>

    <!-- History Modal -->
    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeHistoryModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl animate-slideIn">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900">Order History</h3>
                        <button onclick="closeHistoryModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class='bx bx-x text-xl'></i>
                        </button>
                    </div>
                </div>
                <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar" id="historyTimeline"></div>
                <div class="p-6 border-t border-gray-200">
                    <button onclick="closeHistoryModal()" class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            document.getElementById('mobileSidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.toggle('hidden');
            document.body.style.overflow = document.getElementById('mobileSidebar').classList.contains('-translate-x-full') ? '' : 'hidden';
        }
        function closeMobileSidebar() {
            document.getElementById('mobileSidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const btn = document.querySelector('[onclick="toggleProfileDropdown()"]');
            if (dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        async function showOrderHistory(orderId) {
            const modal = document.getElementById('historyModal');
            const timeline = document.getElementById('historyTimeline');
            timeline.innerHTML = `<div class="text-center py-8"><div class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full"></div></div>`;
            modal.classList.remove('hidden');

            try {
                const res = await fetch(`/staff/customer-order/${orderId}/history/json`);
                const data = await res.json();
                if (data.histories?.length > 0) {
                    timeline.innerHTML = data.histories.map(h => `
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-start">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between">
                                        <h4 class="font-medium capitalize">${h.action.replace(/_/g, ' ')}</h4>
                                        <span class="text-sm text-gray-500">${h.created_at}</span>
                                    </div>
                                    ${h.notes ? `<div class="mt-2 p-3 bg-gray-50 rounded-lg text-sm">${h.notes}</div>` : ''}
                                    <div class="mt-2 text-sm text-gray-500">By: ${h.staff_name}</div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    timeline.innerHTML = `<div class="text-center py-8 text-gray-500">No history available.</div>`;
                }
            } catch (err) {
                timeline.innerHTML = `<div class="text-center py-8 text-red-600">Failed to load history.</div>`;
            }
        }
        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        function showToast(msg, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<div class="flex items-center"><i class='bx ${type==='success'?'bx-check-circle':'bx-error-circle'} mr-3 text-lg'></i><span>${msg}</span></div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const search = document.getElementById('searchInput');
            const date = document.getElementById('dateFilter');
            const clear = document.getElementById('clearFilters');
            const refresh = document.getElementById('refreshBtn');
            const count = document.getElementById('resultCount');
            const noRes = document.getElementById('noResults');
            const rows = document.querySelectorAll('#ordersTableBody tr');

            function applyFilters() {
                let visible = 0;
                const term = search.value.toLowerCase().trim();
                const dval = date.value;
                const today = new Date(); today.setHours(0,0,0,0);

                rows.forEach(r => {
                    let show = true;
                    if (term && !(r.dataset.searchText?.includes(term))) show = false;
                    if (dval && show) {
                        const rd = new Date(r.dataset.date);
                        rd.setHours(0,0,0,0);
                        if (dval === 'today' && rd.getTime() !== today.getTime()) show = false;
                        if (dval === 'yesterday') { const y = new Date(today); y.setDate(y.getDate()-1); if (rd.getTime() !== y.getTime()) show = false; }
                        if (dval === 'week') { const ws = new Date(today); ws.setDate(ws.getDate() - ws.getDay()); if (rd < ws) show = false; }
                        if (dval === 'month') { const ms = new Date(today.getFullYear(), today.getMonth(), 1); if (rd < ms) show = false; }
                    }
                    r.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                count.textContent = visible;
                noRes.classList.toggle('hidden', visible > 0);
            }

            search.addEventListener('input', applyFilters);
            date.addEventListener('change', applyFilters);
            clear.addEventListener('click', () => { search.value = ''; date.value = ''; applyFilters(); });
            refresh.addEventListener('click', () => location.reload());

            applyFilters();
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