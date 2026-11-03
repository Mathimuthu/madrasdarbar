<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Location;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 1) {
                return redirect()->route('admin-dashboard')->with('success-message', 'Login Successful.');
            }

            if (Auth::user()->role == 2) {
                return redirect()->route('customer-order')->with('success-message', 'Login Successful.');
            }
            
            if (Auth::user()->role == 3) {
                return redirect()->route('delivery-order')->with('success-message', 'Login Successful.');
            }
        }
        else {
            return view('company.auth.login');
        }
    }
    
    /**
     * Handle an authentication attempt
     */
    public function authenticate(Request $request) : RedirectResponse
    {
        $credentials = $request->validate([
            'staff_id' => ['required'],
            'password' => ['required'],
        ]);
        
        $user = User::where('staff_id', $credentials['staff_id'])->first();
        if (!$user) {
            return back()->withErrors([
                'error-message' => 'Staff ID is incorrect.',
            ])->onlyInput('staff_id');
        }
        
        if (($user->role == 2) && (empty($user->location_id))) {
                return back()->withErrors([
                    'error-message' => 'Location is not allocated to you, please contact your manager.'
                ])->onlyInput('staff_id');
            }

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role == 1) {
                $request->session()->regenerate();
                return redirect()->route('admin-dashboard')->with('success-message', 'Login Successful.');
            }
            
            if (Auth::user()->role == 2) {
                $request->session()->regenerate();
                return redirect()->route('customer-order')->with('success-message', 'Login Successful.');
            }
            if (Auth::user()->role == 3) {
                $request->session()->regenerate();
                return redirect()->route('delivery-order')->with('success-message', 'Login Successful.');
            }
        }

        return back()->withErrors([
            'error-message' => 'The provided credentials do not match our records.'
        ])->onlyInput('staff_id');
    }

    public function logout(Request $request) : RedirectResponse
    {
        $request->session()->forget('location_id');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
