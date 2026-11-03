<?php

namespace App\Http\Controllers\Api;

use App\Models\Charges;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ChargesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Charges::where('status', 1);
            
            // Filter by location if provided
            if ($request->has('location_id')) {
                $query->where('location_id', $request->location_id);
            }
            
            $charges = $query->get();
            
            return response()->json([
                'success' => true,
                'charges' => $charges,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load charges: ' . $e->getMessage(),
            ], 500);
        }
    }
}
