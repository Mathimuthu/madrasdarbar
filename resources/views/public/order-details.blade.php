<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Details - {{ $order->order_code }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

<div class="container px-3 py-3">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('orders.history') }}" class="btn btn-outline-dark bg-danger btn-sm text-white">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- Order Header -->
    <div class="order-header mb-4">
        <h1 class="h5 fw-bold text-dark mb-2">Order #{{ $order->order_code }}</h1>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-dark d-block">{{ $order->created_at->format('M d, Y • h:i A') }}</small>
                @if($order->customer_contact)
                <small class="text-dark">{{ $order->customer_contact }}</small>
                @endif
            </div>
            <div class="text-end">
                <span class="badge {{ $order->isPaid ? 'bg-success' : 'bg-danger' }} text-white small">
                    {{ $order->isPaid ? 'Paid' : 'Not Paid' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Order Status -->
    <div class="status-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0">Order Status</h6>
            <span class="status-badge {{ App\Http\Controllers\Staff\Order\OrderControler::getStatusClass($order->order_status) }}">
                {{ App\Http\Controllers\Staff\Order\OrderControler::getStatusDisplay($order->order_status) }}
            </span>
        </div>
    </div>

    <!-- Order Items -->
    <div class="order-items-card mb-4">
        <h6 class="fw-bold text-dark mb-3">Order Items</h6>
        
        @foreach($order->customerOrderDetail as $detail)
        @php
            $itemTotal = (float) ($detail->total_price ?? (($detail->price ?? 0) * $detail->quantity));
            $foodPrice = $detail->quantity > 0 ? ($itemTotal / $detail->quantity) : $itemTotal;
        @endphp
        <div class="order-item mb-3 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="fw-bold text-dark mb-1">{{ $detail->foodMenu->name ?? 'N/A' }}</h6>
                    <div class="d-flex align-items-center">
                        <span class="text-muted small me-3">Qty: {{ $detail->quantity }}</span>
                        <span class="text-muted small">{{ $location->currency }} {{ number_format($foodPrice, 2) }} each</span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark">{{ $location->currency }} {{ number_format($itemTotal, 2) }}</span>
                </div>
            </div>
        </div>
        @endforeach

        <!-- ORDER SUMMARY WITH CHARGES -->
        <div class="order-summary pt-3">
            <!-- Subtotal -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Subtotal</span>
                <span class="text-dark">{{ $location->currency }} {{ number_format($orderSubtotal, 2) }}</span>
            </div>

            <!-- Charges Breakdown -->
            @if(count($chargesBreakdown) > 0)
                <div class="charges-breakdown mb-3">
                    @foreach($chargesBreakdown as $charge)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="text-muted">{{ $charge['name'] }}</span>
                                @if($charge['is_percentage'])
                                    <small class="text-muted d-block">({{ $charge['rate'] }}%)</small>
                                @endif
                            </div>
                            <span class="{{ $charge['amount'] < 0 ? 'text-success' : 'text-danger' }}">
                                {{ $charge['amount'] < 0 ? '-' : '+' }}{{ $location->currency }} {{ number_format(abs($charge['amount']), 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Total Charges -->
                @if($totalChargesAmount != 0)
                <div class="d-flex justify-content-between align-items-center mb-2 pt-2 border-top">
                    <span class="text-muted">Total Additional Charges</span>
                    <span class="{{ $totalChargesAmount < 0 ? 'text-success' : 'text-danger' }}">
                        {{ $totalChargesAmount < 0 ? '-' : '+' }}{{ $location->currency }} {{ number_format(abs($totalChargesAmount), 2) }}
                    </span>
                </div>
                @endif
            @endif

            <!-- Grand Total -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <span class="fw-bold text-dark">Total</span>
                <span class="fw-bold text-dark fs-5">{{ $location->currency }} {{ number_format($order->order_total_price, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Order Information -->
    <div class="order-info-card mb-4">
      
            <!-- Delivery Type -->
            <div class="col-md-6">
                <div class="info-item">
                    <small class="text-muted d-block">Order Type</small>
                    <span class="fw-medium text-dark text-capitalize">{{ $order->delivery_type }}</span>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="col-md-6">
                <div class="info-item">
                    <small class="text-muted d-block">Payment Method</small>
                    <span class="fw-medium text-dark text-capitalize">{{ $order->payment_type }}</span>
                </div>
            </div>

            
            <!-- Order Notes -->
            @if($order->order_notes)
            <div class="col-md-12">
                <div class="info-item">
                    <small class="text-muted d-block">Order Notes</small>
                    <span class="fw-medium text-dark">{{ $order->order_notes }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

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
        background-color: rgb(216 25 25);
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

    /* Cards */
    .order-items-card,
    .order-info-card,
    .charges-info-card,
    .status-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
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

    /* Order Items */
    .order-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    /* Order Summary */
    .order-summary {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-top: 16px;
    }

    /* Charges Breakdown */
    .charges-breakdown {
        background: #fff;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    /* Info Items */
    .info-item {
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f3f4;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-outline-dark {
        border: 1px solid #6c757d;
        color: #6c757d;
    }

    .btn-outline-dark:hover {
        background: #6c757d;
        color: white;
    }

    /* Text Colors */
    .text-dark {
        color: #1f2937 !important;
    }

    .text-muted {
        color: #6b7280 !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-danger {
        color: #dc3545 !important;
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
        .container {
            padding-left: 16px;
            padding-right: 16px;
        }
        
        .order-items-card,
        .order-info-card,
        .charges-info-card,
        .status-card {
            padding: 16px;
            border-radius: 10px;
        }
    }

    /* Smooth transitions */
    .order-items-card,
    .order-info-card,
    .charges-info-card,
    .status-card,
    .btn {
        transition: all 0.2s ease;
    }
</style>
</body>
</html>