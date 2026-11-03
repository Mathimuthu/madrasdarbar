<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminLocationController extends Controller
{
    /*
     * List all locations
     */
    public function index(): View
    {
        $locations = Location::orderBy('created_at', 'DESC')->paginate(10);

        return view('company.admin.location.index', ['locations' => $locations]);
    }

    /*
     * Show the create form
     */
    public function create(): View
    {
        return view('company.admin.location.create');
    }

    /*
     * Store a new location
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'location_name' => 'required|string|max:100|unique:location,location_name',
            'country'       => 'nullable|string|max:100',
            'currency'      => 'nullable|string|max:10',
        ], [
            'location_name.unique' => 'A location with this name already exists.',
        ]);

        $location = Location::create([
            'location_name' => $request->location_name,
            'country'       => $request->country,
            'currency'      => $request->currency,
        ]);

        Log::info('New location created', ['location_id' => $location->location_id, 'name' => $location->location_name]);

        return redirect()->route('admin-location')->with('success-message', "Location <strong>{$location->location_name}</strong> created successfully.");
    }

    /*
     * Show the edit form
     */
    public function edit($id): View
    {
        $location = Location::findOrFail($id);

        return view('company.admin.location.edit', ['location' => $location]);
    }

    /*
     * Update an existing location
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'location_name' => 'required|string|max:100|unique:location,location_name,' . $id . ',location_id',
            'country'       => 'nullable|string|max:100',
            'currency'      => 'nullable|string|max:10',
        ], [
            'location_name.unique' => 'A location with this name already exists.',
        ]);

        $location->update([
            'location_name' => $request->location_name,
            'country'       => $request->country,
            'currency'      => $request->currency,
        ]);

        Log::info('Location updated', ['location_id' => $location->location_id]);

        return redirect()->route('admin-location')->with('success-message', "Location <strong>{$location->location_name}</strong> updated successfully.");
    }

    /*
     * Delete a location
     */
    public function destroy($id): RedirectResponse
    {
        $location = Location::findOrFail($id);
        $name = $location->location_name;
        $location->delete();

        Log::info('Location deleted', ['location_id' => $id, 'name' => $name]);

        return redirect()->route('admin-location')->with('success-message', "Location <strong>{$name}</strong> deleted successfully.");
    }

    /*
     * Search locations
     */
    public function search(Request $request): View
    {
        $keyword   = $request->input('search');
        $locations = Location::where('location_name', 'like', '%' . $keyword . '%')
            ->orWhere('country', 'like', '%' . $keyword . '%')
            ->orWhere('currency', 'like', '%' . $keyword . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('company.admin.location.index', ['locations' => $locations]);
    }
}
