@extends('main')

@section('title', 'Staff Login')

@php
    // This will hide the header and footer
    $hideLayout = true;
@endphp

@section('styles')
<style>
    body {
        background: url('{{ asset('images/frontpageW (1).jpg') }}') center/cover no-repeat fixed !important;
    }

    /* Mobile Styles */
    @media screen and (max-width: 768px) {
        body {
            background-size: cover;
            background-position: center;
        }

        .login {
            padding: 20px 15px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login .container {
            width: 100%;
            max-width: 400px;
            padding: 30px 20px;
            margin: 0 auto;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login .title {
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .login-field {
            margin-bottom: 20px;
        }

        .login-field .details {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
        }

        .login-field input[type="text"],
        .login-field input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .login-field input[type="text"]:focus,
        .login-field input[type="password"]:focus {
            border-color: #007bff;
        }

        .remember {
            margin-bottom: 20px;
        }

        .remember label {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            margin-right: 8px;
            cursor: pointer;
        }

        .login-button {
            margin-bottom: 20px;
        }

        .login-button input[type="submit"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-button input[type="submit"]:active {
            background: #0056b3;
        }

        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .register a,
        .forgot-link a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .register a:active,
        .forgot-link a:active {
            color: #0056b3;
        }

        .divider {
            margin: 0 10px;
            color: #999;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
    }

    /* Small mobile devices */
    @media screen and (max-width: 480px) {
        .login .container {
            padding: 25px 15px;
        }

        .login .title {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .flex {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }

        .divider {
            display: none;
        }
    }
</style>
@endsection


@section('content')

    <div class="login">

        @error('error-message')
            <div class="error-message">{{ $message }}</div>
        @enderror

        <div class="container">

            <div class="title">
                Staff Login
            </div>

            <form action="{{ route('login') }}" method="POST">

                @csrf

                <div class="login-field">
                    <span class="details">Staff ID</span>
                    <input type="text" name="staff_id" placeholder="Enter your staff id" value="{{ old('staff_id') }}" required>
                </div>

                <div class="login-field">
                    <span class="details">Password</span>
                    <input type="password" name="password" placeholder="Enter your password" value="{{ old('password') }}" required>
                </div>

                <div class="remember">
                    <label>
                        <input type="checkbox">Remember me
                    </label>
                </div>

                <div class="login-button">
                    <input type="submit" value="Sign in">
                </div>

                <div class="flex">
                    <div class="register">
                        <a href="{{ route('register') }}">Register Account</a>
                    </div>

                    <div class="divider">|</div>

                    <div class="forgot-link">
                        <a href="{{ route('forgot-password') }}">Forgot Password</a>
                    </div>

                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
            </form>

        </div>

    </div>

@endsection