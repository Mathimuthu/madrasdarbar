<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&family=Cinzel:wght@400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Tailwind CSS v3.4+ CDN (with JIT, dark mode, and all plugins) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Optional: Tailwind Config (Recommended for better performance & custom colors) -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#dc2626',
                            600: '#b91c1c',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js v3 (Required for modals & reactivity) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Optional: Google Fonts - Inter (Modern & clean) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>     
<body>
     <div class="top-bar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-telephone-fill me-2"></i>
                    <span>{{ session('location_number') }} | {{ strtoupper(session('order_type', 'delivery')) }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    <span>{{ $location->location_name }}</span>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar sticky-top">
        <div class="container">
            <div class="d-flex align-items-center w-100 justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="navbar-brand me-3">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="MadrasDarbar" style="height: 40px;">
                    </div>
                    <div>
                        <p class="restaurant-name mb-0">MadrasDarbar - {{ $location->location_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<div class="container px-3 py-4">
        <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-outline-dark bg-danger btn-sm text-white" style="">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold text-dark mb-1">My Orders</h1>
            <p class=" small mb-0" style="color:red;">{{ $orders->count() }} {{ $orders->count() == 1 ? 'order' : 'orders' }}</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-plus"></i> New Order
        </a>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="orders-list">
            @foreach($orders as $order)
            <div class="order-card mb-3">
                <!-- Order Header -->
                <div class="order-header d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <div class="fw-bold text-dark">Order #{{ $order->order_code }}</div>
                        <small class="text-muted">{{ $order->created_at->format('M d, Y • h:i A') }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-dark">{{ $order->location->currency }} {{ number_format($order->order_total_price, 2) }}</div>
                        <span class="badge {{ $order->isPaid ? 'bg-success' : 'bg-danger' }} text-white small">
                            {{ $order->isPaid ? 'Paid' : 'Not Paid' }}
                        </span>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="order-body p-3">
                    <!-- Status -->
                    <div class="status-section mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Status</span>
                            <span class="status-badge {{ App\Http\Controllers\Staff\Order\OrderControler::getStatusClass($order->order_status) }}">
                                {{ App\Http\Controllers\Staff\Order\OrderControler::getStatusDisplay($order->order_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Delivery & Payment -->
                    <div class="details-section mb-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-muted small mb-1">Delivery Type</div>
                                <div class="fw-medium text-dark">{{ $order->delivery_type }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small mb-1">Payment</div>
                                <div class="fw-medium text-dark text-capitalize">{{ $order->payment_type }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Summary -->
                    @if($order->customerOrderDetail && $order->customerOrderDetail->count() > 0)
                    <div class="items-section pt-3 border-top">
                        <div class="text-muted small mb-2 fw-semibold">
                            <i class="bi bi-basket me-1"></i>Items ({{ $order->customerOrderDetail->sum('quantity') }})
                        </div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($order->customerOrderDetail as $detail)
                                @php
                                    $itemTotal = (float) ($detail->total_price ?? (($detail->price ?? 0) * $detail->quantity));
                                @endphp
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-dark">
                                        <span class="badge bg-light text-dark border me-1">{{ $detail->quantity }}x</span>
                                        {{ $detail->foodMenu->name ?? 'Item' }}
                                    </span>
                                    <span class="text-muted fw-semibold">
                                        {{ $order->location->currency }} {{ number_format($itemTotal, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="action-section d-none"><!--
                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-outline-dark w-100 py-2">
                            <i class="bi bi-eye me-2"></i>View Details
                        </a>-->
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="empty-icon mb-4">
                <i class="bi bi-bag-x display-1 text-muted"></i>
            </div>
            <h3 class="h5 fw-bold text-dark mb-2">No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet</p>
            <a href="{{ route('home') }}" class="btn btn-danger px-4">
                Browse Menu
            </a>
        </div>
    @endif
</div>
<style>
    /* Base Styles */
    body {
        background-color: #b8abab26;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        min-height: 100vh;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

       .top-bar {
            background-color:  rgb(216 25 25);
            color: white;
            padding: 10px 0;
            font-size: 14px;
        }

        .navbar {
            background-color: rgb(216 25 25);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 15px 0;
            color: white;
        }

        .restaurant-name {
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-bottom: 0;
        }

    /* Order Card */
    .order-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    /* Order Header */
    .order-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    /* Status Badges - Simplified */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-ordered { background: #e3f2fd; color: #1976d2; }
    .status-preparing { background: #fff3e0; color: #f57c00; }
    .status-ready { background: #e8f5e8; color: #388e3c; }
    .status-delivery { background: #fff8e1; color: #ffa000; }
    .status-delivered { background: #f3e5f5; color: #7b1fa2; }
    .status-completed { background: #e8f5e8; color: #388e3c; }
    .status-cancelled { background: #ffebee; color: #d32f2f; }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-danger {
        background: #dc2626;
        border: none;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-outline-dark {
        border: 1px solid #6c757d;
        color: #6c757d;
    }

    .btn-outline-dark:hover {
        background: #6c757d;
        color: white;
    }

    .btn-outline-danger {
        border: 1px solid #dc2626;
        color: #dc2626;
    }

    .btn-outline-danger:hover {
        background: #dc2626;
        color: white;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        opacity: 0.5;
    }

    /* Text Colors */
    .text-dark {
        color: #1f2937 !important;
    }

    .text-muted {
        color: #6b7280 !important;
    }

    /* Badges */
    .badge {
        font-size: 0.7rem;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 12px;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        .order-card {
            border-radius: 10px;
        }
        
        .order-header,
        .order-body {
            padding: 16px;
        }
    }

    /* Smooth transitions */
    .order-card,
    .btn,
    .badge {
        transition: all 0.2s ease;
    }

    /* Focus states */
    .btn:focus {
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.25);
    }
 
</style>
</body>
</html>