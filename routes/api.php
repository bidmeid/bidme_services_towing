<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthCustomerController;
use App\Http\Controllers\Auth\AuthMitraController;
use App\Http\Controllers\Auth\AuthDriverController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => ['cors']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/signup', 'signup')->name('api.signup');
        Route::post('/auth/sigin', 'sigin')->name('sigin');
    });

//AUTH CUSTOMER
    Route::controller(AuthCustomerController::class)->group(function () {
        Route::post('/auth/customer/signup', 'signup')->name('api.signup');
        Route::post('/auth/customer/sigin', 'sigin')->name('sigin');
    });

//AUTH MITRA
    Route::controller(AuthMitraController::class)->group(function () {
        Route::post('/auth/mitra/signup', 'signup')->name('api.signup');
        Route::post('/auth/mitra/sigin', 'sigin')->name('sigin');
    });

//AUTH DRIVER
    Route::controller(AuthDriverController::class)->group(function () {
        Route::post('/auth/driver/sigin', 'sigin')->name('sigin');
    });
});


Route::group(['middleware' => 'auth:sanctum',], function () {
	Route::get('/user', function (Request $request) {
		$user = $request->user();
		if ($user->tokenCan('customer')) {
			$user->role = 'customer';
		}elseif($user->tokenCan('mitra')){
			$user->role = 'mitra';
		}elseif($user->tokenCan('driver')){
			$user->role = 'driver';
		};
			return $user;
	});
		
	Route::group(['middleware' => ['auth:sanctum','role:customer']], function() {
		Route::post('/postOrder', [Api\PostOrder::class, 'index']);
		Route::post('/cancelOrder', [Api\PostOrder::class, 'cancelOrder']);
		Route::post('/bidding', [Api\Bidding::class, 'index']);
		Route::post('/myOrder', [Api\PostOrder::class, 'myOrder']);
		Route::get('/getOrderById', [Api\PostOrder::class, 'getOrderById']); 
		Route::post('/checkOut', [Api\PostOrder::class, 'checkOut']);
		Route::post('/couponVoucher', [Api\PostOrder::class, 'couponVoucher']);
		Route::post('/invoice', [Api\Invoice::class, 'index']);
		Route::post('/paymentStatus', [Api\Invoice::class, 'paymentStatus']);
		Route::post('/updateAccount', [Api\UsersCustomer::class, 'update_account']);
		Route::get('/customerNotif', [Api\CustomerNotif::class, 'index']);
		Route::get('/tracking', [Api\Tracking::class, 'index']);
		Route::get('/driverInfo', [Api\Tracking::class, 'driverInfo']);
		Route::post('/finishOrder', [Api\PostOrder::class, 'finishOrder']);
		Route::post('/payment-handler', [\App\Http\Controllers\Midtrans\MidtransController::class, 'payment_handler']);
	});
	
	Route::group(['middleware' => ['auth:sanctum','role:mitra']], function() {
		Route::post('/mitra/postBidding', [Api\Mitra\PostBidding::class, 'index']); 
		Route::post('/mitra/getBidById', [Api\Mitra\PostBidding::class, 'getBidById']); 
		Route::post('/mitra/cancelBidding', [Api\Mitra\PostBidding::class, 'cancelBidding']); 
		Route::get('/mitra/getOrder', [Api\Mitra\Order::class, 'index']); 
		Route::get('/mitra/getOrderById', [Api\Mitra\Order::class, 'getOrderById']); 
		Route::get('/mitra/getInvoiceById', [Api\Mitra\Order::class, 'getInvoiceById']); 
		Route::get('/mitra/myBidding', [Api\Mitra\PostBidding::class, 'myBidding']);
		Route::post('/mitra/updateAccount', [Api\Mitra\UsersMitra::class, 'update_account']);
		Route::post('/mitra/updatePassword', [Api\Mitra\UsersMitra::class, 'update_password']);
		Route::post('/mitra/setNotifikasi', [Api\Mitra\UsersMitra::class, 'setting_notifikasi']);
		Route::get('/mitra/mitraNotif', [Api\Mitra\MitraNotif::class, 'index']);
		Route::post('/mitra/report', [Api\Mitra\Report::class, 'index']);
		
		//DRIVER		
		Route::post('/mitra/listDriver', [Api\Mitra\Driver::class, 'index']);		
		Route::post('/mitra/registrationDriver', [AuthDriverController::class, 'signup']);
		Route::post('/mitra/getDriverById', [Api\Mitra\Driver::class, 'getDriverById']);		
		Route::post('/mitra/updateDriver', [Api\Mitra\Driver::class, 'updateDriver']);		
		Route::delete('/mitra/deleteDriver', [Api\Mitra\Driver::class, 'deleteDriver']);	

		//UNIT
		Route::post('/mitra/listTowing', [Api\Mitra\UnitTowing::class, 'index']);		
		Route::post('/mitra/createTowing', [Api\Mitra\UnitTowing::class, 'createTowing']);		
		Route::post('/mitra/getTowingById', [Api\Mitra\UnitTowing::class, 'getTowingById']);		
		Route::post('/mitra/updateTowing', [Api\Mitra\UnitTowing::class, 'updateTowing']);		
		Route::delete('/mitra/deleteTowing', [Api\Mitra\UnitTowing::class, 'deleteTowing']);
		
		Route::get('/mitra/myOrder', [Api\Mitra\Order::class, 'myOrder']);
		Route::post('/mitra/postDriver', [Api\Mitra\Order::class, 'postDriver']);
	});
	
	Route::group(['middleware' => ['auth:sanctum','role:driver']], function() {
		Route::get('/driver/getOrder', [Api\Driver\Tracking::class, 'index']); 
		Route::post('/driver/postLatLng', [Api\Driver\Tracking::class, 'postLatLng']); 
		Route::post('/driver/lastLatLng', [Api\Driver\Tracking::class, 'lastLatLng']); 
		Route::post('/driver/updateAccount', [Api\Driver\UsersDriver::class, 'updateAccount']);
		Route::post('/driver/finishOrder', [Api\Driver\Tracking::class, 'finishOrder']);
	});
	
    Route::post('/auth/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::post('/requestCost', [Api\RequestCost::class, 'index']);




