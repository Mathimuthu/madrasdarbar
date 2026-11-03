<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffAuthController;
use App\Http\Controllers\Api\DeliveryOrderController;
use App\Http\Controllers\Api\ChargesController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get(
    '/delivery/orders',
    [DeliveryOrderController::class, 'myOrders']
);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/staff/profile', [StaffAuthController::class, 'profile']);
});
Route::middleware('auth:sanctum')->post(
    '/delivery/orders/{order}/status',
    [DeliveryOrderController::class, 'updateStatus']
);

Route::middleware('auth:sanctum')->get(
    '/delivery/orders/{order}',
    [DeliveryOrderController::class, 'show']
);

Route::middleware('auth:sanctum')->post(
    '/delivery/orders/{order}/payment-status',
    [DeliveryOrderController::class, 'updatePaymentStatus']
);
Route::middleware('auth:sanctum')->group(function () {
    // Get available charges
    Route::get('/charges', [ChargesController::class, 'index']);
            Route::post('delivery/orders/{orderId}/delivery-charge', [DeliveryOrderController::class, 'addDeliveryCharge']);
        Route::delete('delivery/orders/{orderId}/delivery-charge', [DeliveryOrderController::class, 'removeDeliveryCharge']);
    // Delivery staff charge management
    Route::prefix('delivery/orders/{orderId}')->group(function () {
        Route::post('/add-charge', [DeliveryOrderController::class, 'addChargeToOrder']);
        Route::post('/add-custom-charge', [DeliveryOrderController::class, 'addCustomChargeToOrder']);
    });
});