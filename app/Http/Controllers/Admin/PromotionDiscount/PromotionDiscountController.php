<?php

namespace App\Http\Controllers\Admin\PromotionDiscount;

use App\Enums\CouponStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Charges;
use App\Models\Location;
use App\Models\PromotionDiscount;
use App\Models\PromotionEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PromotionDiscountController extends Controller
{
    public function index() : View
    {
        $coupon = PromotionDiscount::paginate(10);

        // Send the function to view file
        $backgroundStatus = $this->getStatusStyle($coupon);

        return view('company.admin.promotion-discount.index', ['coupons' => $coupon, 'status' => $backgroundStatus]);
    }




    /*
    *  Function to view create file
    */
    public function create() : View
    {
        $event = PromotionEvent::paginate(5);

        return view('company.admin.promotion-discount.create', ['events' => $event]);
    }




    /*
    *  Function to store data into promotion_discounts table
    */
    public function store(Request $request) : RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'coupon_name' => 'required|max:255',
            'discount' => 'required|numeric|min:0.01|max:1.00',
            'category_id' => 'required|exists:promotion_events,id',
            'validity' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $couponCreate = PromotionDiscount::create([
            'coupon_code' => Str::uuid(),
            'coupon_name' => $request->coupon_name,
            'discount' => $request->discount,
            'event_id' => $request->category_id,
            'validity' => $request->validity,
            'redeem_status' => CouponStatusEnum::NotRedeemed,
        ]);

        Log::info([$couponCreate]);

        return back()->with('success-message', 'Coupon created successfully.');
    }



    /*
    *  Function to view show file
    */
    public function show($id) : View
    {
        $promotion = PromotionDiscount::findOrFail($id);

        return view('company.admin.promotion-discount.show', ['coupons' => $promotion]);
    }





    /*
    *  Function to view edit file
    */
    public function edit($id) : View
    {
        $promotion = PromotionDiscount::findOrFail($id);

        $eventid = $promotion->event_id;

        $event = PromotionEvent::findOrFail($eventid);

        Log::info([$promotion, $event]);

        return view('company.admin.promotion-discount.edit', ['promotion' => $promotion, 'event' => $event]);
    }
    
    

    public function charge_index() : View
    {
        $charges = Charges::paginate(10);

        return view('company.admin.charges.index', ['charges' => $charges]);
    }


    /*
    *  Function to view create file
    */
    public function charge_create() : View
    {
        $locations = Location::get();

        return view('company.admin.charges.create', compact('locations'));
    }


    /*
    *  Function to view show file
    */
    public function charge_show($id) : View
    {
        $charge = Charges::findOrFail($id);

        return view('company.admin.charges.show', ['charge' => $charge]);
    }


    /*
    *  Function to view edit file
    */
    public function charge_edit($id) : View
    {
        $charges = Charges::findOrFail($id);
        $locations = Location::get();

        return view('company.admin.charges.edit', ['charge' => $charges,'locations' => $locations]);
    }
    
    
    public function storeCharge(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:255',
            'amount'     => 'required',
            'type'       => 'required|in:1,2',
            'location_id' => 'required|exists:location,location_id',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        Charges::create([
            'name'       => $request->name,
            'amount'     => $request->amount,
            'type'       => $request->type,
            'location_id' => $request->location_id,
            'status'      => 1,
        ]);
    
        return back()->with('success-message', 'Charges created successfully.');
    }
    
    public function updateCharge(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:255',
            'amount'      => 'required',
            'type'       => 'required|in:1,2',
            'location_id' => 'required|exists:location,location_id',
            'status'      => 'required|in:0,1',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $charge = Charges::findOrFail($id);
    
        $charge->update([
            'name'       => $request->name,
            'amount'      => $request->amount,
            'type'       => $request->type,
            'location_id' => $request->location_id,
            'status'      => $request->status,
        ]);
    
        return redirect()->route('charges.index')
            ->with('success-message', 'Charges updated successfully.');
    }

    /*
    *  Funtion to update promotion discount resource
    */
    public function update(Request $request, $id) : RedirectResponse
    {
        $promotion = PromotionDiscount::findOrFail($id);

        if ($request->anyFilled(['name', 'discount', 'status', 'cateory_id'])) {

            $updateData = [];

            if ($request->filled('name')) {
                $updateData['name'] = $request->input('name');
            }

            if ($request->filled('discount')) {
                $updateData['discount'] = $request->input('discount');
            }

            if ($request->filled('status')) {
                $updateData['status'] = $request->input('status');
            }

            if ($request->filled('category_id')) {
                $updateData['category_id'] = $request->input('category_id');
            }

            $updated = $promotion->update($updateData);

            Log::info([$updateData, $updated]);

            return redirect()->route('promotion-discount-show', $promotion->id)->with('success-message', 'Your changes are saved successfully.');
        }
        else {
            return back()->withErrors([
                'error-message' => 'Please insert data to update coupon.',
            ]);
        }
    }





    /*
    *  Function to customize style css of redeem status field
    */
    public function getStatusStyle($status) : string
    {
        $styling = [
            'font-size: 10px', 'padding: 6px 16px', 'color: #F6F6F9', 
            'border-radius: 20px', 'font-weight: 700',
        ];

        switch ($status) {
            case CouponStatusEnum::Redeemed:
                $styling[] = 'background: #388E3C';
                break;

            case CouponStatusEnum::Expired:
                $styling[] = 'background: #D32F2F';
                break;

            case CouponStatusEnum::Cancel:
                $styling[] = 'background: #AAAAAA';
                break;
            
            case CouponStatusEnum::NotRedeemed:
            default:
                $styling[] = 'background: #AAAAAA';
                break;
        }

        return implode(';', $styling);
    }
public function bannerIndex(Request $request)
{
    $banners = Banner::query()
        ->when($request->search, fn($q) => $q->where('image', 'like', "%{$request->search}%"))
        ->latest()
        ->paginate(12); // ← THIS MUST BE paginate(), NOT get() !

    return view('company.admin.advert-banner.index', compact('banners'));
}

public function bannerCreate()
{
    return view('company.admin.advert-banner.create');
}

public function bannerStore(Request $request): RedirectResponse
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048', // max 2MB
    ]);

    $file = $request->file('image');

    // Generate unique filename
    $fileName = uniqid('banner_') . '.' . $file->getClientOriginalExtension();

    // Move directly to public folder (just like your food menu)
    $file->move('images/banners', $fileName);

    // Save only the relative path
    $imagePath = 'images/banners/' . $fileName;

    Banner::create([
        'image' => $imagePath,
    ]);

    return redirect()
        ->route('banners.index')
        ->with('success', 'Banner uploaded successfully!');
}

public function bannerEdit($id)
{
    $banner = Banner::findOrFail($id);
    return view('company.admin.advert-banner.edit', compact('banner'));
}

public function bannerUpdate(Request $request, $id): RedirectResponse
{
    $request->validate([
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
    ]);

    $banner = Banner::findOrFail($id);

    if ($request->hasFile('image')) {
        // Delete old image
        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        // Upload new one
        $file = $request->file('image');
        $fileName = uniqid('banner_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/banners'), $fileName);

        $banner->image = 'images/banners/' . $fileName;
    }

    $banner->save();

    return redirect()
        ->route('banners.index')
        ->with('success', 'Banner updated successfully!');
}

public function bannerDestroy($id): RedirectResponse
{
    $banner = Banner::findOrFail($id);

    // Delete physical file
    if ($banner->image && file_exists(public_path($banner->image))) {
        unlink(public_path($banner->image));
    }

    $banner->delete();

    return back()->with('success', 'Banner deleted successfully!');
}
public function destroyCharge($id): RedirectResponse
{
    $charge = Charges::findOrFail($id);
    $charge->delete();

    return redirect()
        ->route('charges.index')
        ->with('success-message', 'Charge deleted successfully.');
}


}
