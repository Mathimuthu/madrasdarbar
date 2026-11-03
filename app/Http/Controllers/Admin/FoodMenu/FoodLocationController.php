<?php

namespace App\Http\Controllers\Admin\FoodMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodLocation;
use App\Models\FoodCategory;
use App\Models\FoodMenu;
use App\Models\Location;
use Illuminate\View\View;

class FoodLocationController extends Controller
{

    public function index() : View
    {
        $food = FoodLocation::paginate(10);

        return view('company.admin.food-location.index', ['food' => $food]);
    }

    /*
    *  Funtion to view create file
    */
    public function create() : View
    {
        $foodMenu = FoodMenu::get();
        $locations = Location::get();

        return view('company.admin.food-location.create', ['foodMenu' => $foodMenu, 'locations' => $locations]);
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $request->validate([
        'food_id' => 'required|exists:food_menus,id',
        'location_id' => 'required|exists:location,location_id',
        'price' => 'required|numeric',
    ]);

    // Check if combination already exists
    $exists = FoodLocation::where('food_id', $request->food_id)
                ->where('location_id', $request->location_id)
                ->exists();

    if ($exists) {
        // Redirect back with duplicate warning message
        return redirect()
            ->back()
            ->with('warning-message', 'This food and location combination already exists.')
            ->withInput();
    }

    // Create new record
    FoodLocation::create([
        'food_id' => $request->food_id,
        'location_id' => $request->location_id,
        'price' => $request->price,
    ]);

   return redirect()->back()->with('success-message', 'Food Location created successfully.');

}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    public function search_index(Request $request)
    {
        $search = $request->input('search');
        $foodLocations = FoodLocation::with(['foodMenu', 'location'])
            ->whereHas('foodMenu', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('location', function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('company.admin.food-location.index', ['foodLocations' => $foodLocations]);
    }

    public function search_create(Request $request)
    {
        $search = $request->input('search');
        $FoodMenu = FoodMenu::where('name', 'like', '%' . $search . '%')->get();
        $Location = Location::where('location_name', 'like', '%' . $search . '%')->get();

        return view('company.admin.food-location.create', ['FoodMenu' => $FoodMenu, 'Location' => $Location]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
