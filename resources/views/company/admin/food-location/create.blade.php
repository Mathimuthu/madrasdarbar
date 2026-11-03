@extends('company.admin.main')

@section('title', 'Create')

@section('content')

    <div class="food-menu-create">

        <section>

            <main>

@if (session('success-message'))
    <div class="success-message">
        <i class='bx bxs-check-circle'></i>
        <div class="text">
            <span>Success</span>
            <span class="message">{{ session('success-message') }}</span>
        </div>
    </div>
@endif
                <div class="header">
                    <div class="left">
                        <h1>Create Food Location</h1>
                    </div>
                    <div class="right">
                        <a href="{{ route('food-location') }}" class="back-btn">
                            <i class='bx bx-arrow-back'></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>

                <div class="top-section">

                    <form action="{{ route('food-location.store') }}" method="POST" enctype="multipart/form-data" id="imageForm">
                        @csrf

                        <div class="form-add-menu">

                            <div class="header">
                                <h4>Food Location Details</h4>
                            </div>

                            <div class="form-group">
                                <label class="star">Food name</label>
                                <div class="dropdown food-dropdown">
                                    <div class="select">
                                        <span class="selected">Select Menu</span>
                                        <div class="caret"><i class='bx bx-chevron-down'></i></div>
                                    </div>
                                    <ul class="menu">
                                        @foreach ($foodMenu as $menu)
                                            <li data-value="{{ $menu->id }}">{{ $menu->name }}</li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="food_id" value="" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="star">Location</label>
                                <div class="dropdown location-dropdown">
                                    <div class="select">
                                        <span class="selected">Select Location</span>
                                        <div class="caret"><i class='bx bx-chevron-down'></i></div>
                                    </div>
                                    <ul class="menu">
                                        @foreach ($locations as $location)
                                            <li data-value="{{ $location->location_id }}">{{ $location->location_name }}</li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="location_id" value="" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="star">Price</label>
                                <input type="text" name="price" placeholder="Enter food price" value="{{ old('price') }}" required>
                            </div>

                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
                        <div class="button">
                            <input type="submit" value="Add Food Location">
                            <a href="{{ route('food-location') }}"><span>Cancel</span></a>
                        </div>
                    </form>
                </div>

            </main>

        </section>

    </div>

    <style>
        /* Form Group Styling */
        .form-group {
            margin-bottom: 20px;
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 14px;
        }

        .form-group label.star::after {
            content: " *";
            color: red;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Ensure parent containers don't hide dropdown */
        .form-add-menu {
            position: relative;
            overflow: visible !important;
            padding: 20px;
        }

        .top-section {
            overflow: visible !important;
        }

        /* Dropdown Container */
        .dropdown {
            position: relative;
            width: 100%;
            z-index: 10;
        }

        /* Dropdown Select Box */
        .dropdown .select {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            user-select: none;
            min-height: 42px;
            box-sizing: border-box;
        }

        .dropdown .select:hover {
            border-color: #999;
        }

        .dropdown .select-clicked {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Selected Text */
        .dropdown .select .selected {
            color: #333;
            flex: 1;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
/* Header layout */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

/* Back Button (top-right corner) */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #2563eb; /* Primary blue */
    color: #fff;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.25s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.back-btn i {
    font-size: 18px;
}

.back-btn:hover {
    background: #1d4ed8; /* Darker blue hover */
    box-shadow: 0 3px 6px rgba(37,99,235,0.3);
}

        .dropdown .select .selected:empty::before {
            content: 'Select an option';
            color: #999;
        }

        /* Caret Icon */
        .dropdown .caret {
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            margin-left: 10px;
            flex-shrink: 0;
        }

        .dropdown .caret i {
            font-size: 20px;
            color: #666;
        }

        .dropdown .caret-rotate {
            transform: rotate(180deg);
        }

        /* Dropdown Menu */
        .dropdown .menu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, border 0.3s ease;
            z-index: 999;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .dropdown .menu-open {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #3b82f6;
        }

        /* Menu Items */
        .dropdown .menu li {
            padding: 12px 15px;
            cursor: pointer;
            transition: background 0.2s ease;
            color: #333;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown .menu li:last-child {
            border-bottom: none;
        }

        .dropdown .menu li:hover {
            background: #f3f4f6;
        }

        .dropdown .menu li.active {
            background: #3b82f6;
            color: #fff;
        }

        /* Scrollbar Styling */
        .dropdown .menu::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown .menu::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .dropdown .menu::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .dropdown .menu::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Specific dropdown z-index management */
        .food-dropdown.active {
            z-index: 1000;
        }

        .location-dropdown.active {
            z-index: 999;
        }

        /* Button Styling */
        .button {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .button input[type="submit"] {
            padding: 12px 30px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .button input[type="submit"]:hover {
            background: #2563eb;
        }

        .button a {
            padding: 12px 30px;
            background: #ef4444;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.3s ease;
            display: inline-block;
        }

        .button a:hover {
            background: #dc2626;
        }
        .warning-message {
            display: flex;
            align-items: center;
            background-color: #fef3c7;
            border-left: 5px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 15px;
            animation: fadeIn 0.3s ease-in-out;
        }
        .warning-message i {
            color: #b45309;
            font-size: 20px;
            margin-right: 10px;
        }
        .warning-message .text {
            color: #92400e;
            font-size: 14px;
        }

    </style>

@endsection