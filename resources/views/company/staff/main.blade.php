<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/staff-style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <title>@yield('title', 'Staff')</title>
</head>
<style>
    /* Prevent Bootstrap from overriding sidebar */
    .sidebar *, 
    .topbar * {
        box-sizing: border-box;
        font-family: inherit;
        margin: 0;
        padding: 0;
    }

    /* Prevent Bootstrap nav styles */
    .sidebar ul {
        list-style: none !important;
        padding-left: 0 !important;
        margin: 0 !important;
    }

    .sidebar a {
        text-decoration: none !important;
    }

    /* Prevent Bootstrap button styles */
    .sidebar button,
    .topbar button {
        all: unset;
        cursor: pointer;
    }

    /* Prevent image resizing from Bootstrap */
    .sidebar img,
    .topbar img {
        max-width: none !important;
        height: auto;
    }
    .sidebar .content li.active a {
    color: #d1cbd6;
}
</style>
<body>

    <!-- Sidebar -->
    <div class="sidebar">

        <div class="content">
            <ul>
                <li>
                    <h3><a href="#" class="">
                        Madrasdarbar
                        {{-- <span>Hash Restaurant</span> --}}
                    </a></h3>
                </li>
                <!--<li class="{{ request()->routeIs(['staff-dashboard', 'login']) ? 'active' : '' }}">-->
                <!--    <a href="{{ route('staff-dashboard') }}">-->
                <!--        <i class='bx bxs-dashboard'></i>-->
                <!--        <span>Dashboard</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li class="{{ request()->routeIs(['customer-order', 'customer-order-create']) ? 'active' : '' }}">
                    <a href="{{ route('customer-order') }}">
                        <i class='bx bxs-spreadsheet'></i>
                        <span>Orders</span>
                    </a>
                </li>
                {{-- <li class="{{ request()->routeIs(['customer-reservation']) ? 'active' : '' }}">
                    <a href="{{ route('customer-reservation') }}">
                        <i class='bx bxs-book'></i>
                        <span>Reservation</span>
                    </a>
                </li> --}}
                {{-- <li>
                    <a href="#">
                        <i class='bx bxs-message-alt-error'></i>
                        <span>Complaint</span>
                    </a>
                </li> --}}
            </ul>
            <ul>
                <li class="logout">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">

                        @csrf

                        <button type="button" id="logout-button">
                            <i class='bx bx-log-out-circle'></i>
                            <span>Logout</span>
                        </button>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    </form>
                </li>
            </ul>
        </div>

    </div>

    <div class="topbar">

        <div class="content">

            <div class="menu-button">
                <i class='bx bx-menu'></i>
            </div>

            @include('company.partials.order-notifications')

            <div class="user">
                @if (Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" alt="User-Photo" class="user-profile" id="profile-menu">
                @else
                    <i class='bx bxs-user-circle' id="profile-menu"></i>
                @endif
                <ul class="toggle-profile">
                    <li><a href="{{ route('staff-profile-show', Auth::user()->id) }}"><i class='bx bxs-user-detail'></i><span>Update Profile</span></a></li>
                    <li><a href="#"><i class='bx bxs-key'></i><span>Change Password</span></a></li>
                    <li class="logout">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">

                            @csrf

                            <button type="button" id="logout-button-topbar">
                                <i class='bx bx-log-out-circle'></i>
                                <span>Logout</span>
                            </button>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/staff.js') }}"></script>

</body>

</html>
