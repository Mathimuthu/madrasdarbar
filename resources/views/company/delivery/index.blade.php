<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>@yield('title', 'Customer Orders')</title>
    <style>
        /* Custom styles for professional look */
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #dbeafe;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--gray-800);
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
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

        /* Status badge styles */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-badge.ordered {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        .status-badge.preparing {
            background-color: #dbeafe;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        .status-badge.ready_to_deliver {
            background-color: #e0e7ff;
            color: #3730a3;
            border-color: #c7d2fe;
        }

        .status-badge.delivery_on_the_way {
            background-color: #fef3c7;
            color: #92400e;
            border-color: #fde68a;
        }

        .status-badge.delivered {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #a7f3d0;
        }

        .status-badge.completed {
            background-color: #ccfbf1;
            color: #0d9488;
            border-color: #99f6e4;
        }

        .status-badge.cancelled {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }

        /* Payment badge */
        .payment-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
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

        /* Assignment badges */
        .assigned-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .assigned-to-you {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .assigned-to-other {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .unassigned {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Radio button styles */
        .form-label {
            display: flex;
            align-items: center;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-label:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .form-label.selected {
            background-color: #eff6ff;
            border-color: #3b82f6;
        }

        .custom-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .custom-radio.selected {
            border-color: #3b82f6;
        }

        .custom-radio.selected::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: #3b82f6;
            border-radius: 50%;
        }

        /* Hide actual radio inputs */
        .radio-input {
            display: none;
        }

        /* Animation classes */
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        .animate-slideUp {
            animation: slideUp 0.3s ease-in-out;
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Food tag */
        .food-tag {
            background-color: #f3f4f6;
            color: #4b5563;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
        }
        /* Mobile-specific modal adjustments */
        @media (max-width: 767px) {
            #statusModal .relative,
            #paymentModal .relative {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            
            /* Ensure notifications appear above mobile modals */
            #notificationContainer {
                z-index: 99999 !important;
            }
            
            #notificationContainer > div {
                z-index: 999999 !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Mobile Sidebar -->
    <div class="fixed inset-0 z-50 hidden" id="mobileSidebar">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="toggleMobileSidebar()"></div>
        <div class="fixed left-0 top-0 h-full w-80 bg-white shadow-xl transform transition-transform duration-300 -translate-x-full" id="sidebarContent">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        @if (Auth::user()->photo)
                            <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class='bx bx-user text-blue-600 text-lg'></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">Delivery Member</p>
                        </div>
                    </div>
                    <button onclick="toggleMobileSidebar()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i class='bx bx-x text-xl text-gray-500'></i>
                    </button>
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all font-medium">
                        <i class='bx bx-log-out-circle text-xl'></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Top Navigation -->
    <nav class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Mobile menu button -->
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-menu text-2xl text-gray-700'></i>
                </button>

                <!-- Logo/Brand -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-package text-white text-lg'></i>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">OrderHub</span>
                    <span class="text-sm text-gray-500 ml-2 hidden lg:block">/ Customer Orders</span>
                </div>

                <!-- User Profile -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
                        @if (Auth::user()->photo)
                            <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class='bx bx-user text-blue-600'></i>
                            </div>
                        @endif
                        <i class='bx bx-chevron-down text-gray-500 text-sm'></i>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 hidden animate-slideUp">
                        <a href="{{ route('staff-profile-show', Auth::user()->id) }}" 
                           class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class='bx bx-user-circle text-gray-500'></i>
                            <span>Update Profile</span>
                        </a>
                        <a href="#" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class='bx bx-key text-gray-500'></i>
                            <span>Change Password</span>
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 w-full px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class='bx bx-log-out-circle text-red-500'></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            @if(!empty($todayOrderCount))
            <h1 class="text-2xl font-bold text-gray-900">Today Orders({{@$todayOrderCount}})</h1>
            @else
            <h1 class="text-2xl font-bold text-gray-900">Customer Orders</h1>
            @endif
            <p class="text-gray-600 mt-1">Manage and track all orders assigned to you</p>
        </div>

        <!-- Dynamic Notifications -->
        <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

        <!-- Filters Section -->
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
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="searchInput" placeholder="Order code, food, contact..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Order Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="ordered">Ordered</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready_to_deliver">Ready to Deliver</option>
                            <option value="delivery_on_the_way">Delivery on the Way</option>
                            <option value="delivered">Delivered</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Payment Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment</label>
                        <select id="paymentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            <option value="paid">Paid</option>
                            <option value="not_paid">Not Paid</option>
                        </select>
                    </div>
                    
                    <!-- Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assignment</label>
                        <select id="assignmentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All</option>
                            <option value="my_orders">My Orders</option>
                            <option value="available">Available</option>
                            <option value="others">Assigned to Others</option>
                        </select>
                    </div>
                    
                    <!-- Date Filter -->
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

                <!-- Active Filters -->
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
                        <h2 class="text-lg font-semibold text-gray-900">All Orders</h2>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-800 text-sm font-medium rounded-full" 
                              id="resultCount">{{ count($customerOrder) }}</span>
                    </div>
                    <button id="refreshBtn" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-300 rounded-lg flex items-center space-x-2">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order Code</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Food Order</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Assigned To</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order Status</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Paid Status</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Total Price</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Customer Contact</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Delivery Type</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Address</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Notes</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ordered At</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody" class="divide-y divide-gray-200">
                        @foreach ($customerOrder as $order)
                            @php
                                // Build the full address from all components
                                $fullAddress = '';
                                
                                if (!empty($order->customer->door_no) && $order->customer->door_no !== 'NULL') {
                                    $fullAddress .= $order->customer->door_no . ', ';
                                }
                                
                                if (!empty($order->customer->area) && $order->customer->area !== 'NULL') {
                                    $fullAddress .= $order->customer->area . ', ';
                                }
                                
                                if (!empty($order->customer->address) && $order->customer->address !== 'NULL') {
                                    $fullAddress .= $order->customer->address;
                                }
                                
                                $fullAddress = rtrim($fullAddress, ', ');
                                $fullAddress = trim($fullAddress);
                                
                                if (empty($fullAddress) || $fullAddress === ',') {
                                    $fullAddress = 'Address not available';
                                }
                                
                                $latlon = $order->customer->latlon ?? '';
                                $hasValidCoordinates = !empty($latlon) && 
                                                       $latlon !== 'NULL' && 
                                                       strpos($latlon, ',') !== false;
                                
                                $googleMapsUrl = '';
                                if ($hasValidCoordinates) {
                                    $googleMapsUrl = 'https://www.google.com/maps?q=' . urlencode(trim($latlon));
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors"
                                data-order-id="{{ $order->id }}"
                                data-order-code="{{ $order->order_code }}"
                                data-latlon="{{ $latlon }}"
                                data-address-text="{{ $fullAddress }}"
                                data-google-maps-url="{{ $googleMapsUrl }}"
                                data-status="{{ $order->order_status }}"
                                data-payment="{{ $order->isPaid ? 'paid' : 'not_paid' }}"
                                data-assignment="{{ !$order->assigned_staff_id ? 'available' : ($order->assigned_staff_id == $currentStaffId ? 'my_orders' : 'others') }}"
                                data-date="{{ $order->created_at->format('Y-m-d') }}"
                                data-search-text="{{ strtolower($order->order_code . ' ' . $order->customer->name . ' ' . $order->customer->mobile . ' ' . collect($order->customerOrderDetail)->pluck('foodMenu.name')->implode(' ')) }}">
                                <td class="py-4 px-4">
                                    <span class="font-mono font-medium text-gray-900">#{{ $order->order_code }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($order->customerOrderDetail as $orderDetail)
                                            <span class="food-tag">{{ $orderDetail->foodMenu->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($order->assigned_staff_id)
                                        @if($order->assigned_staff_id == $currentStaffId)
                                            <span class="assigned-badge assigned-to-you">You</span>
                                        @else
                                            <span class="assigned-badge assigned-to-other">{{ $order->assignedStaff->name ?? 'Unknown' }}</span>
                                        @endif
                                    @else
                                        <span class="assigned-badge unassigned">Available</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="status-badge {{ $order->order_status }}" 
                                         onclick="handleStatusClick('{{ $order->id }}', '{{ $order->order_status }}')"
                                         data-order-id="{{ $order->id }}"
                                         data-status="{{ $order->order_status }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->order_status->value)) }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="payment-badge {{ $order->isPaid ? 'payment-paid' : 'payment-not-paid' }}"
                                         onclick="handlePaymentClick('{{ $order->id }}', '{{ $order->isPaid ? '1' : '0' }}')"
                                         data-order-id="{{ $order->id }}"
                                         data-is-paid="{{ $order->isPaid ? '1' : '0' }}">
                                        {{ $order->isPaid ? 'Paid' : 'Not Paid' }}
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-medium text-gray-900">
                                        {{ $order->location->currency ?? '₹' }} {{ number_format($order->order_total_price, 2) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $order->customer->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $order->customer_contact ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        {{ ucfirst($order->delivery_type) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 max-w-xs">
                                    @if($hasValidCoordinates)
                                        <a href="{{ $googleMapsUrl }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="flex items-center space-x-1 text-blue-600 hover:text-blue-700 transition-colors">
                                            <i class="bi bi-geo-alt text-sm"></i>
                                            <span class="truncate">{{ $fullAddress }}</span>
                                        </a>
                                    @else
                                        <div class="flex items-center space-x-1 text-gray-600">
                                            <i class="bi bi-geo-alt text-sm opacity-50"></i>
                                            <span class="truncate">{{ $fullAddress }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-gray-700 text-sm">{{ $order->order_notes ?: '-' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-sm">
                                        <div class="text-gray-900">{{ $order->created_at->format('j M Y') }}</div>
                                        <div class="text-gray-600">{{ $order->created_at->format('g:i A') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="bi bi-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
                <button onclick="clearFilters()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Clear Filters
                </button>
            </div>
        </div>
    </main>

    <!-- Status Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeStatusModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md animate-slideUp">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900">Change Order Status</h3>
                        <button onclick="closeStatusModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class='bx bx-x text-xl text-gray-500'></i>
                        </button>
                    </div>
                </div>
                
                <form id="statusForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-gray-600 mb-2">Current status:</p>
                            <div id="currentStatusBadge" class="inline-block"></div>
                        </div>
                        
                        <div class="space-y-2" id="statusOptions">
                            @foreach(['ordered', 'preparing', 'ready_to_deliver', 'delivery_on_the_way', 'delivered', 'completed', 'cancelled'] as $status)
                                <label class="form-label" onclick="selectStatusOption('{{ $status }}')" data-value="{{ $status }}">
                                    <div class="custom-radio" id="radio-{{ $status }}"></div>
                                    <span class="flex-1">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                    <div class="status-badge {{ $status }} text-xs"></div>
                                    <input type="radio" name="order_status" value="{{ $status }}" class="radio-input" id="input-{{ $status }}">
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            Cancel
                        </button>
                        <button type="button" id="confirmStatusBtn" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                                onclick="submitStatusUpdate()">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closePaymentModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md animate-slideUp">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900">Update Payment Status</h3>
                        <button onclick="closePaymentModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class='bx bx-x text-xl text-gray-500'></i>
                        </button>
                    </div>
                </div>
                
                <form id="paymentForm" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-gray-600 mb-2">Current payment status:</p>
                            <div id="currentPaymentBadge" class="inline-block"></div>
                        </div>
                        
                        <div class="space-y-2" id="paymentOptions">
                            <label class="form-label" onclick="selectPaymentOption('1')" data-value="1">
                                <div class="custom-radio" id="radio-paid"></div>
                                <span class="flex-1">Paid</span>
                                <div class="payment-badge payment-paid text-xs">Paid</div>
                                <input type="radio" name="is_paid" value="1" class="radio-input" id="input-paid">
                            </label>
                            <label class="form-label" onclick="selectPaymentOption('0')" data-value="0">
                                <div class="custom-radio" id="radio-notpaid"></div>
                                <span class="flex-1">Not Paid</span>
                                <div class="payment-badge payment-not-paid text-xs">Not Paid</div>
                                <input type="radio" name="is_paid" value="0" class="radio-input" id="input-notpaid">
                            </label>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closePaymentModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            Cancel
                        </button>
                        <button type="button" id="confirmPaymentBtn" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                                onclick="submitPaymentUpdate()">
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentOrderId = null;
        let currentStatus = null;
        let currentPayment = null;
        let selectedStatus = null;
        let selectedPayment = null;

        // Mobile sidebar functions
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const sidebarContent = document.getElementById('sidebarContent');
            sidebar.classList.toggle('hidden');
            sidebarContent.classList.toggle('-translate-x-full');
            document.body.style.overflow = sidebar.classList.contains('hidden') ? '' : 'hidden';
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
            
            if (dropdown && !dropdown.contains(event.target) && profileBtn && !profileBtn.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Show notification function
        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg shadow-lg animate-slideUp ${type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl mr-3'></i>
                    <div>
                        <p class="font-medium">${type === 'success' ? 'Success' : 'Error'}</p>
                        <p class="text-sm">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Status modal functions
        function handleStatusClick(orderId, status) {
            currentOrderId = orderId;
            currentStatus = status;
            selectedStatus = null;
            
            // Update current status badge
            const currentBadge = document.getElementById('currentStatusBadge');
            currentBadge.innerHTML = `<div class="status-badge ${status}">${formatStatusDisplay(status)}</div>`;
            
            // Reset all radio buttons
            const labels = document.querySelectorAll('#statusOptions .form-label');
            const radios = document.querySelectorAll('#statusOptions .radio-input');
            
            labels.forEach(label => {
                label.classList.remove('selected');
                const radio = label.querySelector('.custom-radio');
                radio.classList.remove('selected');
            });
            
            radios.forEach(radio => {
                radio.checked = false;
            });
            
            // Select current status
            const currentLabel = document.querySelector(`#statusOptions .form-label[data-value="${status}"]`);
            const currentRadio = document.querySelector(`#input-${status}`);
            
            if (currentLabel) {
                currentLabel.classList.add('selected');
                const radioDiv = currentLabel.querySelector('.custom-radio');
                radioDiv.classList.add('selected');
                currentRadio.checked = true;
            }
            
            // Disable update button initially
            const btn = document.getElementById('confirmStatusBtn');
            btn.disabled = true;
            
            // Show modal
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function selectStatusOption(status) {
            selectedStatus = status;
            
            // Reset all labels
            const labels = document.querySelectorAll('#statusOptions .form-label');
            const radios = document.querySelectorAll('#statusOptions .radio-input');
            
            labels.forEach(label => {
                label.classList.remove('selected');
                const radio = label.querySelector('.custom-radio');
                radio.classList.remove('selected');
            });
            
            radios.forEach(radio => {
                radio.checked = false;
            });
            
            // Select clicked option
            const selectedLabel = document.querySelector(`#statusOptions .form-label[data-value="${status}"]`);
            const selectedRadio = document.querySelector(`#input-${status}`);
            
            if (selectedLabel) {
                selectedLabel.classList.add('selected');
                const radioDiv = selectedLabel.querySelector('.custom-radio');
                radioDiv.classList.add('selected');
                selectedRadio.checked = true;
            }
            
            // Enable/disable update button
            const btn = document.getElementById('confirmStatusBtn');
            btn.disabled = (status === currentStatus);
        }

    async function submitStatusUpdate() {
        if (!selectedStatus || !currentOrderId) return;
        
        const btn = document.getElementById('confirmStatusBtn');
        const originalText = btn.textContent;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i> Updating...';
        btn.disabled = true;
        
        try {
            const response = await fetch(`/delivery/update-order/${currentOrderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    order_status: selectedStatus,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Check if status is being changed to "completed"
                const isChangingToCompleted = selectedStatus === 'completed';
                
                // Update UI
                const statusBadge = document.querySelector(`[data-order-id="${currentOrderId}"] .status-badge`);
                statusBadge.className = `status-badge ${selectedStatus}`;
                statusBadge.textContent = formatStatusDisplay(selectedStatus);
                statusBadge.setAttribute('data-status', selectedStatus);
                
                // Update row data attribute
                const row = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                row.setAttribute('data-status', selectedStatus);
                
                // Close modal
                closeStatusModal();
                
                // Show success message
                showNotification('success', data.message);
                
                // Refresh page only if status is changed to completed
                if (isChangingToCompleted) {
                    // Optional: Add a small delay to show the success notification
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            } else {
                showNotification('error', data.message || 'Update failed');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('error', 'An error occurred while updating status');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            currentOrderId = null;
            currentStatus = null;
            selectedStatus = null;
        }

        // Payment modal functions
        function handlePaymentClick(orderId, payment) {
            currentOrderId = orderId;
            currentPayment = payment;
            selectedPayment = null;
            
            // Update current payment badge
            const currentBadge = document.getElementById('currentPaymentBadge');
            const isPaid = currentPayment === '1';
            currentBadge.innerHTML = `<div class="payment-badge ${isPaid ? 'payment-paid' : 'payment-not-paid'}">${isPaid ? 'Paid' : 'Not Paid'}</div>`;
            
            // Reset all radio buttons
            const labels = document.querySelectorAll('#paymentOptions .form-label');
            const radios = document.querySelectorAll('#paymentOptions .radio-input');
            
            labels.forEach(label => {
                label.classList.remove('selected');
                const radio = label.querySelector('.custom-radio');
                radio.classList.remove('selected');
            });
            
            radios.forEach(radio => {
                radio.checked = false;
            });
            
            // Select current payment
            const currentLabel = document.querySelector(`#paymentOptions .form-label[data-value="${payment}"]`);
            const currentRadioId = payment === '1' ? 'input-paid' : 'input-notpaid';
            const currentRadio = document.getElementById(currentRadioId);
            
            if (currentLabel) {
                currentLabel.classList.add('selected');
                const radioDiv = currentLabel.querySelector('.custom-radio');
                radioDiv.classList.add('selected');
                currentRadio.checked = true;
            }
            
            // Disable update button initially
            const btn = document.getElementById('confirmPaymentBtn');
            btn.disabled = true;
            
            // Show modal
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function selectPaymentOption(payment) {
            selectedPayment = payment;
            
            // Reset all labels
            const labels = document.querySelectorAll('#paymentOptions .form-label');
            const radios = document.querySelectorAll('#paymentOptions .radio-input');
            
            labels.forEach(label => {
                label.classList.remove('selected');
                const radio = label.querySelector('.custom-radio');
                radio.classList.remove('selected');
            });
            
            radios.forEach(radio => {
                radio.checked = false;
            });
            
            // Select clicked option
            const selectedLabel = document.querySelector(`#paymentOptions .form-label[data-value="${payment}"]`);
            const selectedRadioId = payment === '1' ? 'input-paid' : 'input-notpaid';
            const selectedRadio = document.getElementById(selectedRadioId);
            
            if (selectedLabel) {
                selectedLabel.classList.add('selected');
                const radioDiv = selectedLabel.querySelector('.custom-radio');
                radioDiv.classList.add('selected');
                selectedRadio.checked = true;
            }
            
            // Enable/disable update button
            const btn = document.getElementById('confirmPaymentBtn');
            btn.disabled = (payment === currentPayment);
        }

        async function submitPaymentUpdate() {
            if (selectedPayment === null || !currentOrderId) return;
            
            const btn = document.getElementById('confirmPaymentBtn');
            const originalText = btn.textContent;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin mr-2"></i> Updating...';
            btn.disabled = true;
            
            try {
                const response = await fetch(`/delivery/update-order-payment/${currentOrderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        is_paid: selectedPayment,
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    const paymentBadge = document.querySelector(`[data-order-id="${currentOrderId}"] .payment-badge`);
                    const isPaid = selectedPayment === '1';
                    paymentBadge.className = `payment-badge ${isPaid ? 'payment-paid' : 'payment-not-paid'}`;
                    paymentBadge.textContent = isPaid ? 'Paid' : 'Not Paid';
                    paymentBadge.setAttribute('data-is-paid', selectedPayment);
                    
                    // Update row data attribute
                    const row = document.querySelector(`[data-order-id="${currentOrderId}"]`);
                    row.setAttribute('data-payment', isPaid ? 'paid' : 'not_paid');
                    
                    // Close modal
                    closePaymentModal();
                    
                    // Show success message
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message || 'Update failed');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred while updating payment status');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            currentOrderId = null;
            currentPayment = null;
            selectedPayment = null;
        }

        // Utility functions
        function formatStatusDisplay(status) {
            return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const paymentFilter = document.getElementById('paymentFilter');
            const assignmentFilter = document.getElementById('assignmentFilter');
            const dateFilter = document.getElementById('dateFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const refreshBtn = document.getElementById('refreshBtn');
            const resultCount = document.getElementById('resultCount');
            const noResults = document.getElementById('noResults');
            const activeFiltersDiv = document.getElementById('activeFilters');
            const filterBadgesDiv = document.getElementById('filterBadges');
            const tableRows = document.querySelectorAll('#ordersTableBody tr');

            // Apply filters
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedStatus = statusFilter.value;
                const selectedPayment = paymentFilter.value;
                const selectedAssignment = assignmentFilter.value;
                const selectedDate = dateFilter.value;

                let visibleCount = 0;
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                tableRows.forEach(row => {
                    let visible = true;

                    // Search filter
                    if (searchTerm) {
                        const searchText = row.getAttribute('data-search-text') || '';
                        if (!searchText.includes(searchTerm)) {
                            visible = false;
                        }
                    }

                    // Status filter
                    if (selectedStatus && visible) {
                        const rowStatus = row.getAttribute('data-status');
                        if (rowStatus !== selectedStatus) {
                            visible = false;
                        }
                    }

                    // Payment filter
                    if (selectedPayment && visible) {
                        const rowPayment = row.getAttribute('data-payment');
                        if (rowPayment !== selectedPayment) {
                            visible = false;
                        }
                    }

                    // Assignment filter
                    if (selectedAssignment && visible) {
                        const rowAssignment = row.getAttribute('data-assignment');
                        if (rowAssignment !== selectedAssignment) {
                            visible = false;
                        }
                    }

                    // Date filter
                    if (selectedDate && visible) {
                        const rowDate = new Date(row.getAttribute('data-date'));
                        rowDate.setHours(0, 0, 0, 0);
                        
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
                } else {
                    noResults.classList.add('hidden');
                }

                // Update active filters display
                updateActiveFilters();
            }

            function updateActiveFilters() {
                const filters = [];

                if (searchInput.value.trim()) {
                    filters.push({ type: 'search', label: `Search: ${searchInput.value}`, clear: () => searchInput.value = '' });
                }
                if (statusFilter.value) {
                    const text = statusFilter.options[statusFilter.selectedIndex].text;
                    filters.push({ type: 'status', label: `Status: ${text}`, clear: () => statusFilter.value = '' });
                }
                if (paymentFilter.value) {
                    const text = paymentFilter.options[paymentFilter.selectedIndex].text;
                    filters.push({ type: 'payment', label: `Payment: ${text}`, clear: () => paymentFilter.value = '' });
                }
                if (assignmentFilter.value) {
                    const text = assignmentFilter.options[assignmentFilter.selectedIndex].text;
                    filters.push({ type: 'assignment', label: `Assignment: ${text}`, clear: () => assignmentFilter.value = '' });
                }
                if (dateFilter.value) {
                    const text = dateFilter.options[dateFilter.selectedIndex].text;
                    filters.push({ type: 'date', label: `Date: ${text}`, clear: () => dateFilter.value = '' });
                }

                if (filters.length > 0) {
                    activeFiltersDiv.classList.remove('hidden');
                    filterBadgesDiv.innerHTML = filters.map((filter, index) => `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            ${filter.label}
                            <button onclick="removeFilter(${index})" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                <i class="bi bi-x"></i>
                            </button>
                        </span>
                    `).join('');
                } else {
                    activeFiltersDiv.classList.add('hidden');
                    filterBadgesDiv.innerHTML = '';
                }
            }

            function clearFilters() {
                searchInput.value = '';
                statusFilter.value = '';
                paymentFilter.value = '';
                assignmentFilter.value = '';
                dateFilter.value = '';
                applyFilters();
            }

            // Remove individual filter
            window.removeFilter = function(index) {
                const filters = [];
                
                if (searchInput.value.trim()) {
                    filters.push({ clear: () => searchInput.value = '' });
                }
                if (statusFilter.value) {
                    filters.push({ clear: () => statusFilter.value = '' });
                }
                if (paymentFilter.value) {
                    filters.push({ clear: () => paymentFilter.value = '' });
                }
                if (assignmentFilter.value) {
                    filters.push({ clear: () => assignmentFilter.value = '' });
                }
                if (dateFilter.value) {
                    filters.push({ clear: () => dateFilter.value = '' });
                }
                
                if (filters[index]) {
                    filters[index].clear();
                    applyFilters();
                }
            };

            // Event listeners for filters
            searchInput.addEventListener('input', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            paymentFilter.addEventListener('change', applyFilters);
            assignmentFilter.addEventListener('change', applyFilters);
            dateFilter.addEventListener('change', applyFilters);
            clearFiltersBtn.addEventListener('click', clearFilters);

            // Refresh button
            refreshBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                
                // Add spinning animation
                icon.className = 'bi bi-arrow-clockwise animate-spin';
                this.disabled = true;

                // Reload the page
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });

            // Initialize
            applyFilters();

            // Clear filters function for global access
            window.clearFilters = clearFilters;
        });

        // Handle escape key for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeStatusModal();
                closePaymentModal();
                document.getElementById('profileDropdown').classList.add('hidden');
            }
        });
    </script>
</body>
</html>