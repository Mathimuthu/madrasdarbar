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

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    @yield('styles')
<style>
    /* Modern Design Variables */
    :root {
        --primary-color: #e20006;
        --primary-dark: #b80005;
        --secondary-color: #ff1a1f;
        --accent-color: #14b8a6;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --dark-bg: #1e293b;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Raleway', sans-serif;
        background-image: url('{{ asset('images/bg-image.jpeg') }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ============ MODERN NAVIGATION ============ */
    .topbar {
        background: rgba(226, 0, 6, 0.95) !important;
        backdrop-filter: blur(20px);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1030;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(226, 0, 6, 0.3);
    }

    .topbar.scrolled {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background: rgba(226, 0, 6, 1) !important;
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        transition: transform 0.3s ease;
    }

    .logo-section:hover {
        transform: scale(1.02);
    }

    .logo-section img {
        height: 50px;
        width: auto;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.2);
    }

    .logo-section span {
        font-size: 1.5rem;
        font-weight: 800;
        color: white !important;
        font-family: 'Cinzel', serif;
        letter-spacing: 1px;
    }

    /* Modern Nav Links */
    .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.6rem 1.2rem !important;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        font-family: 'Raleway', sans-serif;
        letter-spacing: 0.5px;
    }

    .navbar-nav .nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .navbar-nav .nav-link.active {
        color: white !important;
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .navbar-nav .nav-link.disabled {
        color: rgba(255, 255, 255, 0.4) !important;
        opacity: 0.5;
    }

    /* Mobile Navigation - FIXED */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: rgba(226, 0, 6, 0.98);
            margin-top: 1rem;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .navbar-collapse.show {
            display: block !important;
        }

        .logo-section img {
            height: 45px;
        }

        .logo-section span {
            font-size: 1.25rem;
        }

        /* Ensure navbar items are visible */
        .navbar-nav {
            text-align: center;
        }

        .navbar-nav .nav-link {
            padding: 0.8rem 1rem !important;
            margin: 0.25rem 0;
        }
    }

    /* Modern Cart Button */
    .cart-btn {
        position: relative;
        background: linear-gradient(135deg, #14b8a6 0%, #0ea5e9 100%);
        color: white !important;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        font-family: 'Raleway', sans-serif;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(20, 184, 166, 0.4);
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        min-width: 22px;
        height: 22px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fbbf24;
        color: #000;
        font-weight: 700;
        border-radius: 11px;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* Mobile Cart Wrapper */
    .mobile-cart-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    @media (min-width: 992px) {
        .mobile-cart-wrapper {
            display: none !important;
        }
    }

    /* Hamburger Icon Modern Style - FIXED */
    .navbar-toggler {
        border: none;
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.1);
    }

    .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        outline: none;
    }

    .navbar-toggler[aria-expanded="true"] {
        background: rgba(255, 255, 255, 0.2);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2.5' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        width: 1.5em;
        height: 1.5em;
    }

    /* ============ CART OFFCANVAS (ORIGINAL DESIGN) ============ */
    .offcanvas-cart {
        width: 100% !important;
    }

    @media (min-width: 576px) {
        .offcanvas-cart {
            max-width: 480px !important;
        }
    }

    /* Cart Header */
    .cart-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%) !important;
        color: white;
        flex-shrink: 0;
    }

    .cart-header .offcanvas-title {
        font-family: 'Cinzel', serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* Delivery Info Section */
    .delivery-info-section {
        flex-shrink: 0;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    /* Cart Summary */
    .cart-summary {
        flex-shrink: 0;
        background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);
        color: white;
    }

    /* Scrollable Cart Items */
    .cart-items-container {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        min-height: 0;
        padding: 1rem;
    }

    /* Cart Item Card - Mobile Optimized */
    .cart-item {
        background: white;
        border: 1px solid #e9ecef;
        border-left: 3px solid #2dd4bf;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);
        transform: translateY(-2px);
    }

    .cart-item-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #f0fdfa;
    }

    /* Mobile: Smaller image */
    @media (max-width: 576px) {
        .cart-item-img {
            width: 60px;
            height: 60px;
        }
    }

    /* Quantity Controls */
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        background: #2dd4bf;
        color: white;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .qty-btn:hover {
        background: #0ea5e9;
        transform: scale(1.05);
    }

    @media (max-width: 576px) {
        .qty-btn {
            width: 28px;
            height: 28px;
            font-size: 0.875rem;
        }
    }

    /* Delete Button */
    .btn-delete {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Order Details Form */
    .order-details-section {
        flex-shrink: 0;
        background: #f8f9fa;
        border-top: 2px solid #dee2e6;
        max-height: 35vh;
    }

    /* Buttons */
    .gradient-button {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        transition: all 0.3s ease;
        font-family: 'Raleway', sans-serif;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .gradient-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .gradient-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-change-delivery {
        background: #2dd4bf;
        border: none;
        color: white;
        transition: all 0.2s ease;
        font-family: 'Raleway', sans-serif;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .btn-change-delivery:hover {
        background: #0ea5e9;
    }

    /* Empty Cart */
    .empty-cart {
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    /* Mobile Optimizations */
    @media (max-width: 576px) {
        .cart-item .card-body {
            padding: 0.75rem !important;
        }
        
        .cart-item h6 {
            font-size: 0.875rem;
        }
        
        .cart-item .text-muted {
            font-size: 0.75rem;
        }
        
        .order-details-section {
            padding: 0.75rem !important;
        }

        .order-details-section .form-label {
            font-size: 0.875rem;
        }

        .order-details-section .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }

    /* ============ MODERN MODALS ============ */
    .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        font-family: 'Raleway', sans-serif;
    }

    .modal-header {
        border: none;
        padding: 2rem;
    }

    .modal-header .modal-title {
        font-family: 'Cinzel', serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border: none;
        padding: 1.5rem 2rem;
        background: var(--light-bg);
    }

    /* Delivery Option Cards */
    .delivery-card .card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .delivery-card .card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }

    .delivery-card.active .card {
        border-color: var(--primary-color) !important;
        border-width: 3px !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .delivery-card.active .delivery-check i {
        display: block !important;
    }
/* ============ MODERN FOOTER - MOBILE FIXED ============ */
.modern-footer {
    background: linear-gradient(135deg, #e20006 0%, #b80005bd 100%) !important;    color: white;
    padding: 2rem 0 1rem;
    font-family: 'Raleway', sans-serif;
    width: 100%;
    position: relative;
}

.modern-footer .container {
    max-width: 100%;
    padding: 0 15px;
}

/* Header Section */
.footer-header {
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.footer-title {
    font-family: 'Cinzel', serif;
    font-weight: 700;
    font-size: 1.8rem;
    color: white;
    margin: 0;
}

/* Footer Sections */
.footer-section {
    text-align: center;
    padding: 1rem;
}

.section-title {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.section-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    margin: 0.5rem auto 1rem;
    max-width: 100px;
}

/* Locations */
.locations-list {
    margin-bottom: 1rem;
}

.location-item {
    color: rgba(255, 255, 255, 0.9);
    padding: 0.5rem 0;
    font-size: 1rem;
    font-weight: 500;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.location-item:last-child {
    border-bottom: none;
}

.location-item:hover {
    color: white;
    transform: translateX(5px);
}

/* Contact Info */
.contact-info {
    margin-bottom: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.8rem;
    padding: 0.5rem 0;
}

.contact-link {
    color: rgba(255, 255, 255, 0.9) !important;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.contact-link:hover {
    color: white !important;
    text-decoration: underline;
}

/* Copyright */
.copyright {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    font-weight: 400;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

/* Social Icons */
.social-icons {
    margin-top: 1rem;
}

.social-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.social-icon:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* ============ MOBILE RESPONSIVE STYLES ============ */
@media (max-width: 768px) {
    .modern-footer {
        padding: 1.5rem 0 1rem;
    }
    
    .footer-header {
        padding-bottom: 0.8rem;
        margin-bottom: 1rem;
    }
    
    .footer-title {
        font-size: 1.5rem;
    }
    
    .restaurant-name {
        font-size: 1.2rem;
    }
    
    .restaurant-subtitle {
        font-size: 0.9rem;
    }
    
    .footer-section {
        padding: 0.8rem 0.5rem;
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1.1rem;
        margin-bottom: 0.6rem;
    }
    
    .location-item {
        font-size: 0.95rem;
        padding: 0.4rem 0;
    }
    
    .contact-item {
        font-size: 0.95rem;
        margin-bottom: 0.6rem;
        flex-direction: column;
        text-align: center;
    }
    
    .contact-item i {
        margin-bottom: 0.3rem;
        margin-right: 0;
        font-size: 1.1rem;
    }
    
    .copyright {
        font-size: 0.85rem;
        padding-top: 0.8rem;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
    }
    
    .social-icon i {
        font-size: 1.1rem;
    }
}

/* Extra Small Devices */
@media (max-width: 576px) {
    .modern-footer {
        padding: 1rem 0 0.5rem;
    }
    
    .modern-footer .container {
        padding: 0 10px;
    }
    
    .footer-title {
        font-size: 1.3rem;
    }
    
    .restaurant-name {
        font-size: 1.1rem;
    }
    
    .restaurant-subtitle {
        font-size: 0.85rem;
    }
    
    .section-title {
        font-size: 1rem;
    }
    
    .location-item {
        font-size: 0.9rem;
        padding: 0.35rem 0;
    }
    
    .contact-item {
        font-size: 0.9rem;
    }
    
    .contact-link {
        font-size: 0.9rem;
    }
    
    .copyright {
        font-size: 0.8rem;
    }
    
    .social-icon {
        width: 38px;
        height: 38px;
    }
    
    .social-icons {
        gap: 1.5rem !important;
    }
}

/* Large Screens */
@media (min-width: 992px) {
    .footer-section {
        text-align: left;
    }
    
    .contact-item {
        justify-content: flex-start;
    }
    
    .social-icons {
        justify-content: flex-start !important;
    }
    
    .locations-list {
        text-align: left;
    }
}

/* Ensure proper stacking on mobile */
@media (max-width: 768px) {
    .modern-footer .row {
        flex-direction: column;
        align-items: center;
    }
    
    .modern-footer .col-12 {
        width: 100%;
        max-width: 400px;
    }
}

    /* Responsive Utilities */
    @media (max-width: 576px) {
        .order-details-section {
            padding: 1rem;
        }

        .gradient-button {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }

        .cart-header {
            padding: 1.25rem;
        }

        .cart-header .offcanvas-title {
            font-size: 1.25rem;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cart-item {
        animation: fadeIn 0.3s ease forwards;
    }

    /* Scrollbar Styling */
    .cart-items-container::-webkit-scrollbar {
        width: 8px;
    }

    .cart-items-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .cart-items-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);
        border-radius: 10px;
    }

    .cart-items-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #0284c7 0%, #14b8a6 100%);
    }

    /* My Orders Button */
    .btn-orders {
        background: linear-gradient(135deg, #e20006 0%, #ff1a1f 100%);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(226, 0, 6, 0.3);
        font-family: 'Raleway', sans-serif;
        letter-spacing: 0.5px;
    }

    .btn-orders:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(226, 0, 6, 0.4);
        color: white;
    }
    /* Search Clear Button */
    .search-clear-btn {
        background: none;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        padding: 0;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .search-clear-btn:hover {
        color: var(--primary-red);
        transform: scale(1.1);
    }

    /* Loading state for search */
    .search-box.searching .bx-search {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
    @yield('styles')
</head>

<body data-customer-id="{{ session('customer_id') ?? '' }}" data-location-id="{{ session('location_id') ?? '' }}">

   <!-- FIXED NAVIGATION BAR -->
    @if(!isset($hideLayout) || !$hideLayout)
    <nav class="topbar navbar navbar-expand-lg navbar-light py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand logo-section" href="#">
                <img src="{{ asset('images/logo.jpeg') }}" alt="MadrasDarbar">
                <span class="d-none d-sm-inline"></span>
            </a>

            <!-- FIXED: Mobile Cart + Hamburger -->
            <div class="mobile-cart-wrapper d-lg-none">
                <button class="btn cart-btn position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class='bx bx-cart fs-5'></i>
                    <span class="cart-badge" id="cart-quantity-mobile">0</span>
                </button>
                
                <!-- FIXED: Proper Bootstrap toggle attributes -->
                <button class="navbar-toggler" type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- FIXED: Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    @if (session()->has('location_id'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs(['location.menu', 'search']) ? 'active' : '' }}"
                               href="{{ route('location.menu', ['id' => session('location_id')]) }}">
                               <i class="bi bi-book me-1"></i> Menu
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#">
                                <i class="bi bi-book me-1"></i> Menu
                            </a>
                        </li>
                    @endif

                    @if(session('customer_id'))
                    <li class="nav-item d-lg-none">
                        <a class="nav-link {{ request()->routeIs(['orders.*']) ? 'active' : '' }}" href="{{ route('orders.history') }}">
                            <i class="bi bi-clock-history me-1"></i> My Orders
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item">
                        <a class="nav-link"
                        href="{{ route('customer.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>

                <!-- Desktop Actions -->
                <div class="d-none d-lg-flex align-items-center gap-2">
                    <button class="btn cart-btn position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class='bx bx-cart fs-5 me-1'></i> Cart
                        <span class="cart-badge" id="cart-quantity">0</span>
                    </button>

                    @if(session('customer_id'))
                    <a href="{{ route('orders.history') }}" class="btn btn-orders">
                        <i class="bi bi-clock-history me-1"></i> My Orders
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>


   <!-- Modern Cart Offcanvas -->
    <div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="cartOffcanvas">
        <!-- Header -->
        <div class="offcanvas-header cart-header">
            <h5 class="offcanvas-title mb-0">
                <i class="bi bi-cart-check-fill me-2"></i> Your Cart
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>

        <!-- Body -->
        <div class="offcanvas-body p-0 d-flex flex-column">
            
            <!-- Delivery Info -->
            <div class="delivery-info-section d-none" id="deliveryInfoSection">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block mb-1">Order Type</small>
                        <strong id="selected-delivery-type" class="text-primary fs-6"></strong>
                    </div>
                    <button type="button" class="btn btn-change-delivery" id="changeDeliveryType">
                        <i class='bx bx-edit me-1'></i> Change
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary p-3">
                <div class="row g-0 text-center">
                    <div class="col-6">
                        <small class="d-block text-muted mb-1">Total Items</small>
                        <h5 class="mb-0 fw-bold text-dark" id="cart-item-count">0 items</h5>
                    </div>
                    <div class="col-6">
                        <small class="d-block text-muted mb-1">Total Amount</small>
                        <h4 class="mb-0 fw-bold text-primary" id="cart-total-amount">RM 0.00</h4>
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="cart-items-container">
                <ul class="cart-list list-unstyled mb-0">
                    <li class="empty-cart">
                        <i class="bi bi-cart-x"></i>
                        <p class="mt-2 mb-0">Your cart is empty</p>
                        <small class="text-muted">Add items to get started</small>
                    </li>
                </ul>
            </div>

            <!-- Order Details -->
            <div class="order-details-section">
                <div class="mb-3 d-none" id="tableNumberSection">
                    <label class="form-label">
                        <i class="bi bi-table me-1"></i> Table Number <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="table_number" class="form-control" placeholder="Enter table number">
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-telephone me-1"></i> Contact Number <span class="text-danger">*</span>
                    </label>
                    <input type="tel" name="customer_contact" class="form-control" placeholder="0123456789" required>
                </div>
                
                <!-- Optional Contact Number -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-telephone-plus me-1"></i> Additional Contact Number <span class="text-muted">(Optional)</span>
                    </label>
                    <input type="tel" name="additional_contact" class="form-control" placeholder="Optional second contact number">
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle me-1"></i> In case we need to reach you at another number
                    </small>
                </div>

                <!-- Delivery Address -->
                <div class="mb-3 d-none" id="addressSection">
                    <label class="form-label">
                        <i class="bi bi-geo-alt me-1"></i> Delivery Address <span class="text-danger">*</span>
                    </label>
                    <textarea name="customer_address" class="form-control" rows="3" placeholder="Enter your full delivery address" required></textarea>
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle me-1"></i> This address will be saved to your profile
                    </small>
                </div>

                <!-- Cash Note -->
                <div class="alert alert-warning py-2 mb-3 d-none" id="cashNote">
                    <small><i class="bi bi-cash-coin me-1"></i> <strong>Note:</strong> Cash payment only</small>
                </div>

                <!-- Confirm Button -->
                <div>
                    <small class="text-muted d-block text-center mb-2">
                        Please review your order before confirming
                    </small>
                    <button type="button" class="btn btn-success btn-lg w-100 gradient-button confirm-order" disabled>
                        <i class="bi bi-check-circle me-1"></i> Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Delivery Type Modal - Enhanced UI -->
    <div class="modal fade" id="changeDeliveryModal" tabindex="-1" aria-labelledby="changeDeliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Header -->
                <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);">
                    <div class="w-100 text-center py-3">
                        <div class="mb-2">
                            <i class="bi bi-arrow-left-right-circle text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="modal-title text-white fw-bold mb-1" id="changeDeliveryModalLabel">
                            Change Order Type
                        </h5>
                        <p class="text-white-50 small mb-0">Select how you'd like to receive your order</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Body -->
                <div class="modal-body px-4 py-4">
                    <div class="row g-3">
                        <!-- Doorstep Delivery Option -->
                        <div class="col-12">
                            <button type="button" class="btn btn-delivery-option delivery-card w-100 text-start p-0 border-0" data-option="Doorstep Delivery">
                                <div class="card border-2 h-100 shadow-sm hover-lift" style="border-color: #10b981 !important; transition: all 0.3s ease;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="delivery-icon me-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-house-door-fill text-white" style="font-size: 1.8rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fw-bold text-dark">Doorstep Delivery</h5>
                                                <p class="mb-0 small text-muted">We'll deliver right to your door</p>
                                            </div>
                                            <div class="delivery-check ms-2" style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #10b981; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-check text-success fw-bold" style="font-size: 1.2rem; display: none;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Counter Pickup Option -->
                        <div class="col-12">
                            <button type="button" class="btn btn-delivery-option delivery-card w-100 text-start p-0 border-0" data-option="Counter Pickup">
                                <div class="card border-2 h-100 shadow-sm hover-lift" style="border-color: #f59e0b !important; transition: all 0.3s ease;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="delivery-icon me-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-shop text-white" style="font-size: 1.8rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fw-bold text-dark">Counter Pickup</h5>
                                                <p class="mb-0 small text-muted">Pick up from our counter when ready</p>
                                            </div>
                                            <div class="delivery-check ms-2" style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #f59e0b; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-check text-warning fw-bold" style="font-size: 1.2rem; display: none;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 mt-4 mb-0" style="background: linear-gradient(135deg, #e0f2fe 0%, #bfdbfe 100%);">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle-fill text-info me-2 mt-1" style="font-size: 1.2rem;"></i>
                            <small class="text-dark">
                                <strong>Note:</strong> Changing your order type will update all items in your cart and may affect delivery requirements.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentConfirmationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%);">
                    <h5 class="modal-title text-white"><i class="bi bi-credit-card me-2"></i> Payment Method</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="fs-5 mb-4">How would you like to pay?</p>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <input class="btn-check" type="radio" name="paymentType" id="cashPayment" value="cash" checked>
                            <label class="btn btn-outline-success w-100 py-4" for="cashPayment">
                                <i class="bi bi-cash fs-1 d-block mb-2"></i>
                                <strong>Cash</strong>
                            </label>
                        </div>
                        <div class="col-6">
                            <input class="btn-check" type="radio" name="paymentType" id="cardPayment" value="card">
                            <label class="btn btn-outline-primary w-100 py-4" for="cardPayment">
                                <i class="bi bi-credit-card fs-1 d-block mb-2"></i>
                                <strong>Card</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Order Summary</h6>
                            <p id="paymentOrderSummary" class="mb-0 fw-bold">Total: RM 0.00</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPayment" style="background: linear-gradient(135deg, #0ea5e9 0%, #2dd4bf 100%); border: none;">
                        <i class="bi bi-check-circle me-1"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Confirmation Modal -->
    <div class="modal fade" id="finalConfirmationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i> Confirm Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-cart-check-fill fs-1 text-warning d-block mb-3"></i>
                    <p id="finalConfirmationMessage" class="fs-6 text-start" style="white-space: pre-line;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="finalConfirmOrder">
                        <i class="bi bi-check-circle me-1"></i> Yes, Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i> Success!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <p id="successMessage" class="fs-5 mt-3 fw-semibold"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="closeSuccessModal" data-bs-dismiss="modal">
                        <i class="bi bi-check me-1"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @yield('content')

    <!-- Footer -->
  <!-- Footer - Based on Your Image Content -->
@if(!isset($hideLayout) || !$hideLayout)
<footer class="modern-footer text-white py-4">
    <div class="container">
        <!-- Header Section -->
        <div class="footer-header text-center mb-4">
            <img src="{{ asset('images/logo.jpeg') }}" alt="MadrasDarbar" class="img-fluid mb-3" style="max-height: 90px; border-radius: 15px;">
        </div>

        <div class="row justify-content-center">
            
            <!-- Locations Section -->
            <div class="col-12 col-md-6 col-lg-5 mb-4">
                <div class="footer-section">
                    <h4 class="section-title" style="color:white; font-size:1.2rem">OUR LOCATIONS</h4>
                    <div class="section-divider"></div>
                    <div class="locations-list">
                        <div class="location-item">Pattaya</div>
                        <div class="location-item">Bangkok</div>
                        <div class="location-item">Phuket</div>
                        <div class="location-item">Colombo</div>
                    </div>
                    <div class="copyright mt-3">
                        © 2025 Madras Darbar. All Rights Reserved.
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="col-12 col-md-6 col-lg-5 mb-4">
                <div class="footer-section">
                    <h4 class="section-title" style="color:white; font-size:1.2rem">CONTACT US</h4>
                    <div class="section-divider"></div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:madrasdarbar@gmail.com" class="contact-link">madrasdarbar@gmail.com</a>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:info@madrasdarbar.org" class="contact-link">info@madrasdarbar.org</a>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="social-section mt-4">
                        <div class="social-icons d-flex justify-content-center gap-3">
                            <a href="https://www.facebook.com/madrasdarbarsrilanka" target="_blank" class="social-icon">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://www.instagram.com/madrasdarbarsrilanka?igsh=MWRjZGV2bnN6NzNtdA==" target="_blank" class="social-icon">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/public.js') }}"></script>
 <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing navbar');
            
            // Force navbar to work properly
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                console.log('Navbar elements found');
                
                navbarToggler.addEventListener('click', function() {
                    console.log('Navbar toggler clicked');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    navbarCollapse.classList.toggle('show');
                    
                    // Force display block when showing
                    if (navbarCollapse.classList.contains('show')) {
                        navbarCollapse.style.display = 'block';
                        navbarCollapse.style.visibility = 'visible';
                        navbarCollapse.style.opacity = '1';
                    } else {
                        navbarCollapse.style.display = 'none';
                    }
                });
            } else {
                console.log('Navbar elements not found:', {
                    toggler: !!navbarToggler,
                    collapse: !!navbarCollapse
                });
            }
            
            // Close navbar when clicking on a link (mobile)
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        const navbarCollapse = document.querySelector('.navbar-collapse');
                        const navbarToggler = document.querySelector('.navbar-toggler');
                        
                        if (navbarCollapse) {
                            navbarCollapse.classList.remove('show');
                            navbarCollapse.style.display = 'none';
                        }
                        if (navbarToggler) {
                            navbarToggler.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
            });
        });

        // Additional safety check
        window.addEventListener('load', function() {
            console.log('Page fully loaded');
        });
    </script>


</body>
</html>