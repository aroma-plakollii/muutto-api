<?php

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\MbAdditionalDayController;
use App\Http\Controllers\MbPriceController;
use App\Http\Controllers\MsCompanyController;
use App\Http\Controllers\MsProductController;
use App\Http\Controllers\MsProductTypeController;
use App\Http\Controllers\MsUnitControlller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MbBookingController;
use App\Http\Controllers\MbCompanyController;
use App\Http\Controllers\MbFreeCityController;
use App\Http\Controllers\MbBlockedDatesController;
use App\Http\Controllers\MsUnitController;
use App\Http\Controllers\MsCustomPriceController;
use App\Http\Controllers\MsFreeCityController;
use App\Http\Controllers\MsUnitAvailabilityController;
use App\Http\Controllers\MsUnitProductPriceController;
use App\Http\Controllers\MsBookingController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\MsExtraServiceController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\BookingRequestController;
use App\Http\Controllers\BookingRequestPriceController;
use App\Http\Controllers\BookingRequestProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RegionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/mb-bookings/{id}', [MbBookingController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    //Users
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/mb-users-by-id/{id}', [AuthController::class, 'getSingleUser']);
    Route::delete('/mb-users/{id}', [AuthController::class, 'destroy']);
    Route::put('/mb-users-update/{id}', [AuthController::class, 'update']);
    Route::post('/mb-users-password-reset', [NewPasswordController::class, 'reset']);

    // //Bookings
    // Route::resource('/mb-bookings', MbBookingController::class);
    // Route::get('/mb-bookings/search/{bookingNumber}', [MbBookingController::class, 'search']);
    // Route::post('/mb-bookings-update-status', [MbBookingController::class, 'updateStatus']);
    // Route::put('/mb-bookings-update/{id}', [MbBookingController::class, 'update']);
    // Route::post('/mb-bookings-day', [MbBookingController::class, 'getBookingsByDay']);
    // Route::post('/mb-bookings-month', [MbBookingController::class, 'getBookingsByMonth']);


    // //Companies
    // Route::get('/mb-companies', [MbCompanyController::class, 'index']);
    // Route::get('/mb-companies/{id}', [MbCompanyController::class, 'show']);
    // Route::post('/mb-company-create', [MbCompanyController::class, 'store']);
    // Route::delete('/mb-company/{id}', [MbCompanyController::class, 'destroy']);
    // Route::put('/mb-company/{id}', [MbCompanyController::class, 'update']);


    // //Prices
    // Route::get('/mb-prices', [MbPriceController::class, 'index']);
    // Route::get('/mb-prices/{id}', [MbPriceController::class, 'show']);
    // Route::post('/mb-prices-create', [MbPriceController::class, 'store']);
    // Route::delete('/mb-prices/{id}', [MbPriceController::class, 'destroy']);
    // Route::put('/mb-prices/{id}', [MbPriceController::class, 'update']);

    // //Free Cities
    // Route::get('/mb-cities', [MbFreeCityController::class, 'index']);
    // Route::get('/mb-cities/{id}', [MbFreeCityController::class, 'show']);
    // Route::get('/mb-cities-by-company/{id}', [MbFreeCityController::class, 'getAllByCompany']);
    // Route::post('/mb-cities-create', [MbFreeCityController::class, 'store']);
    // Route::delete('/mb-cities/{id}', [MbFreeCityController::class, 'destroy']);
    // Route::put('/mb-cities/{id}', [MbFreeCityController::class, 'update']);

    // //Blocked Dates
    // Route::get('/mb-blocked-dates', [MbBlockedDatesController::class, 'index']);
    // Route::get('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'show']);
    // Route::post('/mb-blocked-dates-create', [MbBlockedDatesController::class, 'store']);
    // Route::delete('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'destroy']);
    // Route::put('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'update']);

    // //Bookings

    // Route::post('/mb-bookings-no-transport', [MbBookingController::class, 'getBookingsByMonthNoTransport']);
    // Route::get('/mb-bookings-month', [MbBookingController::class, 'month']);
    // Route::get('/mb-bookings-no-transport', [MbBookingController::class, 'noTransport']);
    // Route::post('/mb-bookings-create-without-email', [MbBookingController::class, 'createBooking']);

    // //Moving Service Routes
    // Route::get('/ms-companies/pricing', [MsCompanyController::class, 'pricing']);
});

//Moving Boxes Routes

// Admin Routes

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function(){

    //Users
    Route::get('/mb-users-by-id/{id}', [AuthController::class, 'getSingleUser']);
    Route::delete('/mb-users/{id}', [AuthController::class, 'destroy']);
    Route::put('/mb-users-update/{id}', [AuthController::class, 'update']);

    //Companies
    Route::get('/mb-companies', [MbCompanyController::class, 'index']);
    Route::post('/mb-company-create', [MbCompanyController::class, 'store']);
    Route::delete('/mb-company/{id}', [MbCompanyController::class, 'destroy']);

    //Prices
    Route::get('/mb-prices', [MbPriceController::class, 'index']);

    //Free Cities
    Route::get('/mb-cities', [MbFreeCityController::class, 'index']);
    Route::get('/mb-cities/{id}', [MbFreeCityController::class, 'show']);
    Route::get('/mb-cities-by-company/{id}', [MbFreeCityController::class, 'getAllByCompany']);
    Route::post('/mb-cities-create', [MbFreeCityController::class, 'store']);
    Route::delete('/mb-cities/{id}', [MbFreeCityController::class, 'destroy']);
    Route::put('/mb-cities/{id}', [MbFreeCityController::class, 'update']);

    //Blocked Dates
    Route::get('/mb-blocked-dates', [MbBlockedDatesController::class, 'index']);

    //Coupons
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);
    Route::get('/coupons/{id}', [CouponController::class, 'show']);
    Route::put('/coupons-update/{id}', [CouponController::class, 'update']);

    // Role Routes
    Route::get('/role', [RoleController::class, 'index']);
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::post('/role-create', [RoleController::class, 'store']);
    Route::put('/role-update/{id}', [RoleController::class, 'update']);
    Route::delete('/role/{id}', [RoleController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin|company']], function(){

    //Companies
    Route::get('/mb-companies', [MbCompanyController::class, 'index']);
    Route::post('/mb-company-create', [MbCompanyController::class, 'store']);
    Route::delete('/mb-company/{id}', [MbCompanyController::class, 'destroy']);
    Route::get('/mb-companies/{id}', [MbCompanyController::class, 'show']);
    Route::put('/mb-company/{id}', [MbCompanyController::class, 'update']);

    //Bookings
    Route::resource('/mb-bookings', MbBookingController::class);
    Route::get('/mb-bookings/search/{bookingNumber}', [MbBookingController::class, 'search']);
    Route::post('/mb-bookings-update-status', [MbBookingController::class, 'updateStatus']);
    Route::post('/mb-bookings-day', [MbBookingController::class, 'getBookingsByDay']);
    Route::post('/mb-bookings-month', [MbBookingController::class, 'getBookingsByMonth']);

    Route::post('/mb-bookings-no-transport', [MbBookingController::class, 'getBookingsByMonthNoTransport']);
    Route::get('/mb-bookings-month', [MbBookingController::class, 'month']);
    Route::get('/mb-bookings-no-transport', [MbBookingController::class, 'noTransport']);
    Route::post('/mb-bookings-create-without-email', [MbBookingController::class, 'createBooking']);

});

Route::group(['middleware' => ['auth:sanctum', 'role:company']], function(){

    // Bookings
    Route::put('/mb-bookings-update/{id}', [MbBookingController::class, 'update']);

    //Blocked Dates
    Route::get('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'show']);
    Route::post('/mb-blocked-dates-create', [MbBlockedDatesController::class, 'store']);
    Route::delete('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'destroy']);
    Route::put('/mb-blocked-dates/{id}', [MbBlockedDatesController::class, 'update']);

    //Prices
    Route::get('/mb-prices', [MbPriceController::class, 'index']);
    Route::get('/mb-prices/{id}', [MbPriceController::class, 'show']);
    Route::post('/mb-prices-create', [MbPriceController::class, 'store']);
    Route::delete('/mb-prices/{id}', [MbPriceController::class, 'destroy']);
    Route::put('/mb-prices/{id}', [MbPriceController::class, 'update']);

    //Free Cities
    Route::get('/mb-cities', [MbFreeCityController::class, 'index']);
    Route::get('/mb-cities/{id}', [MbFreeCityController::class, 'show']);
    Route::get('/mb-cities-by-company/{id}', [MbFreeCityController::class, 'getAllByCompany']);
    Route::post('/mb-cities-create', [MbFreeCityController::class, 'store']);
    Route::delete('/mb-cities/{id}', [MbFreeCityController::class, 'destroy']);
    Route::put('/mb-cities/{id}', [MbFreeCityController::class, 'update']);
});

//Moving Service Routes

//Admin routes
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function() {

    //Ms Companies
    Route::delete('/ms-companies/{id}', [MsCompanyController::class, 'destroy']);
    Route::post('/ms-companies-create', [MsCompanyController::class, 'store']);

    // Products
    Route::post('/ms-products-create', [MsProductController::class, 'store']);
    Route::delete('/ms-products/{id}', [MsProductController::class, 'destroy']);
    Route::put('/ms-products-update/{id}', [MsProductController::class, 'update']);

    // Product Types
    Route::post('/ms-product-types-create', [MsProductTypeController::class, 'store']);
    Route::get('/ms-product-types/{id}', [MsProductTypeController::class, 'show']);
    Route::delete('/ms-product-types/{id}', [MsProductTypeController::class, 'destroy']);
    Route::put('/ms-product-types-update/{id}', [MsProductTypeController::class, 'update']);

    // Booking Request Prices
    Route::get('/booking-request-prices', [BookingRequestPriceController::class, 'index']);
    Route::get('/booking-request-prices/{id}', [BookingRequestPriceController::class, 'show']);
    Route::put('/booking-request-prices-update/{id}', [BookingRequestPriceController::class, 'update']);
    Route::delete('/booking-request-price/{id}', [BookingRequestPriceController::class, 'destroy']);

    // Booking Request Products
    Route::get('/booking-request-products', [BookingRequestProductController::class, 'index']);
    Route::post('/booking-request-products-create', [BookingRequestProductController::class, 'store']);
    Route::get('/booking-request-products/{id}', [BookingRequestProductController::class, 'show']);
    Route::delete('/booking-request-products/{id}', [BookingRequestProductController::class, 'destroy']);
    Route::put('/booking-request-products-update/{id}', [BookingRequestProductController::class, 'update']);

    //Regions
    Route::post('/regions-create', [RegionController::class, 'store']);
    Route::put('/regions-update/{id}', [RegionController::class, 'update']);
    Route::delete('/regions/{id}', [RegionController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin|company']], function(){

    //Company
    Route::get('/ms-companies/{id}', [MsCompanyController::class, 'show']);
    Route::put('/ms-companies-update/{id}', [MsCompanyController::class, 'update']);

    // Products
    Route::get('/ms-products/{id}', [MsProductController::class, 'show']);
});

//Company routes
Route::group(['middleware' => ['auth:sanctum', 'role:company']], function() {

    //MsCompany Product Price
    Route::post('/ms-units-product-prices-create', [MsUnitProductPriceController::class, 'store']);
    Route::get('/ms-units-product-prices', [MsUnitProductPriceController::class, 'index']);
    Route::get('/ms-units-product-prices/{id}', [MsUnitProductPriceController::class, 'show']);
    Route::put('/ms-units-product-prices-update/{id}', [MsUnitProductPriceController::class, 'update']);
    Route::get('/ms-units-product-prices-by-unit', [MsUnitProductPriceController::class, 'getPricesByUnit']);

    //Units
    Route::get('/ms-units', [MsUnitController::class, 'index']);
    Route::post('/ms-units-create', [MsUnitController::class, 'store']);
    Route::get('/ms-units/{id}', [MsUnitController::class, 'show']);
    Route::put('/ms-units-update/{id}', [MsUnitController::class, 'update']);
    Route::delete('/ms-units/{id}', [MsUnitController::class, 'destroy']);

    //Custom Price
    Route::get('/ms-custom-prices', [MsCustomPriceController::class, 'index']);
    Route::post('/ms-custom-prices-create', [MsCustomPriceController::class, 'store']);
    Route::get('/ms-custom-prices/{id}', [MsCustomPriceController::class, 'show']);
    Route::put('/ms-custom-prices-update/{id}', [MsCustomPriceController::class, 'update']);
    Route::delete('/ms-custom-prices/{id}', [MsCustomPriceController::class, 'destroy']);
    Route::get('/ms-custom-prices-by-company/{id}', [MsCustomPriceController::class, 'getCustomPricesByCompany']);

    //MsUnit Availability
    Route::get('/ms-units-availability', [MsUnitAvailabilityController::class, 'index']);
    Route::post('/ms-units-availability-create', [MsUnitAvailabilityController::class, 'store']);
    Route::get('/ms-units-availability/{id}', [MsUnitAvailabilityController::class, 'show']);
    Route::put('/ms-units-availability-update/{id}', [MsUnitAvailabilityController::class, 'update']);
    Route::delete('/ms-units-availability/{id}', [MsUnitAvailabilityController::class, 'destroy']);
    Route::get('/ms-units-availability-by-company/{id}', [MsUnitAvailabilityController::class, 'getUnitAvailabilityByCompany']);

    //Ms Free Cities
    Route::get('/ms-free-cities', [MsFreeCityController::class, 'index']);
    Route::get('/ms-free-cities-by-company/{id}', [MsFreeCityController::class, 'getFreeCitiesByCompany']);
    Route::post('/ms-free-cities-create', [MsFreeCityController::class, 'store']);
    Route::get('/ms-free-cities/{id}', [MsFreeCityController::class, 'show']);
    Route::put('/ms-free-cities-update/{id}', [MsFreeCityController::class, 'update']);
    Route::delete('/ms-free-cities/{id}', [MsFreeCityController::class, 'destroy']);

    // Booking Requests
    Route::get('/booking-requests', [BookingRequestController::class, 'index']);
    Route::get('/booking-requests/{id}', [BookingRequestController::class, 'show']);
    Route::put('/booking-requests-update/{id}', [BookingRequestController::class, 'update']);
    Route::delete('/booking-requests/{id}', [BookingRequestController::class, 'destroy']);

    //MsBooking
    Route::post('/ms-bookings-create', [MsBookingController::class, 'store']);
    Route::post('/ms-bookings-send-payment-email/{id}', [MsBookingController::class, 'sendPaymentEmail']);

    // Extra Service
    Route::post('/ms-bookings/extra-service/{id}', [MsExtraServiceController::class, 'store']);
    Route::get('/ms-extra-service', [MsExtraServiceController::class, 'index']);
    Route::get('/ms-extra-service-by-booking-id/{id}', [MsExtraServiceController::class, 'getExtraServiceByBooking']);

    //Coupons
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons-create', [CouponController::class, 'store']);
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);
    Route::get('/coupons/{id}', [CouponController::class, 'show']);
    Route::put('/coupons-update/{id}', [CouponController::class, 'update']);
    Route::get('/coupons-by-company/{id}', [CouponController::class, 'getCouponsByCompany']);

});

//Company or Unit routes
Route::group(['middleware' => ['auth:sanctum', 'role:unit|company']], function() {
    
    //MsBooking
    Route::get('/ms-bookings', [MsBookingController::class, 'index']);
    Route::get('/ms-bookings/{id}', [MsBookingController::class, 'show']);
    Route::put('/ms-bookings-update/{id}', [MsBookingController::class, 'update']);
    Route::get('/ms-bookings-by-company/{id}', [MsBookingController::class, 'getBookingsByCompany']);
    Route::post('/ms-bookings-day', [MsBookingController::class, 'getBookingsByDay']);
    Route::post('/ms-bookings-month', [MsBookingController::class, 'getBookingsByMonth']);
    Route::put('/ms-bookings-update-status', [MsBookingController::class, 'updateStatus']);
    Route::delete('/ms-bookings/{id}', [MsBookingController::class, 'destroy']);

    // Extra Service
    Route::post('/ms-bookings/extra-service/{id}', [MsExtraServiceController::class, 'store']);
    Route::get('/ms-extra-service', [MsExtraServiceController::class, 'index']);
    Route::get('/ms-extra-service-by-booking-id/{id}', [MsExtraServiceController::class, 'getExtraServiceByBooking']);

    //Units
    Route::get('/ms-units-by-company/{id}', [MsUnitController::class, 'getUnitsByCompany']);
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin|company|unit']], function(){

    //Company
    Route::get('/ms-companies/{id}', [MsCompanyController::class, 'show']);
});
/**
 * End Private Routes
 */

/**
 * Public Routes
 */

// Role Routes
Route::get('/role', [RoleController::class, 'index']);
Route::get('role/{id}', [RoleController::class, 'show']);
Route::post('/role-create', [RoleController::class, 'store']);
Route::put('/role-update/{id}', [RoleController::class, 'update']);
Route::delete('/role/{id}', [RoleController::class, 'destroy']);

//Widget routes
Route::get('/mb-companies-by-secret/{secret}', [MbCompanyController::class, 'getCompanyBySecret']);
Route::get('/mb-prices-by-company/{id}', [MbPriceController::class, 'getAllByCompany']);
Route::post('/get-day-price', [MbPriceController::class, 'getDayPrice']);
Route::post('/get-package-price', [MbPriceController::class, 'getPackagePrice']);
Route::post('/mb-prices-continue', [MbPriceController::class, 'getContinuePrice']);
Route::post('/mb-bookings-create', [MbBookingController::class, 'store']);
Route::post('/mb-bookings-update-payment', [MbBookingController::class, 'updatePayment']);
Route::get('/mb-bookings-continue/{id}', [MbBookingController::class, 'getContinueBooking']);
Route::post('/mb-additional-days-create', [MbAdditionalDayController::class, 'store']);
Route::get('/mb-additional-days-by-booking-id/{id}', [MbAdditionalDayController::class, 'getAllByBookingId']);
Route::get('/ms-products', [MsProductController::class, 'index']);
Route::post('/ms-bookings-update-payment', [MsBookingController::class, 'updatePayment']);
Route::post('/ms-bookings/add-a-block', [MsBookingController::class, 'addABlock']);
Route::post('/ms-bookings/create/send-payment-link', [MsBookingController::class, 'createBookingAndSendPaymentLink']);
Route::post('/booking-request-price-create', [BookingRequestPriceController::class, 'store']);
Route::get('/ms-companies', [MsCompanyController::class, 'index']);
Route::get('/booking-request-by-code/{code}', [BookingRequestController::class, 'getBookingRequestsByCode']);
Route::post('/mb-bookings-payment-canceled', [MbBookingController::class, 'sendCancelEmail']);

//Blocked Dates
Route::get('/mb-blocked-dates-by-company/{id}', [MbBlockedDatesController::class, 'getAllByCompany']);


//Moving Service Routes
Route::get('/ms-products', [MsProductController::class, 'index']);
Route::get('/ms-product-types', [MsProductTypeController::class, 'index']);
Route::post('/ms-products/unit-product-details', [MsProductController::class, 'unit_product_details']);
Route::post('/ms-companies/pricing', [MsCompanyController::class, 'pricing']);
Route::post('/ms-units/pricing', [MsUnitController::class, 'pricing']);
Route::post('/ms-units/extra-pricing', [MsUnitController::class, 'extraPricing']);
Route::post('/ms-units/available', [MsUnitController::class, 'get_units_available']);
Route::post('/ms-units/available-times', [MsUnitController::class, 'get_units_available_times']);
Route::post('/ms-units/available-for-all-companies', [MsUnitController::class, 'get_units_available_for_all_companies']);
Route::post('/ms-bookings/create', [MsBookingController::class, 'createBooking']);
Route::post('/ms-bookings/create/pay-right-away', [MsBookingController::class, 'payRightAway']);
Route::post('/ms-bookings-payment-canceled', [MsBookingController::class, 'sendCancelEmail']);
Route::post('/booking-request-create', [BookingRequestController::class, 'store']);
Route::post('/booking-request-month', [BookingRequestController::class, 'getBookingRequestsByMonth']);
Route::post('/booking-request-price-by-company', [BookingRequestPriceController::class, 'getBookingRequestPriceByCompany']);
Route::get('/booking-request-price-by-booking-request/{code}', [BookingRequestPriceController::class, 'getBookingRequestPriceByBookingRequest']);
Route::post('/booking-create-by-booking-request/{id}', [BookingRequestController::class, 'createBookingfromBookingRequest']);

//Dashboard Routes
Route::get('/ms-companies-by-user/{id}', [MsCompanyController::class, 'getCompanyByUser']);
Route::get('/ms-companies-by-user-2/{id}', [MsCompanyController::class, 'getCompanyByUser2']);
Route::get('/mb-companies-by-user/{id}', [MbCompanyController::class, 'getCompanyByUser']);

//Regions
Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{id}', [RegionController::class, 'show']);

//User Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/generate-reset-token', [ForgotPasswordController::class, 'generateToken']);
Route::post('/forgot-password/{token}', [ForgotPasswordController::class, 'sendResetEmail']);
Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset']);
Route::post('/users-by-service', [AuthController::class, 'getUsersByService']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', [MbBookingController::class, 'test']);

// Check Coupons
Route::post('/check-coupon', [CouponController::class, 'checkCoupon']);
Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

/**
 * End Public Routes
 */


