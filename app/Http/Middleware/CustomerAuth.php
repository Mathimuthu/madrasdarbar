<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if location is selected (required for all customers)
        if (!session()->has('location_id')) {
            return redirect()->route('public.login')->with('error', 'Please select a location first.');
        }

        // Check if customer is logged in (only for protected routes)
        // You can make this conditional based on route or remove it
        if (!session()->has('customer_id') && $this->requiresLogin($request)) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        return $next($request);
    }

    protected function requiresLogin(Request $request)
    {
        // Define routes that require customer login
        $protectedRoutes = [
            'cart.add',
            'cart.update',
            'create-order',
            'create-reservation',
            'orders.history',
            'customer.logout'
        ];

        return in_array($request->route()->getName(), $protectedRoutes);
    }
}