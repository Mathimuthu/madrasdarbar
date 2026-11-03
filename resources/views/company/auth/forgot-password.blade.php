@extends('main')

@section('title', 'Forgot Password')

@php
    // This will hide the header and footer
    $hideLayout = true;
@endphp

@section('content')
<style>
    body {
        background: url('{{ asset('images/frontpageW (1).jpg') }}') center/cover no-repeat fixed !important;
    }
</style>
    <div class="forgot-password">

        <div class="container">

            <div class="title">
                Reset Password
            </div>

            <div class="text">
                Please Contact the admin to reset the Password.
            </div>
        </div>
    </div>

@endsection