<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required',
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)
                    ->where('role', 3) // ONLY employee
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid phone or password',
            ], 401);
        }

        $token = $user->createToken('staff-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id'          => $user->id,
                'staff_id'    => $user->staff_id,
                'name'        => $user->name,
                'phone'       => $user->phone,
                'role'        => $user->role,
                'location_id' => $user->location_id,
            ]
        ]);
    }
    public function profile(Request $request)
{
    $user = $request->user()->load('location');

    // ✅ Allow ONLY delivery boy (role = 3)
    if (!$user || $user->role != 3) {
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized access',
        ], 403);
    }

    return response()->json([
        'status'  => true,
        'message' => 'Staff profile fetched successfully',
        'data'    => [
            'id'            => $user->id,
            'staff_id'      => $user->staff_id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'gender'        => $user->gender,
            'position'      => $user->position,
            'address'       => $user->address,

            // ✅ Location data
            'location_id'   => $user->location_id,
            'location_name' => $user->location?->location_name ?? null,
            'country'       => $user->location?->country ?? null,
            'currency'      => $user->location?->currency ?? null,
        ]
    ]);
}

}
