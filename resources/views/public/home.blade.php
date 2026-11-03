<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <title>MadrasDarbar - Food Ordering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <style>
        :root {
            --primary-red: rgb(216 25 25);
            --primary-red-dark: #6B0000;
            --primary-red-light: #F5B7B1;
            --bg-light: #F8F9FA;
            --text-dark: #2C3E50;
            --border-color: #E0E0E0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #e2d4d426;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-dark);
            padding-bottom: 160px;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
            position: relative;
        }
.top-bar {
    background-color: var(--primary-red);
    color: white;
    padding: 10px 0;
    font-size: 14px;
    z-index: 1029; /* Lower than navbar */
    position: relative;
}
       
        ::-webkit-scrollbar {
            width: 4px;
        }
       
        ::-webkit-scrollbar-track {
            background: transparent;
        }
       
        ::-webkit-scrollbar-thumb {
            background: rgba(139, 0, 0, 0.05);
            border-radius: 2px;
        }
       
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 0, 0, 0.1);
        }

.navbar {
    background-color: rgb(216 25 25);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 15px 0;
    color: white;
    z-index: 1030; /* Explicit z-index */
}

        .restaurant-name {
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-bottom: 0;
        }

        .sidebar {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .category-item {
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 15px;
            color: var(--text-dark);
            border-left: 3px solid transparent;
            -webkit-tap-highlight-color: transparent;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .category-item:hover {
            background-color: #FEF5F4;
            border-left-color: var(--primary-red-light);
        }

        .category-item.active {
            background-color: #FEF5F4;
            color: var(--primary-red);
            font-weight: 600;
            border-left-color: var(--primary-red);
        }

        .content-area {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            min-height: 600px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--text-dark);
            border-bottom: 3px solid var(--primary-red);
            display: inline-block;
            padding-bottom: 8px;
        }

        .menu-card {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .menu-card:hover {
            box-shadow: 0 4px 16px rgba(139, 0, 0, 0.15);
            transform: translateY(-2px);
        }
       
        .menu-card-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }
       
        .item-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .item-description {
            font-size: 14px;
            color: #7F8C8D;
            margin-bottom: 12px;
            line-height: 1.5;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .item-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .btn-add {
            background-color: var(--primary-red);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 14px;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            touch-action: manipulation;
        }

        .btn-add:hover, .btn-add:active {
            background-color: var(--primary-red-dark);
            transform: scale(1.05);
            cursor: pointer;
        }

        .btn-add:active {
            transform: scale(0.95);
        }

        .quantity-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-control button {
            background-color: white;
            border: none;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--primary-red);
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            min-width: 44px;
            min-height: 44px;
            -webkit-appearance: none;
            appearance: none;
        }

        .quantity-control button:hover {
            background-color: #FEF5F4;
        }

        .quantity-control input {
            width: 50px;
            text-align: center;
            border: none;
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            font-weight: 600;
            height: 44px;
            font-size: 16px;
        }

.cart-sidebar {
    position: fixed;
    right: -400px;
    top: 0;
    width: 400px;
    height: 100vh;
    background-color: white;
    box-shadow: -4px 0 16px rgba(0,0,0,0.1);
    transition: right 0.3s;
    z-index: 1060; /* Increased to 1060 */
    display: flex;
    flex-direction: column;
    height: 100%;
    max-height: 100vh;
}



        .cart-sidebar.show {
            right: 0;
        }

        .cart-header {
            background-color: var(--primary-red);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: max(20px, env(safe-area-inset-top));
        }

        .cart-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .close-cart {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-tap-highlight-color: transparent;
        }

        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            -webkit-overflow-scrolling: touch;
            overflow-y: scroll;
            -webkit-overflow-scrolling: touch;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 15px;
        }

        .cart-item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: var(--primary-red);
            font-weight: 700;
        }

        .cart-footer {
            padding: 20px;
            border-top: 2px solid var(--border-color);
            background-color: var(--bg-light);
            position: relative;
            z-index: 100;
            padding-bottom: max(20px, env(safe-area-inset-bottom));
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .btn-checkout {
            width: 100%;
            background-color: var(--primary-red);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(139, 0, 0, 0.3);
            touch-action: manipulation;
            user-select: none;
            -webkit-appearance: none;
            appearance: none;
            -webkit-tap-highlight-color: transparent;
            min-height: 44px;
            touch-action: manipulation;
        }

        .btn-checkout:hover, .btn-checkout:active {
            background-color: var(--primary-red-dark);
        }
       
        .btn-checkout:active {
            transform: translateY(2px);
            box-shadow: 0 1px 5px rgba(139, 0, 0, 0.2);
        }

        .cart-float-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background-color: var(--primary-red);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(139, 0, 0, 0.4);
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s;
            border: 3px solid white;
            -webkit-tap-highlight-color: transparent;
            bottom: max(30px, env(safe-area-inset-bottom) + 10px);
        }

        .cart-float-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(139, 0, 0, 0.5);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #2C3E50;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            border: 2px solid white;
        }

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1045; /* Between navbar (1030) and cart (1060) */
    display: none;
}

        .overlay.show {
            display: block;
        }

        .payment-option {
            cursor: pointer;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option .payment-card {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .payment-option input[type="radio"]:checked + .payment-card {
            border-color: var(--primary-red);
            background-color: #FEF5F4;
        }

        .payment-option .payment-card:hover {
            border-color: var(--primary-red-light);
            background-color: #FEF5F4;
        }

        .payment-card i {
            font-size: 32px;
            color: var(--primary-red);
            margin-bottom: 10px;
        }

        /* Modal Z-Index and Styling Fix */
        .modal {
            z-index: 1075 !important;
        }
        .modal-backdrop {
            z-index: 1070 !important;
        }
        #thankYouModal {
            z-index: 1085 !important;
        }

        .checkout-modal-dialog {
            max-width: 520px;
            width: 100%;
        }

        #checkoutModal .modal-content {
            border-radius: 18px;
            border: none;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.35);
        }

        #checkoutModal .modal-header {
            background-color: var(--primary-red);
            color: white;
            padding: 16px 20px;
            border-bottom: none;
        }

        #checkoutModal .modal-title {
            font-size: 18px;
            font-weight: 700;
        }

        #checkoutModal .modal-body {
            padding: 22px;
        }

        #checkoutModal .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1f2937;
        }

        #checkoutModal .form-control {
            font-size: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        #checkoutModal .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(216, 25, 25, 0.15);
        }

        #checkoutModal .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border-color);
            background: #f8fafc;
            display: flex;
            gap: 12px;
        }

        #checkoutModal .modal-footer .btn-secondary {
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            background-color: #64748b;
            border: none;
        }

        #checkoutModal .modal-footer #confirmOrderBtn {
            flex: 1;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 10px;
            min-height: 48px;
            background-color: var(--primary-red);
            color: white;
            border: none;
            box-shadow: 0 4px 14px rgba(216, 25, 25, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Mobile Styles */
        @media (max-width: 991px) {
            .sidebar {
                display: none;
            }
                .navbar {
        position: sticky;
        top: 0;
        z-index: 1030;
    }

            .mobile-category-menu {
                display: block !important;
            }

  .cart-sidebar {
        width: 100%;
        right: -100%;
        z-index: 1060; /* Keep it high */
    }
  .cart-sidebar.show ~ .sticky-wrapper,
    .cart-sidebar.show ~ .container {
        pointer-events: none;
        filter: blur(1px);
    }
            .cart-float-btn {
                bottom: 20px;
                right: 20px;
                width: 60px;
                height: 60px;
                z-index: 1001;
                bottom: max(20px, env(safe-area-inset-bottom) + 10px);
            }

            .cart-badge {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }

            .content-area {
                padding: 20px;
            }

            .menu-card {
                padding: 15px;
            }

            .menu-card-img {
                width: 100px;
                height: 100px;
            }

            .item-name {
                font-size: 16px;
            }

            .item-price {
                font-size: 18px;
            }
           
            .cart-footer {
                padding-bottom: 80px !important;
                padding-bottom: max(80px, env(safe-area-inset-bottom) + 60px) !important;
            }
           
            .cart-body {
                padding-bottom: 100px;
            }
           
            .btn-checkout {
                margin-bottom: 0 !important;
                position: relative;
                bottom: 0;
                z-index: 10;
            }
        }

        @media (max-width: 576px) {
            .cart-float-btn {
                width: 55px;
                height: 55px;
                bottom: 15px;
                right: 15px;
                bottom: max(15px, env(safe-area-inset-bottom) + 5px);
            }

            .cart-badge {
                width: 20px;
                height: 20px;
                font-size: 11px;
            }

            .menu-card-img {
                width: 100%;
                height: 150px;
                margin-bottom: 10px;
            }
           
            .btn-checkout {
                padding: 18px 15px !important;
                font-size: 18px !important;
                border-radius: 12px !important;
            }
           
            .cart-total {
                font-size: 20px !important;
            }
           
            .btn-add {
                padding: 14px 20px !important;
                font-size: 16px !important;
            }
            
            .cart-sidebar {
                height: 100vh;
                height: -webkit-fill-available;
            }
            
            .cart-body {
                max-height: calc(100vh - 200px);
                max-height: -webkit-fill-available;
            }

            /* Checkout Modal Mobile Styles */
            .checkout-modal-dialog {
                margin: 10px auto;
                max-width: calc(100% - 20px);
                width: calc(100% - 20px);
            }

            #checkoutModal .modal-content {
                border-radius: 16px;
            }

            #checkoutModal .modal-header {
                padding: 14px 16px;
            }

            #checkoutModal .modal-title {
                font-size: 16px;
            }

            #checkoutModal .modal-body {
                padding: 16px 14px;
                max-height: calc(100dvh - 150px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            #checkoutModal .payment-card {
                padding: 12px 8px;
            }

            #checkoutModal .payment-card i {
                font-size: 24px;
                margin-bottom: 4px;
            }

            #checkoutModal .payment-card .fw-bold {
                font-size: 14px;
            }

            #checkoutModal .payment-card small {
                font-size: 11px;
            }

            #checkoutModal .modal-footer {
                padding: 12px 14px;
            }

            #checkoutModal .modal-footer .btn-secondary {
                padding: 10px 16px;
                font-size: 14px;
            }

            #checkoutModal .modal-footer #confirmOrderBtn {
                padding: 10px 16px;
                font-size: 15px;
            }

            #locationButton, #mapButton {
                font-size: 12px;
                padding: 7px 10px;
            }
        }

        .category-section {
            display: none;
        }

        .category-section.active {
            display: block;
        }

        .search-bar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .cart-pulse {
            animation: pulse 0.5s ease-in-out;
        }

        .description-fallback {
            color: #ccc;
            font-style: italic;
        }

        .mobile-search-bar {
            display: none;
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        @media (max-width: 991px) {
            .mobile-search-bar {
                display: block;
            }
        }

        /* Orders Button */
        .orders-float-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 70px;
            height: 70px;
            background-color: var(--primary-red);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(139, 0, 0, 0.4);
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s;
            border: 3px solid white;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
            bottom: max(30px, env(safe-area-inset-bottom) + 10px);
        }

        .orders-float-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(139, 0, 0, 0.5);
            color: white;
        }

        @media (max-width: 991px) {
            .orders-float-btn {
                bottom: 20px;
                left: 20px;
                width: 60px;
                height: 60px;
                bottom: max(20px, env(safe-area-inset-bottom) + 10px);
            }
        }

        @media (max-width: 576px) {
            .orders-float-btn {
                width: 55px;
                height: 55px;
                bottom: 15px;
                left: 15px;
                bottom: max(15px, env(safe-area-inset-bottom) + 5px);
            }
        }
       
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            animation: fadeIn 0.3s;
            overflow-y: auto;
            padding: 20px 0;
        }

        .image-modal-content {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 600px;
            max-height: 60vh;
            object-fit: contain;
            animation: zoomIn 0.3s;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .image-modal-close {
            position: fixed;
            top: 20px;
            right: 25px;
            color: white;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1101;
            background: rgba(0,0,0,0.6);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .image-modal-close:hover {
            color: #ff6b6b;
            background: rgba(0,0,0,0.8);
            transform: scale(1.1);
        }

        .modal-caption-container {
            text-align: center;
            color: white;
            padding: 25px 20px;
            max-width: 600px;
            margin: 20px auto 0;
            background: rgba(0,0,0,0.7);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .modal-item-name {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .modal-item-description {
            font-size: 16px;
            color: #e0e0e0;
            line-height: 1.5;
            margin-bottom: 15px;
            max-height: none;
            overflow: visible;
            text-overflow: unset;
            display: block;
            -webkit-line-clamp: unset;
            -webkit-box-orient: unset;
        }

        .modal-item-price {
            font-size: 22px;
            font-weight: bold;
            color: #ff6b6b;
            margin-top: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
       
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: rgb(216, 25, 25);
            color: white;
            text-align: center;
            padding: 2px 10px;
            font-size: 14px;
            z-index: 999;
            padding-bottom: max(2px, env(safe-area-inset-bottom));
        }
       
        @media (max-width: 576px) {
            .footer {
                font-size: 12px;
                padding: 10px 8px;
                padding-bottom: max(10px, env(safe-area-inset-bottom) + 5px);
            }
        }
       
        .cart-toast {
            position: fixed;
            bottom: 100px;
            right: 30px;
            background-color: var(--primary-red);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            z-index: 1050;
            display: flex;
            align-items: center;
            gap: 12px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .cart-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .cart-toast-icon {
            font-size: 20px;
        }

        .cart-toast-message {
            font-size: 15px;
            font-weight: 500;
        }

        @media (max-width: 991px) {
            .cart-toast {
                bottom: 90px;
                right: 20px;
                left: 20px;
                padding: 12px 20px;
                bottom: max(90px, env(safe-area-inset-bottom) + 70px);
            }
        }

        @media (max-width: 576px) {
            .cart-toast {
                bottom: 80px;
                right: 15px;
                left: 15px;
                padding: 10px 15px;
                bottom: max(80px, env(safe-area-inset-bottom) + 60px);
            }
        }
       
.sticky-wrapper {
    position: sticky;
    top: 0;
    z-index: 1030; /* Lower than cart sidebar */
}

        /* iOS Safari Fix */
        @supports (-webkit-touch-callout: none) {
            .cart-sidebar {
                height: 100vh;
                height: -webkit-fill-available;
                max-height: -webkit-fill-available;
            }

            /* Checkout Modal Mobile Styles */
            .checkout-modal-dialog {
                margin: 10px auto;
                max-width: calc(100% - 20px);
                width: calc(100% - 20px);
            }

            #checkoutModal .modal-content {
                border-radius: 16px;
            }

            #checkoutModal .modal-header {
                padding: 14px 16px;
            }

            #checkoutModal .modal-title {
                font-size: 16px;
            }

            #checkoutModal .modal-body {
                padding: 16px 14px;
                max-height: calc(100dvh - 150px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            #checkoutModal .payment-card {
                padding: 12px 8px;
            }

            #checkoutModal .payment-card i {
                font-size: 24px;
                margin-bottom: 4px;
            }

            #checkoutModal .payment-card .fw-bold {
                font-size: 14px;
            }

            #checkoutModal .payment-card small {
                font-size: 11px;
            }

            #checkoutModal .modal-footer {
                padding: 12px 14px;
            }

            #checkoutModal .modal-footer .btn-secondary {
                padding: 10px 16px;
                font-size: 14px;
            }

            #checkoutModal .modal-footer #confirmOrderBtn {
                padding: 10px 16px;
                font-size: 15px;
            }

            #locationButton, #mapButton {
                font-size: 12px;
                padding: 7px 10px;
            }
           
            .cart-body {
                max-height: -webkit-fill-available;
            }

            /* Checkout Modal Mobile Styles */
            .checkout-modal-dialog {
                margin: 10px auto;
                max-width: calc(100% - 20px);
                width: calc(100% - 20px);
            }

            #checkoutModal .modal-content {
                border-radius: 16px;
            }

            #checkoutModal .modal-header {
                padding: 14px 16px;
            }

            #checkoutModal .modal-title {
                font-size: 16px;
            }

            #checkoutModal .modal-body {
                padding: 16px 14px;
                max-height: calc(100dvh - 150px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            #checkoutModal .payment-card {
                padding: 12px 8px;
            }

            #checkoutModal .payment-card i {
                font-size: 24px;
                margin-bottom: 4px;
            }

            #checkoutModal .payment-card .fw-bold {
                font-size: 14px;
            }

            #checkoutModal .payment-card small {
                font-size: 11px;
            }

            #checkoutModal .modal-footer {
                padding: 12px 14px;
            }

            #checkoutModal .modal-footer .btn-secondary {
                padding: 10px 16px;
                font-size: 14px;
            }

            #checkoutModal .modal-footer #confirmOrderBtn {
                padding: 10px 16px;
                font-size: 15px;
            }

            #locationButton, #mapButton {
                font-size: 12px;
                padding: 7px 10px;
                -webkit-overflow-scrolling: touch;
            }
           
            .btn-add {
                padding: 14px 24px !important;
                font-size: 16px !important;
                cursor: pointer;
            }
            
            .cart-sidebar.show ~ body {
                overflow: hidden;
            }
            
            .btn-add:active, .btn-checkout:active, .quantity-control button:active {
                opacity: 0.7;
                transform: scale(0.98);
                transition: transform 0.1s, opacity 0.1s;
            }
            
            .cart-body {
                -webkit-overflow-scrolling: touch;
                overflow-y: scroll;
            }
        }

        /* Fix for iOS Safari button styling */
        button, [type="button"], [type="reset"], [type="submit"] {
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-appearance: none;
            appearance: none;
            -webkit-tap-highlight-color: transparent;
        }
       
        /* iPhone specific fixes */
        @media (max-width: 767px) {
            .btn-add {
                padding: 14px 20px;
                font-size: 15px;
                min-height: 48px;
            }
           
            .menu-card {
                padding: 16px;
            }
            
            input, select, textarea {
                font-size: 16px !important;
            }
            
            body {
                position: relative;
                overflow-y: scroll;
                -webkit-overflow-scrolling: touch;
            }
            
            .cart-sidebar.show {
                position: fixed;
                z-index: 1050;
            }
        }
        
        html {
            -webkit-text-size-adjust: 100%;
        }

        /* Order notes section */
        .order-notes-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Empty cart state */
        .empty-cart-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-cart-state i {
            font-size: 60px;
            color: #dee2e6;
            margin-bottom: 20px;
        }
    </style>
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

    <div class="sticky-wrapper">
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
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-none d-md-inline-flex" type="button" onclick="toggleSearch()" style="color:white">
                            <i class="bi bi-search me-2"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Search Bar -->
        <div class="container">
            <div class="mobile-search-bar" id="mobileSearchBar">
                <div class="input-group">
                    <input type="text" class="form-control"
                           placeholder="Search for dishes..."
                           id="mobileSearchInput"
                           oninput="performLiveSearch(this.value.toLowerCase())">
                </div>
            </div>
        </div>

        <div class="container d-none" id="searchBar">
            <div class="search-bar">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   placeholder="Search for dishes..."
                                   id="searchInput"
                                   oninput="performLiveSearch(this.value.toLowerCase())">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar">
                    <h5 class="sidebar-title">Menu Categories</h5>
                    @foreach($categories as $index => $category)
                        <div class="category-item {{ $index === 0 ? 'active' : '' }}"
                             data-category-id="{{ $category->id }}"
                             onclick="showCategory('{{ $category->id }}')">
                            {{ $category->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12 d-lg-none mb-3">
                <div class="mobile-category-menu" style="background-color: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <select class="form-select" onchange="showCategory(this.value)">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="content-area">
                    @foreach($foodMenuByCategory as $categoryId => $categoryData)
                        <div class="category-section {{ $loop->first ? 'active' : '' }}" id="category-{{ $categoryId }}">
                            <h2 class="section-title">{{ $categoryData['category']->name }}</h2>
                           
                            @if($categoryData['items']->count() > 0)
                                @foreach($categoryData['items'] as $item)
                                    <div class="menu-card" data-item-name="{{ strtolower($item['name']) }}">
                                        <div class="row align-items-center">
                                            @if($item['image'])
                                            <div class="col-md-3 col-12">
                                                <img src="{{ asset($item['image']) }}"
                                                    class="menu-card-img w-100"
                                                    alt="{{ $item['name'] }}"
                                                    onclick="openImageModal(
                                                        this.src,
                                                        '{{ addslashes($item['name']) }}',
                                                        '{{ addslashes($item['description'] ?? '') }}',
                                                        {{ $item['price'] }}
                                                    )">
                                            </div>
                                            @endif
                                            <div class="{{ $item['image'] ? 'col-md-6' : 'col-md-9' }} col-12 mt-3 mt-md-0">
                                                <h5 class="item-name">{{ $item['name'] }}</h5>
                                                @if(!empty($item['description']) && !str_contains($item['description'], 'r6rgqeLbQaR1llPCw3V0jTC7ghng1tDrKpwuvhHNWqTQuDfxgx1pc'))
                                                    <p class="item-description">{{ $item['description'] }}</p>
                                                @else
                                                    <p class="item-description description-fallback">Delicious {{ $item['name'] }} prepared with fresh ingredients</p>
                                                @endif
                                                <div class="item-price">{{ $location->currency }} {{ number_format($item['price'], 2) }}</div>
                                            </div>
                                            <!-- Change this in your menu card section -->
                                            <div class="col-md-3 col-12 text-md-end text-center mt-3 mt-md-0">
                                                <button class="btn-add add-to-cart-btn"
                                                        data-item-id="{{ $item['id'] }}"
                                                        data-item-name="{{ addslashes($item['name']) }}"
                                                        data-item-price="{{ $item['price'] }}">
                                                    Add <i class="bi bi-plus-lg ms-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-3 text-muted">No items available in this category</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Float Button - FIXED: Make sure the route exists in your Laravel routes -->
    <a href="{{ route('orders.history') }}" class="orders-float-btn" title="My Orders">
        <i class="bi bi-bag-check" style="font-size: 24px;"></i>
    </a>

    <!-- Cart Float Button -->
    <div class="cart-float-btn" onclick="toggleCart()">
        <i class="bi bi-bag-fill" style="font-size: 24px;"></i>
        <span class="cart-badge" id="cartCount">0</span>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h5><i class="bi bi-bag-fill me-2"></i> Your Order Summary</h5>
            <button class="close-cart" onclick="toggleCart()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="cart-body" id="cartBody">
            <div class="empty-cart-state">
                <i class="bi bi-cart-x"></i>
                <p class="mt-3 text-muted">Your cart is empty</p>
                <p class="text-muted small">Add items from the menu to get started</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="order-notes-container">
                <label for="orderNotes" class="form-label fw-bold mb-2">Order Notes (Optional)</label>
                <textarea class="form-control" rows="2" placeholder="Any special instructions?" id="orderNotes"></textarea>
            </div>
            <div id="chargesBreakdown" class="mb-3"></div>
            <button class="btn-checkout" onclick="checkout()" id="checkoutBtn" disabled>
                Checkout <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="toggleCart()"></div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" alt="Food Item">
        <div class="modal-caption-container">
            <div id="modalItemName"></div>
            <div id="modalItemDescription"></div>
            <div id="modalItemPrice"></div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable checkout-modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary-red); color: white;">
                    <h5 class="modal-title"><i class="bi bi-clipboard-check me-2"></i>Complete Your Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="checkoutError"></div>
                   
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label for="customerName" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" placeholder="Enter your Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="door_no" class="form-label fw-bold">Door/Flat Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="door_no" placeholder="e.g., 123, Flat No. 4A" required>
                        </div>
   
                        <div class="mb-3">
                            <label for="area" class="form-label fw-bold">Area/Locality <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="area" placeholder="e.g., T. Nagar, Anna Nagar" required>
                        </div>

                        <div class="mb-3">
                            <label for="customerAddress" class="form-label fw-bold">Delivery Address <span class="text-danger">*</span> 
                            (<span class="fw-semibold ms-1">Please enable your location <i class="bi bi-geo-alt-fill text-danger"></i></span>)</label>
                            <textarea class="form-control" id="customerAddress" rows="3" placeholder="Click 'Use My Current Location' to get your address" readonly required onclick="if(!this.value.trim() || !this.value.startsWith('Lat:')) this.removeAttribute('readonly')"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Payment Method <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="paymentType" value="cash" checked>
                                        <div class="payment-card">
                                            <i class="bi bi-cash-stack"></i>
                                            <div class="fw-bold">Cash</div>
                                            <small class="text-muted">Pay on delivery</small>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="payment-option w-100">
                                        <input type="radio" name="paymentType" value="card">
                                        <div class="payment-card">
                                            <i class="bi bi-credit-card"></i>
                                            <div class="fw-bold">Card</div>
                                            <small class="text-muted">Pay by card</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Order Total:</strong>
                                <strong style="font-size: 22px; color: var(--primary-red);">{{ $location->currency }} <span id="modalTotal">0.00</span></strong>
                            </div>
                            @if(strtolower(session('order_type', 'delivery')) === 'delivery')
                            <div class="small text-muted mt-1">
                                <i class="bi bi-info-circle me-1"></i>Delivery fee is separate for each order.
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-checkout" id="confirmOrderBtn" onclick="confirmOrder()">
                        Confirm Order <i class="bi bi-check-lg ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thank You Modal -->
    <div class="modal fade" id="thankYouModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-5 px-4">
                    <div class="thank-you-check">
                        <i class="bi bi-check2"></i>
                    </div>
                    <h4 class="mb-3 fw-bold" style="color: var(--primary-red);">Thank You!</h4>
                    <p class="text-muted mb-4">Your order has been placed successfully.</p>
                    <button type="button" class="btn btn-checkout w-100" data-bs-dismiss="modal" onclick="location.reload()">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="cart-toast" id="cartToast">
        <i class="bi bi-check-circle cart-toast-icon"></i>
        <span class="cart-toast-message" id="cartToastMessage">Item added to cart!</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    
    <script>
      // =============================================
    // FIXED JAVASCRIPT CODE - WORKING VERSION
    // =============================================

    // Global variables
    let cart = [];
    let activeCharges = [];
    let currentLocationId = {{ $location->id ?? 1 }};
    let isCheckingOut = false;
    let userLatitude = null;
    let userLongitude = null;
    let locationCurrency = '{{ $location->currency }}';

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing...');
       
        // Load charges for current location from database
        loadChargesFromDatabase(currentLocationId);
       
        // Initialize cart
        loadCartFromStorage();
        updateCartDisplay();
       
        // Setup event listeners
        setupEventListeners();
       
        // Get user location
        getUserLocation();
       
        // Initialize search functionality
        initializeSearch();
    });

    // =============================================
    // SEARCH FUNCTIONALITY
    // =============================================
    function initializeSearch() {
        // Show ALL categories by default (no active category)
        document.querySelectorAll('.category-section').forEach(section => {
            section.style.display = 'block';
            section.classList.remove('active');
        });
        
        // Clear active category from sidebar
        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Clear mobile dropdown selection
        const mobileSelect = document.querySelector('.mobile-category-menu select');
        if (mobileSelect) {
            mobileSelect.value = '';
            mobileSelect.addEventListener('change', function() {
                if (this.value) {
                    showCategory(this.value);
                } else {
                    // If "Select Category" is chosen, show all
                    document.querySelectorAll('.category-section').forEach(section => {
                        section.style.display = 'block';
                        section.classList.remove('active');
                    });
                    document.querySelectorAll('.category-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    document.querySelectorAll('.menu-card').forEach(card => {
                        card.style.display = 'block';
                    });
                    
                    // Clear search
                    const searchInput = document.getElementById('searchInput');
                    const mobileSearchInput = document.getElementById('mobileSearchInput');
                    if (searchInput) searchInput.value = '';
                    if (mobileSearchInput) mobileSearchInput.value = '';
                    
                    hideNoResultsMessage();
                }
            });
        }
        
        // Initialize live search
        setupLiveSearch();
    }

    function setupLiveSearch() {
        const desktopSearch = document.getElementById('searchInput');
        const mobileSearch = document.getElementById('mobileSearchInput');
        
        if (desktopSearch) {
            desktopSearch.addEventListener('input', function() {
                performLiveSearch(this.value.toLowerCase());
            });
        }
        
        if (mobileSearch) {
            mobileSearch.addEventListener('input', function() {
                performLiveSearch(this.value.toLowerCase());
            });
        }
    }

    function showCategory(categoryId) {
        // Clear search inputs
        const searchInput = document.getElementById('searchInput');
        const mobileSearchInput = document.getElementById('mobileSearchInput');
        if (searchInput) searchInput.value = '';
        if (mobileSearchInput) mobileSearchInput.value = '';
        
        // Remove no results message if exists
        const noResultsMsg = document.querySelector('.no-results-message');
        if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
        
        // Show selected category
        document.querySelectorAll('.category-section').forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
        });
        
        const selectedSection = document.getElementById('category-' + categoryId);
        if (selectedSection) {
            selectedSection.classList.add('active');
            selectedSection.style.display = 'block';
            
            // Show all items in the category
            selectedSection.querySelectorAll('.menu-card').forEach(card => {
                card.style.display = 'block';
            });
        }
        
        // Update active category in sidebar
        document.querySelectorAll('.category-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`.category-item[data-category-id="${categoryId}"]`)?.classList.add('active');
        
        // Update mobile dropdown
        const mobileSelect = document.querySelector('.mobile-category-menu select');
        if (mobileSelect) {
            mobileSelect.value = categoryId;
        }
    }

    function performLiveSearch(searchTerm) {
        const activeCategorySection = document.querySelector('.category-section.active');
        const activeCategoryId = activeCategorySection ? activeCategorySection.id.replace('category-', '') : null;
        
        // Get mobile select value if no active category
        const mobileSelect = document.querySelector('.mobile-category-menu select');
        const mobileCategoryId = mobileSelect?.value || null;
        
        // Determine which category is active (if any)
        let currentCategoryId = activeCategoryId || mobileCategoryId;
        
        if (!searchTerm.trim()) {
            // If search is empty, show all items based on current view
            if (currentCategoryId) {
                // Show only selected category items
                document.querySelectorAll('.category-section').forEach(section => {
                    if (section.id === `category-${currentCategoryId}`) {
                        section.style.display = 'block';
                        section.classList.add('active');
                        section.querySelectorAll('.menu-card').forEach(card => {
                            card.style.display = 'block';
                        });
                    } else {
                        section.style.display = 'none';
                        section.classList.remove('active');
                    }
                });
            } else {
                // Show all items from all categories
                document.querySelectorAll('.category-section').forEach(section => {
                    section.style.display = 'block';
                    section.classList.remove('active');
                    section.querySelectorAll('.menu-card').forEach(card => {
                        card.style.display = 'block';
                    });
                });
            }
            
            // Hide no results message
            const noResultsMsg = document.querySelector('.no-results-message');
            if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
            
            return;
        }
        
        // If a category is selected, search only within that category
        if (currentCategoryId) {
            // Search within selected category only
            const selectedSection = document.getElementById(`category-${currentCategoryId}`);
            if (selectedSection) {
                // Hide all other categories
                document.querySelectorAll('.category-section').forEach(section => {
                    if (section.id !== `category-${currentCategoryId}`) {
                        section.style.display = 'none';
                        section.classList.remove('active');
                    } else {
                        section.style.display = 'block';
                        section.classList.add('active');
                    }
                });
                
                // Show/hide items in selected category based on search
                let foundResults = false;
                selectedSection.querySelectorAll('.menu-card').forEach(card => {
                    const itemName = card.getAttribute('data-item-name');
                    if (itemName.includes(searchTerm)) {
                        card.style.display = 'block';
                        foundResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Show no results message if no items found
                if (!foundResults) {
                    showNoResultsMessage(searchTerm);
                } else {
                    hideNoResultsMessage();
                }
            }
        } else {
            // No category selected - search across ALL categories
            let foundResults = false;
            let anyCategoryHasResults = false;
            
            document.querySelectorAll('.category-section').forEach(section => {
                let categoryHasResults = false;
                
                section.querySelectorAll('.menu-card').forEach(card => {
                    const itemName = card.getAttribute('data-item-name');
                    if (itemName.includes(searchTerm)) {
                        card.style.display = 'block';
                        categoryHasResults = true;
                        foundResults = true;
                        anyCategoryHasResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Show/hide entire category section based on results
                section.style.display = categoryHasResults ? 'block' : 'none';
                
                // Remove active class from all sections when searching all
                section.classList.remove('active');
            });
            
            // If any category has results, show them all
            if (anyCategoryHasResults) {
                document.querySelectorAll('.category-section').forEach(section => {
                    let hasVisibleItems = false;
                    section.querySelectorAll('.menu-card').forEach(card => {
                        if (card.style.display !== 'none') {
                            hasVisibleItems = true;
                        }
                    });
                    section.style.display = hasVisibleItems ? 'block' : 'none';
                });
            }
            
            // Show/hide no results message
            if (!foundResults) {
                showNoResultsMessage(searchTerm);
            } else {
                hideNoResultsMessage();
            }
        }
    }

    function showNoResultsMessage(searchTerm) {
        const contentArea = document.querySelector('.content-area');
        let noResultsMsg = contentArea.querySelector('.no-results-message');
        
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results-message text-center py-5';
            contentArea.appendChild(noResultsMsg);
        }
        
        noResultsMsg.innerHTML = `
            <i class="bi bi-search" style="font-size: 48px; color: #ccc;"></i>
            <p class="mt-3 text-muted">No items found matching "${searchTerm}"</p>
        `;
        noResultsMsg.style.display = 'block';
    }

    function hideNoResultsMessage() {
        const noResultsMsg = document.querySelector('.no-results-message');
        if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }

    // =============================================
    // CART STORAGE FUNCTIONS
    // =============================================
    function loadCartFromStorage() {
        // Always return empty cart
        cart = [];
        console.log('Cart initialized as empty');
    }

    /*
    function saveCartToStorage() {
        // Don't save anything - cart only exists in current session memory
        // Remove this line completely or comment it out
        // sessionStorage.setItem('madrasCart', JSON.stringify(cart));
    }

    // Initialize when page loads
    // duplicate removed
        console.log('Page loaded, initializing...');
        
        // Clear any stored cart data
        localStorage.removeItem('madrasCart');
        sessionStorage.removeItem('madrasCart');
        
        // Load charges for current location from database
        loadChargesFromDatabase(currentLocationId);
        
        // Initialize empty cart
        loadCartFromStorage();
        updateCartDisplay();
        
        // Setup event listeners
        setupEventListeners();
        
        // Get user location
        getUserLocation();
        
        // Initialize search functionality
        initializeSearch();
    });
    */

    function saveCartToStorage() {
        try {
            localStorage.setItem('madrasCart', JSON.stringify(cart));
        } catch (e) {
            console.error('Error saving cart to storage:', e);
        }
    }

  // =============================================
// EVENT LISTENER SETUP
// =============================================
function setupEventListeners() {
    console.log('Setting up event listeners...');
    
    // Add to cart buttons - Using event delegation to prevent duplicate binding
    document.addEventListener('click', function(event) {
        // Check if the clicked element or its parent has the add-to-cart-btn class
        const addButton = event.target.closest('.add-to-cart-btn');
        if (addButton) {
            event.preventDefault();
            event.stopPropagation();
            
            const itemId = addButton.getAttribute('data-item-id');
            const itemName = addButton.getAttribute('data-item-name');
            const itemPrice = addButton.getAttribute('data-item-price');
            
            if (itemId && itemName && itemPrice) {
                addToCart(itemId, itemName, itemPrice);
            }
            return false;
        }
    });
    
    // Remove the old event listener binding to prevent duplication
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.replaceWith(button.cloneNode(true));
    });
    
    // Overlay and cart toggles
    document.getElementById('overlay').addEventListener('click', toggleCart);
   
    // Escape key to close cart
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && document.getElementById('cartSidebar').classList.contains('show')) {
            toggleCart();
        }
    });
    
    // Setup checkout modal
    const checkoutModalElInit = document.getElementById('checkoutModal');
    if (checkoutModalElInit) {
        checkoutModalElInit.addEventListener('hidden.bs.modal', () => {
            if (!window.orderJustPlaced && cart && cart.length > 0) {
                const cartSidebar = document.getElementById('cartSidebar');
                const overlay = document.getElementById('overlay');
                if (cartSidebar) cartSidebar.classList.add('show');
                if (overlay) overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            window.orderJustPlaced = false;
        });
    }
    const checkoutModal = document.getElementById('checkoutModal');
    if (checkoutModal) {
        checkoutModal.addEventListener('shown.bs.modal', () => {
            setTimeout(() => {
                addLocationButtonToCheckout();
                if (userLatitude && userLongitude) {
                    const addressField = document.getElementById('customerAddress');
                    if (addressField) {
                        storeLatLonInAddress(userLatitude, userLongitude, true);
                    }
                    if (false) {
                        addressField.value = `Lat: ${userLatitude.toFixed(6)}, Lon: ${userLongitude.toFixed(6)}`;
                    }
                } else {
                    getUserLocation(false);
                }
            }, 100);
        });
    }
}
   // =============================================
// ADD TO CART FUNCTION (Updated)
// =============================================
function addToCart(itemId, name, price, event) {
    // Prevent multiple clicks
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    console.log('Adding to cart:', itemId, name, price);
    
    // Check if this is a duplicate call (debounce)
    const now = Date.now();
    if (window.lastAddToCartTime && (now - window.lastAddToCartTime) < 500) {
        console.log('Preventing duplicate add to cart');
        return;
    }
    window.lastAddToCartTime = now;
    
    // Convert to proper types
    const id = parseInt(itemId);
    const itemPrice = parseFloat(price);
    
    const existingItem = cart.find(item => item.id === id);
   
    if (existingItem) {
        existingItem.quantity += 1;
        showToast(`Increased quantity of ${name} to ${existingItem.quantity}`);
    } else {
        cart.push({ id: id, name: name, price: itemPrice, quantity: 1 });
        showToast(`${name} added to cart!`);
    }
   
    saveCartToStorage();
    updateCartDisplay();
    
    // Add pulse animation to cart button
    const cartBtn = document.querySelector('.cart-float-btn');
    cartBtn.classList.add('cart-pulse');
    setTimeout(() => cartBtn.classList.remove('cart-pulse'), 500);
}

    function incrementItem(index) {
        if (cart[index]) {
            cart[index].quantity += 1;
            saveCartToStorage();
            updateCartDisplay();
            showToast(`Increased quantity of ${cart[index].name}`, 'info');
        }
    }

    function decrementItem(index) {
        if (cart[index]) {
            if (cart[index].quantity > 1) {
                cart[index].quantity -= 1;
                saveCartToStorage();
                updateCartDisplay();
                showToast(`Decreased quantity of ${cart[index].name}`, 'info');
            } else {
                removeItem(index);
            }
        }
    }

    function removeItem(index) {
        if (cart[index]) {
            const itemName = cart[index].name;
            cart.splice(index, 1);
            saveCartToStorage();
            updateCartDisplay();
            showToast(`${itemName} removed from cart`, 'info');
        }
    }

    // =============================================
    // UPDATE CART DISPLAY
    // =============================================
    function updateCartDisplay() {
        const cartBody = document.getElementById('cartBody');
        const cartCount = document.getElementById('cartCount');
        const chargesBreakdown = document.getElementById('chargesBreakdown');
        const checkoutBtn = document.getElementById('checkoutBtn');
       
        let subtotal = 0;
        let totalItems = 0;
       
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            totalItems += item.quantity;
        });
       
        const chargeCalculation = calculateCharges(subtotal);
        cartCount.textContent = totalItems;
       
        if (cart.length === 0) {
            cartBody.innerHTML = `
                <div class="empty-cart-state">
                    <i class="bi bi-cart-x"></i>
                    <p class="mt-3 text-muted">Your cart is empty</p>
                    <p class="text-muted small">Add items from the menu to get started</p>
                </div>
            `;
            chargesBreakdown.innerHTML = '';
            checkoutBtn.disabled = true;
            checkoutBtn.style.opacity = '0.7';
            return;
        }
       
        let html = '';
        cart.forEach((item, index) => {
            const itemTotal = (item.price * item.quantity).toFixed(2);
            html += `
                <div class="cart-item">
                    <div class="flex-grow-1">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="cart-item-price">${locationCurrency} ${item.price.toFixed(2)}</div>
                            <small class="text-muted">× ${item.quantity}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="quantity-control">
                            <button type="button" onclick="decrementItem(${index})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" value="${item.quantity}" readonly class="quantity-input">
                            <button type="button" onclick="incrementItem(${index})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeItem(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
       
        let chargesHtml = '<div class="charges-breakdown-details mt-3">';
        chargeCalculation.charges.forEach(charge => {
            if (charge.isDelivery) return;
            if (charge.isItemTotal) {
                chargesHtml += `
                    <div class="charge-item d-flex justify-content-between mb-2">
                        <span class="charge-name fw-bold">Item Total</span>
                        <span class="charge-amount fw-bold">${locationCurrency} ${charge.amount.toFixed(2)}</span>
                    </div>
                `;
            } else {
                const prefix = charge.isDiscount ? '-' : '+';
                const textClass = charge.isDiscount ? 'text-success' : 
                                charge.isTax ? 'text-danger' : '';
                chargesHtml += `
                    <div class="charge-item d-flex justify-content-between mb-1 ${textClass}">
                        <span class="charge-name">${charge.name}</span>
                        <span class="charge-amount">${prefix}${locationCurrency} ${Math.abs(charge.amount).toFixed(2)}</span>
                    </div>
                `;
            }
        });

        @if(strtolower(session('order_type', 'delivery')) === 'delivery')
        chargesHtml += `
            <div class="charge-item d-flex justify-content-between mb-1 text-muted small">
                <span><i class="bi bi-info-circle me-1"></i>Delivery Fee</span>
                <span class="fst-italic">Calculated separately</span>
            </div>
        `;
        @endif
       
        chargesHtml += `
            <hr class="my-3">
            <div class="charge-item d-flex justify-content-between mt-3 pt-3 border-top fw-bold" style="font-size: 1.2rem;">
                <span>To Pay</span>
                <span style="color: var(--primary-red);">${locationCurrency} ${chargeCalculation.grandTotal.toFixed(2)}</span>
            </div>
        </div>`;
       
        cartBody.innerHTML = html;
        chargesBreakdown.innerHTML = chargesHtml;
        checkoutBtn.disabled = false;
        checkoutBtn.style.opacity = '1';
       
        const modalTotal = document.getElementById('modalTotal');
        if (modalTotal) modalTotal.textContent = chargeCalculation.grandTotal.toFixed(2);
       
        window.currentCartData = {
            subtotal: subtotal,
            charges: chargeCalculation.charges.filter(c => !c.isItemTotal && !c.isDelivery),
            totalCharges: chargeCalculation.totalCharges,
            grandTotal: chargeCalculation.grandTotal,
            itemCount: totalItems
        };
    }

    // =============================================
    // CHARGE MANAGEMENT
    // =============================================
    async function loadChargesFromDatabase(locationId) {
        try {
            const response = await fetch(`/charges?location_id=${locationId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
           
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.charges?.length > 0) {
                    // Filter out delivery charges since delivery is separate per order
                    activeCharges = data.charges.filter(charge => 
                        !charge.name.toLowerCase().includes('delivery')
                    );
                    updateCartDisplay();
                }
            }
        } catch (error) {
            console.error('Error loading charges:', error);
        }
    }

    function calculateCharges(subtotal) {
        const charges = [{
            id: 0,
            name: 'Item Total',
            type: 0,
            rate: 0,
            amount: subtotal,
            isItemTotal: true
        }];
       
        let totalCharges = 0;
       
        if (activeCharges?.length > 0) {
            activeCharges.forEach(charge => {
                if (charge.status !== 1) return;
                // Exclude delivery charges because delivery charge is separate per order
                if (charge.name && charge.name.toLowerCase().includes('delivery')) return;
               
                let amount = charge.type === 2 ? (subtotal * charge.amount) / 100 : charge.amount;
                const calculatedAmount = parseFloat(amount.toFixed(2));
               
                charges.push({
                    id: charge.id,
                    name: charge.name,
                    type: charge.type,
                    rate: charge.amount,
                    amount: calculatedAmount,
                    isPercentage: charge.type === 2,
                    isDiscount: calculatedAmount < 0 || charge.name.toLowerCase().includes('discount'),
                    isTax: charge.name.toLowerCase().includes('tax'),
                    isDelivery: false
                });
               
                totalCharges += calculatedAmount;
            });
        }
       
        return {
            charges: charges,
            subtotal: subtotal,
            totalCharges: parseFloat(totalCharges.toFixed(2)),
            grandTotal: parseFloat((subtotal + totalCharges).toFixed(2))
        };
    }

    // =============================================
    // LOCATION FUNCTIONS
    // =============================================
    /* OLD LOCATION FUNCTIONS BYPASSED FOR IOS OPTIMIZED VERSION
    function old_getUserLocation() {
        if (!navigator.geolocation) {
            showToast("Geolocation not supported", "info");
            return;
        }

        const addressField = document.getElementById('customerAddress');
        if (addressField) {
            addressField.placeholder = "Detecting your location...";
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 2000
        };

        navigator.geolocation.watchPosition(
            async (pos) => {
                userLatitude = pos.coords.latitude;
                userLongitude = pos.coords.longitude;
                const accuracy = Math.round(pos.coords.accuracy);

                console.log("Lat:", userLatitude, "Lng:", userLongitude, "Accuracy:", accuracy);

                if (accuracy > 100) {
                    showToast("📍 Location is approximate. Please verify.", "info");
                }

                storeLatLonInAddress(userLatitude, userLongitude);
            },
            (err) => {
                if (err.code === 1) {
                    showToast("Please allow location access", "info");
                } else {
                    showToast("Unable to fetch location", "error");
                }
                if (addressField) {
                    addressField.placeholder = "Enter your delivery address";
                }
            },
            options
        );
    }

    function storeLatLonInAddress(lat, lon) {
        const addressField = document.getElementById('customerAddress');
       
        if (addressField && !addressField.value.trim()) {
            const locationString = `Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}`;
            addressField.value = locationString;
            addressField.dispatchEvent(new Event('input', { bubbles: true }));
            showToast("📍 Location coordinates stored", "success");
        }
    }

    function detectMyLocation() {
        const addressField = document.getElementById('customerAddress');
        if (addressField) {
            addressField.placeholder = "Detecting your location...";
            addressField.value = "";
        }
        getUserLocation();
    }
    */

    // =============================================
    // LOCATION FUNCTIONS (iOS & Mobile Optimized)
    // =============================================
    let isFetchingLocation = false;

    function getUserLocation(isUserGesture = false) {
        if (!navigator.geolocation) {
            showToast("Geolocation is not supported by your browser", "info");
            return;
        }

        if (window.isSecureContext === false && location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            console.warn("Geolocation requires HTTPS on iOS Safari");
            showToast("📍 iOS requires HTTPS to access location", "info");
        }

        if (isFetchingLocation) return;
        isFetchingLocation = true;

        const addressField = document.getElementById('customerAddress');
        const locationButton = document.getElementById('locationButton');

        if (addressField && !addressField.value.trim()) {
            addressField.placeholder = "Detecting your location...";
        }
        if (locationButton) {
            locationButton.disabled = true;
            locationButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Detecting...';
        }

        const handleSuccess = (pos) => {
            isFetchingLocation = false;
            userLatitude = pos.coords.latitude;
            userLongitude = pos.coords.longitude;
            const accuracy = Math.round(pos.coords.accuracy);

            console.log("Location detected: Lat:", userLatitude, "Lng:", userLongitude, "Accuracy:", accuracy + "m");

            storeLatLonInAddress(userLatitude, userLongitude, true);

            if (locationButton) {
                locationButton.disabled = false;
                locationButton.innerHTML = '<i class="bi bi-geo-alt me-1"></i> Use My Current Location';
            }

            if (isUserGesture) {
                showToast("📍 Location detected successfully!", "success");
            }
        };

        const handleFallback = () => {
            console.log("High accuracy timed out or failed. Retrying with low accuracy for iOS/iPhone compatibility...");
            navigator.geolocation.getCurrentPosition(
                handleSuccess,
                (fallbackErr) => {
                    isFetchingLocation = false;
                    if (locationButton) {
                        locationButton.disabled = false;
                        locationButton.innerHTML = '<i class="bi bi-geo-alt me-1"></i> Use My Current Location';
                    }
                    handleLocationError(fallbackErr, isUserGesture);
                },
                {
                    enableHighAccuracy: false,
                    timeout: 15000,
                    maximumAge: 300000 // Accept location cached in the last 5 minutes
                }
            );
        };

        // First attempt: High accuracy with generous timeout (12 seconds) for GPS acquisition on iOS
        navigator.geolocation.getCurrentPosition(
            handleSuccess,
            (err) => {
                // Error codes: 1 = PERMISSION_DENIED, 2 = POSITION_UNAVAILABLE, 3 = TIMEOUT
                if (err.code === 3 || err.code === 2) {
                    handleFallback();
                } else {
                    isFetchingLocation = false;
                    if (locationButton) {
                        locationButton.disabled = false;
                        locationButton.innerHTML = '<i class="bi bi-geo-alt me-1"></i> Use My Current Location';
                    }
                    handleLocationError(err, isUserGesture);
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 60000 // Accept location cached in the last 60 seconds
            }
        );
    }

    function handleLocationError(err, isUserGesture) {
        console.warn("Geolocation error:", err.code, err.message);
        const addressField = document.getElementById('customerAddress');
        if (addressField) {
            addressField.placeholder = "Click 'Use My Current Location' or enter your address";
            addressField.removeAttribute('readonly');
        }

        if (isUserGesture) {
            if (err.code === 1) { // PERMISSION_DENIED
                showToast("Location access denied. Please allow location access in your iOS / Safari settings.", "error");
            } else if (err.code === 3) { // TIMEOUT
                showToast("Location request timed out. Please tap 'Use My Current Location' again.", "error");
            } else {
                showToast("Unable to fetch location. Please enter address manually.", "error");
            }
        }
    }

    /*
    function old_storeLatLonInAddress(lat, lon, force = false) {
        userLatitude = lat;
        userLongitude = lon;
        const addressField = document.getElementById('customerAddress');
       
        if (addressField) {
            if (force || !addressField.value.trim() || addressField.value.startsWith('Lat:')) {
                const locationString = `Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}`;
                addressField.value = locationString;
                addressField.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }
    */

    function storeLatLonInAddress(lat, lon, force = true) {
        userLatitude = lat;
        userLongitude = lon;
        const locationString = `Lat: ${lat.toFixed(6)}, Lon: ${lon.toFixed(6)}`;
        const addressField = document.getElementById('customerAddress');
        if (addressField) {
            addressField.value = locationString;
            addressField.textContent = locationString;
            addressField.placeholder = locationString;
            addressField.dispatchEvent(new Event('input', { bubbles: true }));
            addressField.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    function detectMyLocation() {
        const addressField = document.getElementById('customerAddress');
        if (addressField) {
            addressField.placeholder = "Detecting your location...";
        }
        if (userLatitude && userLongitude) {
            storeLatLonInAddress(userLatitude, userLongitude, true);
        }
        getUserLocation(true);
    }

    async function fillCustomerData() {
        try {
            const response = await fetch('/customer/data', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.customer) {
                    const nameInput = document.getElementById('customerName');
                    const addressInput = document.getElementById('customerAddress');
                    const door_noInput = document.getElementById('door_no');
                    const areaInput = document.getElementById('area');

                    if (data.customer.name?.trim()) nameInput.value = data.customer.name;
                    if (data.customer.door_no?.trim()) door_noInput.value = data.customer.door_no;
                    if (data.customer.area?.trim()) areaInput.value = data.customer.area;
                   
                    if (userLatitude && userLongitude) {
                        storeLatLonInAddress(userLatitude, userLongitude, true);
                    } else if (data.customer.address?.trim()) {
                        addressInput.value = data.customer.address;
                    } else if (userLatitude && userLongitude) {
                        addressInput.value = `Lat: ${userLatitude.toFixed(6)}, Lon: ${userLongitude.toFixed(6)}`;
                    }
                } else if (userLatitude && userLongitude) {
                    const addressInput = document.getElementById('customerAddress');
                    if (addressInput && !addressInput.value.trim()) {
                        addressInput.value = `Lat: ${userLatitude.toFixed(6)}, Lon: ${userLongitude.toFixed(6)}`;
                    }
                }
            }
        } catch (error) {
            console.error('Error fetching customer data:', error);
        }
        if (userLatitude && userLongitude) {
            storeLatLonInAddress(userLatitude, userLongitude, true);
        }
    }

    function showLocationOnMap() {
        if (!userLatitude || !userLongitude) {
            alert("Location not available. Please enable location services.");
            return;
        }
        window.open(`https://www.google.com/maps?q=${userLatitude},${userLongitude}`, '_blank');
    }

    function addLocationButtonToCheckout() {
        if (document.getElementById('locationButton')) return;
       
        const addressField = document.getElementById('customerAddress');
        if (!addressField) return;
       
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'mb-2 d-flex flex-wrap gap-2';
       
        const locationButton = document.createElement('button');
        locationButton.id = 'locationButton';
        locationButton.type = 'button';
        locationButton.className = 'btn btn-outline-primary btn-sm';
        locationButton.innerHTML = '<i class="bi bi-geo-alt me-1"></i> Use My Current Location';
        locationButton.onclick = detectMyLocation;
       
        const mapButton = document.createElement('button');
        mapButton.type = 'button';
        mapButton.className = 'btn btn-outline-secondary btn-sm';
        mapButton.innerHTML = '<i class="bi bi-map me-1"></i> View on Map';
        mapButton.onclick = showLocationOnMap;
       
        buttonContainer.appendChild(locationButton);
        buttonContainer.appendChild(mapButton);
        addressField.parentNode.insertBefore(buttonContainer, addressField);
    }

    // =============================================
    // CHECKOUT FUNCTIONS
    // =============================================
    async function checkout() {
        if (isCheckingOut || cart.length === 0) return;
        isCheckingOut = true;
       
        const checkoutBtn = document.getElementById('checkoutBtn');
        const originalText = checkoutBtn.innerHTML;
        checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        checkoutBtn.disabled = true;
       
        try {
            // Reset form and fill customer data
            // Close cart sidebar and overlay so ONLY the checkout popup is shown
            const cartSidebar = document.getElementById('cartSidebar');
            const overlay = document.getElementById('overlay');
            if (cartSidebar) cartSidebar.classList.remove('show');
            if (overlay) overlay.classList.remove('show');
            document.body.style.overflow = '';

            // Reset form
            const form = document.getElementById('checkoutForm');
            // form.reset() avoided so detected coordinates are not wiped

            // Update total in checkout modal
            const modalTotal = document.getElementById('modalTotal');
            if (modalTotal && window.currentCartData) {
                modalTotal.textContent = window.currentCartData.grandTotal.toFixed(2);
            }
            try {
                await fillCustomerData();
            } catch (fillErr) {
                console.warn('Customer data fill warning:', fillErr);
            }
           
            // Apply detected location & location buttons before showing modal
            addLocationButtonToCheckout();
            if (userLatitude && userLongitude) {
                storeLatLonInAddress(userLatitude, userLongitude, true);
            }

            // Show checkout modal
            const checkoutModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('checkoutModal'));
            checkoutModal.show();
        } catch (error) {
            console.error('Checkout error:', error);
            showToast('Error preparing checkout', 'error');
        } finally {
            checkoutBtn.innerHTML = originalText;
            checkoutBtn.disabled = false;
            isCheckingOut = false;
        }
    }

    function confirmOrder() {
        const name = document.getElementById('customerName').value.trim();
        const door_no = document.getElementById('door_no').value.trim();
        const area = document.getElementById('area').value.trim();
        const address = document.getElementById('customerAddress').value.trim();
        const paymentType = document.querySelector('input[name="paymentType"]:checked')?.value;
        const orderNotes = document.getElementById('orderNotes').value.trim();
        const errorDiv = document.getElementById('checkoutError');
       
        errorDiv.classList.add('d-none');
        errorDiv.textContent = '';

        // Validation
        if (!name) return showError('Please enter your name');
        if (!door_no) return showError('Please enter door/flat number');
        if (!area) return showError('Please enter area/locality');
        if (!address) return showError('Please enter your delivery address');
        if (!paymentType) return showError('Please select a payment method');
       
        const orderData = {
            items: cart.map(item => ({ 
                food_id: item.id, 
                quantity: item.quantity, 
                price: item.price,
                name: item.name 
            })),
            customer_name: name,
            customer_address: address,
            door_no: door_no,
            area: area,
            payment_type: paymentType,
            subtotal: window.currentCartData.subtotal,
            total_amount: window.currentCartData.grandTotal,
            charges: window.currentCartData.charges,
            order_notes: orderNotes,
            latitude: (function() {
                if (userLatitude) return userLatitude;
                const m = address.match(/Lat:\s*([0-9.-]+)/i);
                return m ? parseFloat(m[1]) : null;
            })(),
            longitude: (function() {
                if (userLongitude) return userLongitude;
                const m = address.match(/Lon:\s*([0-9.-]+)/i);
                return m ? parseFloat(m[1]) : null;
            })(),
            location_id: currentLocationId
        };

        console.log('Submitting order:', orderData);

        const confirmBtn = document.getElementById('confirmOrderBtn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            console.log('Order successful:', data);
            
            window.orderJustPlaced = true;
            
            // Close checkout modal
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
           
            // Show thank you modal
            const thankYouModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('thankYouModal'));
            thankYouModal.show();
           
            // Clear cart
            cart = [];
            localStorage.removeItem('madrasCart');
            updateCartDisplay();
        })
        .catch(error => {
            console.error('Order error:', error);
            const errorMessage = error['validation-error-message'] || error.message || 'An error occurred while placing your order. Please try again.';
            showError(errorMessage);
        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    }

    function showError(message) {
        const errorDiv = document.getElementById('checkoutError');
        errorDiv.textContent = message;
        errorDiv.classList.remove('d-none');
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // =============================================
    // UI HELPER FUNCTIONS
    // =============================================
    function showToast(message, type = 'success') {
        const toast = document.getElementById('cartToast');
        const toastMessage = document.getElementById('cartToastMessage');
        const icon = toast.querySelector('.cart-toast-icon');
       
        if (!toast || !toastMessage) return;
       
        // Set message and icon
        toastMessage.textContent = message;
        
        if (type === 'error') {
            icon.className = 'bi bi-exclamation-triangle cart-toast-icon';
            toast.style.backgroundColor = '#dc3545';
        } else if (type === 'info') {
            icon.className = 'bi bi-info-circle cart-toast-icon';
            toast.style.backgroundColor = '#17a2b8';
        } else {
            icon.className = 'bi bi-check-circle cart-toast-icon';
            toast.style.backgroundColor = 'var(--primary-red)';
        }
       
        // Show toast
        toast.classList.add('show');
       
        // Auto hide
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function toggleCart() {
        const cartSidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('overlay');
        
        if (!cartSidebar || !overlay) {
            console.error('Cart elements not found');
            return;
        }

        const isOpening = !cartSidebar.classList.contains('show');
       
        if (isOpening) {
            cartSidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        } else {
            cartSidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    function openImageModal(src, name, description, price) {
        const modal = document.getElementById('imageModal');
        document.getElementById('modalImage').src = src;
        document.getElementById('modalItemName').innerHTML = name;
        document.getElementById('modalItemDescription').innerHTML = description || 'Delicious ' + name;
        document.getElementById('modalItemPrice').innerHTML = locationCurrency + ' ' + parseFloat(price).toFixed(2);
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Remove the duplicate showCategory function that was here previously

    function toggleSearch() {
        const searchBar = document.getElementById('searchBar');
        searchBar.classList.toggle('d-none');
        if (!searchBar.classList.contains('d-none')) {
            setTimeout(() => document.getElementById('searchInput').focus(), 100);
        }
    }

    // =============================================
    // FIREBASE CONFIGURATION (unchanged)
    // =============================================
    const firebaseConfig = {
        apiKey: "AIzaSyCCj-N6MdMKaFAzxb02G2MmGm9BeKNeKCU",
        authDomain: "laravelordernotify.firebaseapp.com",
        projectId: "laravelordernotify",
        storageBucket: "laravelordernotify.firebasestorage.app",
        messagingSenderId: "868388646146",
        appId: "1:868388646146:web:856d59ae681ea3b821661b",
        measurementId: "G-2N94G3NPC0"
    };

    if (firebase.apps.length === 0) {
        firebase.initializeApp(firebaseConfig);
    }
    const messaging = firebase.messaging();

    // Request notification permission
    if ('Notification' in window) {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                messaging.getToken({
                    vapidKey: 'BPww83cVnQiz5Y3ziip7Fh4kXgkVnHYo5xYfVzvpEQBHJdQUfLCwnPHzd47yFfIf1_FZnFKF5UgdSnJYTGJD3Yo'
                }).then(token => {
                    fetch('{{ route("save.fcm.token") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ token })
                    })
                    .then(res => res.json())
                    .then(data => console.log('Token saved:', data))
                    .catch(err => console.error(err));

                }).catch(err => console.error('Error getting token:', err));
            }
        });
    }

    // Make functions globally available
    window.addToCart = addToCart;
    window.incrementItem = incrementItem;
    window.decrementItem = decrementItem;
    window.removeItem = removeItem;
    window.checkout = checkout;
    window.confirmOrder = confirmOrder;
    window.toggleCart = toggleCart;
    window.showCategory = showCategory;
    window.performLiveSearch = performLiveSearch;
    window.toggleSearch = toggleSearch;
    window.openImageModal = openImageModal;
    window.closeImageModal = closeImageModal;
    window.detectMyLocation = detectMyLocation;
    window.showLocationOnMap = showLocationOnMap;
    </script>
</body>
</html>