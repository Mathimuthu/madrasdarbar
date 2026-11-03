<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\FoodCategory;
use App\Models\FoodMenu;
use App\Models\Charges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDetail;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function showOrderPage()
    {
        $locations = Location::get();
        return view('public.login', ['locations' => $locations]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:delivery,pickup',
            'location_id' => 'required|exists:location,location_id',
            'phone_number' => 'required|string|max:20|min:8'
        ]);

        $customer = Customer::firstOrCreate(
            ['mobile' => $request->input('phone_number')],
            [
                'mobile' => $request->input('phone_number'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $locationPhones = [
            1 => '+66922717767', // Phuket
            2 => '+66944292089', // Bangkok
            3 => '+66990984666', // Pattaya
            4 => '+94743124147', // Colombo
        ];

        $locationPhone = $locationPhones[$request->location_id] ?? $request->input('phone_number');

        session([
            'order_type' => $request->input('order_type'),
            'location_id' => $request->input('location_id'),
            'location_number' => $locationPhone,
            'customer_phone' => $customer->mobile,
            'customer_id' => $customer->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location and phone number saved successfully',
        ]);
    }

    public function home()
    {
        $locationId = session('location_id');
        if (!$locationId) {
            return redirect()->route('public.login')->with('error', 'Please select a location first');
        }

        $location = Location::findOrFail($locationId);

        // Get active charges for this location (excluding delivery charges which are separate per order)
        $charges = Charges::where('location_id', $locationId)
            ->where('status', 1)
            ->whereRaw('LOWER(name) NOT LIKE ?', ['%delivery%'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = FoodCategory::select('food_categories.*')
            ->join('food_menus', 'food_menus.category_id', '=', 'food_categories.id')
            ->join('food_price', 'food_price.food_id', '=', 'food_menus.id')
            ->where('food_price.location_id', $locationId)
            ->distinct()
            ->get();

        $foodMenuByCategory = [];

        foreach ($categories as $category) {
            $foodItems = FoodMenu::select('food_menus.*', 'food_price.price')
                ->join('food_price', 'food_price.food_id', '=', 'food_menus.id')
                ->where('food_menus.category_id', $category->id)
                ->where('food_price.location_id', $locationId)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'image' => $item->image,
                        'price' => $item->price,
                        'category_id' => $item->category_id,
                    ];
                });

            $foodMenuByCategory[$category->id] = [
                'category' => $category,
                'items' => $foodItems
            ];
        }

        return view('public.home', [
            'location' => $location,
            'categories' => $categories,
            'foodMenuByCategory' => $foodMenuByCategory,
            'charges' => $charges,
            'orderType' => session('order_type', 'delivery')
        ]);
    }
    public function getCharges(Request $request): JsonResponse
    {
        try {
            $locationId = $request->input('location_id') ?? session('location_id');
            
            if (!$locationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found'
                ]);
            }
    
            $charges = Charges::where('location_id', $locationId)
                ->where('status', 1) // Only active charges
                ->whereRaw('LOWER(name) NOT LIKE ?', ['%delivery%'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($charge) {
                    return [
                        'id' => $charge->id,
                        'name' => $charge->name,
                        'amount' => floatval($charge->amount),
                        'type' => intval($charge->type),
                        'status' => intval($charge->status),
                        'location_id' => $charge->location_id
                    ];
                });
    
            return response()->json([
                'success' => true,
                'charges' => $charges
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching charges', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching charges'
            ]);
        }
    }

    public function getCustomerData(Request $request): JsonResponse
    {
        try {
            $customerPhone = session('customer_phone');
            if (!$customerPhone) {
                return response()->json(['success' => false, 'message' => 'Customer not found']);
            }

            $customer = Customer::where('mobile', $customerPhone)->first();

            if ($customer) {
                return response()->json([
                    'success' => true,
                    'customer' => [
                        'name' => $customer->name ?? '',
                        'address' => $customer->address ?? '',
                        'door_no' => $customer->door_no ?? '',
                        'area' => $customer->area ?? ''
                    ]
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Customer not found']);
        } catch (\Exception $e) {
            Log::error('Error fetching customer data', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching customer data']);
        }
    }

    public function checkout(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string',
                'customer_address' => 'required|string|max:500',
                'door_no' => 'required|string|max:20',
                'area' => 'required|string|max:500',
                'payment_type' => 'required|in:cash,card',
                'total_amount' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'charges' => 'nullable|array',
                'items' => 'required|array|min:1',
                'items.*.food_id' => 'required|exists:food_menus,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500',
            ]);

            $locationId = session('location_id');
            if (!$locationId) {
                return response()->json(['validation-error-message' => 'Location not found. Please select a location first.'], 422);
            }

            $location = Location::find($locationId);
            if (!$location) {
                return response()->json(['validation-error-message' => 'Invalid location selected.'], 422);
            }

            // Get active charges for this location (excluding delivery charges which are separate per order)
            $chargesData = Charges::where('location_id', $locationId)
                ->where('status', 1)
                ->whereRaw('LOWER(name) NOT LIKE ?', ['%delivery%'])
                ->get();

            // Calculate charges if not provided in request
            $charges = $validated['charges'] ?? [];
            // Filter out delivery charges since delivery charges are separate per order
            $charges = array_values(array_filter($charges, function ($charge) {
                $name = strtolower($charge['name'] ?? '');
                return !str_contains($name, 'delivery');
            }));

            $calculatedCharges = [];
            $totalChargesAmount = 0;
            
            if (empty($charges)) {
                foreach ($chargesData as $charge) {
                    $amount = 0;
                    if ($charge->type == 2) { // Percentage
                        $amount = ($validated['subtotal'] * $charge->amount) / 100;
                    } else { // Fixed amount
                        $amount = $charge->amount;
                    }
                    
                    $calculatedCharges[] = [
                        'name' => $charge->name,
                        'type' => $charge->type,
                        'rate' => $charge->amount,
                        'amount' => $amount
                    ];
                    $totalChargesAmount += $amount;
                }
            } else {
                foreach ($charges as $charge) {
                    $calculatedCharges[] = $charge;
                    $totalChargesAmount += floatval($charge['amount'] ?? 0);
                }
            }
            
            // Verify total amount matches subtotal + non-delivery charges
            $calculatedTotal = $validated['subtotal'] + $totalChargesAmount;
            if (abs($calculatedTotal - $validated['total_amount']) > 0.05) {
                return response()->json([
                    'validation-error-message' => 'Total amount mismatch. Please refresh and try again.'
                ], 422);
            }

            // Set timezone based on location
            $timezones = [
                1 => 'Asia/Bangkok',
                2 => 'Asia/Bangkok',
                3 => 'Asia/Bangkok',
                4 => 'Asia/Colombo',
            ];
            date_default_timezone_set($timezones[$locationId] ?? 'Asia/Bangkok');
            
            $lat = $request->latitude;
            $lon = $request->longitude;
            if ((!$lat || !$lon) && !empty($validated['customer_address'])) {
                if (preg_match('/Lat:\s*([0-9.-]+),\s*Lon:\s*([0-9.-]+)/i', $validated['customer_address'], $matches)) {
                    $lat = $matches[1];
                    $lon = $matches[2];
                }
            }
            $latlon = ($lat && $lon) ? ($lat . ',' . $lon) : null;

            $customerContact = session('customer_phone');
            $customer = Customer::firstOrCreate(
                ['mobile' => $customerContact],
                [
                    'name' => $validated['customer_name'],
                    'address' => $validated['customer_address'],
                    'area' => $validated['area'],
                    'door_no' => $validated['door_no'],
                    'latlon' => $latlon,
                ]
            );

            $customer->update([
                'name'    => $validated['customer_name'],
                'address' => $validated['customer_address'],
                'area'    => $validated['area'],
                'door_no' => $validated['door_no'],
                'latlon'  => $latlon,
            ]);
            
            // 🔥 Retry logic to avoid duplicate order codes
            $maxRetries = 5;
            $attempt = 0;
            $createdOrder = null;

            do {
                DB::beginTransaction();
                try {
                    // Create order with charges breakdown
                    $order = CustomerOrder::create([
                        'customer_id' => $customer->id,
                        'location_id' => $locationId,
                        'dining_table_id' => null,
                        'order_subtotal' => $validated['subtotal'],
                        'order_total_price' => $validated['total_amount'],
                        'delivery_type' => session('order_type', 'delivery'),
                        'payment_type' => $validated['payment_type'],
                        // 'isPaid' => $validated['payment_type'] === 'card',
                        'order_status' => OrderStatusEnum::Ordered,
                        'customer_contact' => $customerContact,
                        'customer_address' => $validated['customer_address'],
                        'order_notes' => $validated['order_notes'] ?? null,
                        'charges_breakdown' => json_encode($calculatedCharges),
                    ]);

                    foreach ($validated['items'] as $item) {
                        CustomerOrderDetail::create([
                            'order_id' => $order->id,
                            'food_id' => $item['food_id'],
                            'quantity' => $item['quantity'],
                            'total_price' => $item['price'] * $item['quantity'],
                            'unit_price' => $item['price'],
                        ]);
                    }

                    DB::commit();
                    $createdOrder = $order;
                    break;

                } catch (\Illuminate\Database\QueryException $qe) {
                    DB::rollBack();

                    $isDuplicateOrderCode =
                        isset($qe->errorInfo[1]) &&
                        $qe->errorInfo[1] == 1062 &&
                        str_contains($qe->getMessage(), 'customer_orders_order_code_unique');

                    $attempt++;

                    if ($isDuplicateOrderCode && $attempt <= $maxRetries) {
                        usleep(200000); // Wait 200ms before retrying
                        continue;
                    }

                    throw $qe;
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }

            } while ($attempt <= $maxRetries);

            if (!$createdOrder) {
                return response()->json(['validation-error-message' => 'Could not create order. Please try again.'], 500);
            }

            return response()->json([
                'success-message' => 'Your order has been placed successfully!',
                'order_id' => $createdOrder->id,
                'order_code' => $createdOrder->order_code,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in order creation', ['errors' => $e->errors()]);
            return response()->json([
                'validation-error-message' => implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'validation-error-message' => 'An error occurred while processing your order: ' . $e->getMessage()
            ], 500);
        }
    }
}
