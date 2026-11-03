<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>@yield('title', 'Admin')</title>
</head>
<!-- Tailwind CSS CDN (v3.4+) - Play CDN for quick testing/production -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Boxicons CDN (for your <i class='bx ...'></i> icons) -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

<!-- Optional: Google Fonts (if you want nicer typography) -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Optional: Custom Tailwind config via CDN (recommended for consistency) -->
<script>
  tailwind.config = {
    darkMode: 'class', // enables dark mode support
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'sans-serif'],
        },
      },
    },
  }
</script>
<style>
    /* Tables on the redesigned pages use Tailwind, so undo the old global table styles for them */
    table.ui-table th { background: transparent; color: inherit; font-size: inherit; border-bottom: 0; }
    table.ui-table td { font-size: inherit; }
    table.ui-table tbody tr:nth-child(even) { background-color: transparent; }
</style>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        
<a href="{{ route('admin-dashboard') }}" class="logo">Madras darbar</a>    
    <ul class="side-menu">
            <li class="{{ request()->routeIs(['admin-dashboard', 'login']) ? 'active' : '' }}">
                <a href="{{ route('admin-dashboard') }}"><i class='bx bxs-dashboard'></i>Dashboard</a>
            </li>
            <li class="{{ request()->routeIs(['staff-account', 'staff-account-create', 'staff-account-edit', 'staff-account-show', 'staff-account-search-index', 'staff-account-search-create']) ? 'active' : '' }}">
                <a href="{{ route('staff-account') }}"><i class='bx bxs-user-rectangle'></i>Staff Accounts</a>
            </li>
            <li class="{{ request()->routeIs(['food-menu', 'food-menu-create', 'food-menu-edit', 'food-menu-show', 'food-menu-search-index', 'food-menu-search-create']) ? 'active' : '' }}">
                <a href="{{ route('food-menu') }}"><i class='bx bxs-food-menu'></i>Food Menus</a>
            </li>
            <li class="{{ request()->routeIs(['food-location', 'food-location-create', 'food-location-edit', 'food-location-show']) ? 'active' : '' }}">
                <a href="{{ route('food-location') }}"><i class='bx bxs-map-pin'></i>Food Location</a>
            </li>
            {{-- <li class="{{ request()->routeIs(['restaurant', 'restaurant-create', 'restaurant-show', 'restaurant-edit']) ? 'active' : '' }}">
                <a href="{{ route('restaurant') }}"><i class='bx bxs-store'></i>Restaurant</a>
            </li>
            <li class="{{ request()->routeIs(['partnership', 'partnership-create', 'partnership-edit']) ? 'active' : '' }}">
                <a href="{{ route('partnership') }}"><i class='bx bxs-group'></i>Partnerships</a> --}}
            </li>
            <!-- Change this line -->
            <li class="{{ request()->routeIs(['charges', 'charges.create', 'charges.edit']) ? 'active' : '' }}">
                <a href="{{ route('charges.index') }}"><i class='bx bxs-offer'></i>Charges</a>
            </li>
            {{-- <li class="{{ request()->routeIs(['promotion-discount', 'promotion-discount-create', 'promotion-discount-show', 'promotion-discount-edit']) ? 'active' : '' }}">
                <a href="{{ route('promotion-discount') }}"><i class='bx bxs-offer'></i>Promotions & Discounts</a>
            </li> --}}
            <!--<li class="{{ request()->routeIs('banners.*') ? 'active' : '' }}">-->
            <!--    <a href="{{ route('banners.index') }}">-->
            <!--        <i class='bx bxs-image-alt'></i> Advert Banners-->
            <!--    </a>-->
            <!--</li>-->
            <li class="{{ request()->routeIs('delivery-management') ? 'active' : '' }}">
                <a href="{{ route('delivery-management') }}"><i class='bx bxs-truck'></i>Delivery Management</a>
            </li>
            {{-- <li>
                <a href="#"><i class='bx bxs-receipt'></i>Billing & Invoices</a>
            </li>
            <li>
                <a href="#"><i class='bx bx-analyse'></i>Analytics</a>
            </li> --}}
        </ul>

        <ul class="side-menu">
            {{-- <li>
                <a href="#"><i class='bx bx-shield-quarter'></i>Access Control</a>
            </li> --}}
            <li class="{{ request()->routeIs('admin-profile') ? 'active' : ''}}">
                <a href="{{ route('admin-profile') }}"><i class='bx bx-cog'></i>Settings</a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST" id="logout-form">

                    @csrf

                    <button type="button" id="logout-button">
                        <i class='bx bx-log-out-circle'></i>
                        Logout
                    </button>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                </form>
            </li>
        </ul>

    </div>
    <!-- End of Sidebar -->

    <div class="top-bar">
        <nav>
            <i class='bx bx-menu'></i>
            <div class="right-side">
                @include('company.partials.order-notifications')
                <input type="checkbox" id="theme-toggle" hidden><label for="theme-toggle" class="theme-toggle"></label>
                <a href="#" class="profile"><i class='bx bx-user-circle'></i></a>
            </div>
        </nav>
    </div>

    @yield('content')

    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/vendor/jqury/jqury_v3_7_1.min.js') }}"></script>

    @yield('scripts')

</body>

</html>
