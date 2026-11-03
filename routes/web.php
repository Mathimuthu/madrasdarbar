<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\FoodMenu\FoodCategoryController;
use App\Http\Controllers\Admin\FoodMenu\FoodLocationController;
use App\Http\Controllers\Admin\FoodMenu\FoodMenuController;
use App\Http\Controllers\Admin\Partnership\PartnershipController;
use App\Http\Controllers\Admin\PromotionDiscount\PromotionDiscountController;
use App\Http\Controllers\Admin\PromotionDiscount\PromotionEventController;
use App\Http\Controllers\Admin\Restaurant\ItemCategoryController;
use App\Http\Controllers\Admin\Restaurant\RestaurantController;
use App\Http\Controllers\Admin\Settings\AdminProfileController;
use App\Http\Controllers\Admin\StaffAccount\StaffAccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Public\LocationController;
use App\Http\Controllers\Delivery\DeliveryController;
use App\Http\Controllers\OrderNotificationController;
use App\Http\Controllers\Staff\Order\OrderControler;
use App\Http\Controllers\Staff\Profile\StaffProfileController;
use App\Http\Controllers\Staff\Reservation\ReservationController;
use App\Http\Controllers\Staff\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Public Routes
// Route::get('/', [PublicController::class, 'login'])->name('welcome');
// Route::post('/login/submit', [LocationController::class, 'submit'])->name('login.submit');

Route::get('/', [LocationController::class, 'showOrderPage'])->name('public.login');
Route::post('/order/submit', [LocationController::class, 'submit'])->name('order.submit');
Route::get('/home', [LocationController::class, 'home'])->name('home');
Route::post('/checkout', [LocationController::class, 'checkout'])->name('checkout');

Route::post('/save-fcm-token', [PublicController::class, 'saveFcmToken'])
    ->middleware('customer.auth') // your customer middleware
    ->name('save.fcm.token');

    
Route::middleware(['customer.auth'])->group(function () {
    Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
    // Route::get('/home', [PublicController::class, 'home'])->name('home');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/promotion', [PublicController::class, 'promotion'])->name('promotion');
    Route::get('/reservation', [PublicController::class, 'reservation'])->name('reservation');
    Route::get('/location/{id}/menu', [PublicController::class, 'locationMenuPage'])->name('location.menu');
    Route::get('/customer/address/{id}', [PublicController::class, 'getCustomerAddress']);
    Route::post('/customer/logout', [PublicController::class, 'logout'])->name('customer.logout');

    // Cart Management Routes
    Route::post('/cart/add', [PublicController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [PublicController::class, 'updateCartQuantity'])->name('cart.update');
    Route::post('/cart/update-delivery-type', [PublicController::class, 'updateDeliveryType'])->name('cart.update-delivery-type');
    Route::post('/cart/remove', [PublicController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [PublicController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/get', [PublicController::class, 'getCart'])->name('cart.get');

    // Order Routes
    Route::post('/menu/create-order', [PublicController::class, 'createOrder'])->name('create-order');
    
    // Reservation
    Route::post('/reservation/create', [PublicController::class, 'makeReservation'])->name('create-reservation');
    
    // Search
    Route::get('/search', [PublicController::class, 'search'])->name('search');
    
    // Order History Routes
    Route::get('/orders', [PublicController::class, 'orderHistory'])->name('orders.history');
    Route::get('/orders/{orderId}', [PublicController::class, 'orderDetails'])->name('orders.details');
    // Search Routes
Route::post('/menu/search', [PublicController::class, 'searchMenu'])->name('menu.search');
Route::get('/location/categories', [PublicController::class, 'getLocationCategories'])->name('location.categories');
Route::get('/customer/data', [LocationController::class, 'getCustomerData'])->name('customer.data');
// Public routes
Route::get('/charges', [LocationController::class, 'getCharges'])->name('public.charges');
});


// Route for login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

// Route for logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route for registration
Route::get('/register', [RegistrationController::class, 'index'])->name('register');
Route::post('/register', [RegistrationController::class, 'store']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password');

// New order notifications for admin and staff (polled by the bell)
Route::get('/order-notifications', [OrderNotificationController::class, 'index'])
    ->middleware('auth', 'isAdmin')
    ->name('order-notifications');

// Route for admin
Route::prefix('admin')->middleware('auth', 'isAdmin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin-dashboard');
    Route::get('/delivery-management', [AdminController::class, 'DeliveryManagement'])->name('delivery-management');

    // Staff account module
    Route::resource('/staff-account', StaffAccountController::class)->names([
        'index' => 'staff-account',
        'create' => 'staff-account-create',
        'show' => 'staff-account-show',
        'edit' => 'staff-account-edit',
    ]);
    Route::delete('/staff-account-id/{staff_account_id}', [StaffAccountController::class, 'destroyStaffAccountId'])
    ->name('staff-account-id.destroy');

    // Route for search in staff account module
    Route::get('/staff-search-index', [StaffAccountController::class, 'search_index'])->name('staff-account-search-index');
    Route::get('/staff-search-create', [StaffAccountController::class, 'search_create'])->name('staff-account-search-create');

    // Location master data module
    Route::resource('/location', AdminLocationController::class)->names([
        'index'   => 'admin-location',
        'create'  => 'admin-location-create',
        'store'   => 'admin-location.store',
        'edit'    => 'admin-location-edit',
        'update'  => 'admin-location.update',
        'destroy' => 'admin-location.destroy',
    ]);
    Route::get('/location-search', [AdminLocationController::class, 'search'])->name('admin-location-search');

    // Food menu module
    Route::resource('/food-menu', FoodMenuController::class)->names([
        'index' => 'food-menu',
        'create' => 'food-menu-create',
        'show' => 'food-menu-show',
        'edit' => 'food-menu-edit',
    ]);
    Route::resource('/food-location', FoodLocationController::class)->names([
        'index' => 'food-location',
        'create' => 'food-location-create',
        'show' => 'food-location-show',
        'edit' => 'food-location-edit',
    ]);

    Route::post('/food-menu/create', [FoodCategoryController::class, 'store'])->name('food-category');
    Route::delete('/food-category-delete/{id}', [FoodCategoryController::class, 'destroy'])->name('food-category-delete');
    Route::get('/food-search-index', [FoodMenuController::class, 'search_index'])->name('food-menu-search-index');
    Route::get('/food-search-create', [FoodMenuController::class, 'search_create'])->name('food-menu-search-create');

    // Restaurant module
    Route::resource('/restaurant', RestaurantController::class)->names([
        'index' => 'restaurant',
        'create' => 'restaurant-create',
        'show' => 'restaurant-show',
        'edit' => 'restaurant-edit',
    ]);

    Route::post('/restaurant/create', [ItemCategoryController::class, 'store'])->name('item-category');
    Route::delete('/item-category-delete/{id}', [ItemCategoryController::class, 'destroy'])->name('item-category-delete');

    // Partnership module
    Route::resource('/partnership', PartnershipController::class)->names([
        'index' => 'partnership',
        'create' => 'partnership-create',
        'edit' => 'partnership-edit',
    ]);

    // Promotion and discount module
    Route::resource('/promotion-discount', PromotionDiscountController::class)->names([
        'index' => 'promotion-discount',
        'create' => 'promotion-discount-create',
        'show' => 'promotion-discount-show',
        'edit' => 'promotion-discount-edit',
    ]);
    
    // Charges module
    Route::prefix('charges')->name('charges.')->group(function () {
        Route::get('/', [PromotionDiscountController::class, 'charge_index'])->name('index');
        Route::get('/create', [PromotionDiscountController::class, 'charge_create'])->name('create');
        Route::post('/', [PromotionDiscountController::class, 'storeCharge'])->name('store'); // Add this method
        Route::get('/{id}', [PromotionDiscountController::class, 'charge_show'])->name('show');
        Route::get('/{id}/edit', [PromotionDiscountController::class, 'charge_edit'])->name('edit');
        Route::put('/{id}', [PromotionDiscountController::class, 'updateCharge'])->name('update');
    });
    Route::delete('/charges/{id}', [
    PromotionDiscountController::class,
    'destroyCharge'
])->name('charges.destroy');

// Custom resource with different method & route names
// In your admin routes file (e.g. routes/web.php or routes/admin.php)

Route::prefix('banners')->name('banners.')->group(function () {
    Route::get('/', [PromotionDiscountController::class, 'bannerIndex'])->name('index');
    Route::get('/create', [PromotionDiscountController::class, 'bannerCreate'])->name('create');
    Route::post('/', [PromotionDiscountController::class, 'bannerStore'])->name('store');
    Route::get('/{id}/edit', [PromotionDiscountController::class, 'bannerEdit'])->name('edit');
    Route::put('/{id}', [PromotionDiscountController::class, 'bannerUpdate'])->name('update');
    Route::delete('/{id}', [PromotionDiscountController::class, 'bannerDestroy'])->name('destroy');
});

    Route::post('/promotion-discount/create', [PromotionEventController::class, 'store'])->name('promotion-event');

    Route::get('/settings', [AdminProfileController::class, 'adminProfile'])->name('admin-profile');
    Route::put('/settings/update-profile/{id}', [AdminProfileController::class, 'updateProfile'])->name('update-admin-profile');
    Route::put('/settings/update-password/{id}', [AdminProfileController::class, 'updatePassword'])->name('update-admin-password');
});


// In your delivery routes section
Route::prefix('delivery')->middleware('auth', 'isDeliveryboy')->group(function () {
    Route::get('/delivery-order', [DeliveryController::class, 'delivery_order'])->name('delivery-order');
    Route::post('/update-order/{id}', [DeliveryController::class, 'updateStatus'])->name('update-order');
    Route::post('/update-order-payment/{id}', [DeliveryController::class, 'updatePaymentStatus'])->name('update-order-payment');
});

// Route for staff
Route::prefix('staff')->middleware('auth', 'isStaff')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff-dashboard');
    Route::get('/completed_order', [OrderControler::class, 'completed_order'])->name('completed-order');

    // Profile module
    Route::resource('/staff-profile', StaffProfileController::class)->names([
        'show' => 'staff-profile-show',
        'edit' => 'staff-profile-edit',
    ]);
    
    Route::post('/customer-order/{order}/assign', [OrderControler::class, 'assignOrder'])
        ->name('order.assign');

    // Order module
    Route::resource('/customer-order', OrderControler::class)->names([
        'index' => 'customer-order',
        'create' => 'customer-order-create',
        'show' => 'customer-order-show',
        'edit' => 'customer-order-edit',
    ]);
    
    Route::prefix('customer-order')->group(function () {
        Route::post('/{id}/accept', [OrderControler::class, 'acceptOrder'])->name('order.accept');
        Route::post('/{id}/unaccept', [OrderControler::class, 'unacceptOrder'])->name('order.unaccept');
        Route::get('/{id}/history/json', [OrderControler::class, 'getOrderHistoryJson'])->name('order.history.json');
        Route::put('/update-order/{id}', [OrderControler::class, 'updateStatus'])->name('update-order');
        Route::put('/update-order-payment/{id}', [OrderControler::class, 'updatePaymentStatus'])->name('update-order-payment');
    });

    // Reservation module
    Route::resource('/customer-reservation', ReservationController::class)->names([
        'index' => 'customer-reservation',
        'create' => 'customer-reservation-create',
        'show' => 'customer-reservation-show',
        'edit' => 'customer-reservation-edit',
    ]);
});
