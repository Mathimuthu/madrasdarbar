@extends('company.admin.main')
@section('title', 'Add Location')
@section('content')

<div class="food-menu-create">
    <section>
        <main>

            @if (session('success-message'))
                <div class="success-message">
                    <i class='bx bxs-check-circle'></i>
                    <div class="text">
                        <span>Success</span>
                        <span class="message">{!! session('success-message') !!}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message left-red" style="margin-bottom:15px;">
                    <i class='bx bxs-x-circle'></i>
                    <div class="text">
                        <span>Validation Error</span>
                        @foreach ($errors->all() as $error)
                            <span class="message">{{ $error }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="header">
                <div class="left">
                    <h1>Add Location</h1>
                </div>
                <div class="right">
                    <a href="{{ route('admin-location') }}" class="back-btn">
                        <i class='bx bx-arrow-back'></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>

            <div class="top-section">
                <form action="{{ route('admin-location.store') }}" method="POST" id="locationForm">
                    @csrf

                    <div class="form-add-menu">

                        <div class="header">
                            <h4>Location Details</h4>
                        </div>

                        <!-- Location Name -->
                        <div class="form-group">
                            <label class="star">Location Name</label>
                            <input type="text"
                                   name="location_name"
                                   placeholder="e.g. Bangkok, Phuket, Colombo"
                                   value="{{ old('location_name') }}"
                                   required>
                            @error('location_name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text"
                                   name="country"
                                   placeholder="e.g. Thailand, Sri Lanka"
                                   value="{{ old('country') }}">
                            @error('country')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div class="form-group">
                            <label>Currency Code</label>
                            <input type="text"
                                   name="currency"
                                   placeholder="e.g. THB, LKR, USD"
                                   maxlength="10"
                                   value="{{ old('currency') }}">
                            @error('currency')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="button">
                        <input type="submit" value="Save Location">
                        <a href="{{ route('admin-location') }}"><span>Cancel</span></a>
                    </div>

                </form>
            </div>

        </main>
    </section>
</div>

<style>
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

    .field-error {
        display: block;
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-add-menu {
        position: relative;
        overflow: visible !important;
        padding: 20px;
    }

    .top-section {
        overflow: visible !important;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #2563eb;
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

    .back-btn i { font-size: 18px; }
    .back-btn:hover { background: #1d4ed8; }

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

    .button input[type="submit"]:hover { background: #2563eb; }

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

    .button a:hover { background: #dc2626; }
</style>

@endsection
