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
		if ($user->tokenCan('role:customer')) {
			$user->role = 'customer';
		}elseif($user->tokenCan('role:mitra')){
			$user->role = 'mitra';
		}elseif($user->tokenCan('role:driver')){
			$user->role = 'driver';
		};
			return $user;
	});
		
	Route::group(['middleware' => ['auth:sanctum','role:customer']], function() {
		Route::post('/postOrder', [Api\PostOrder::class, 'index']);
		Route::post('/cancelOrder', [Api\PostOrder::class, 'cancelOrder']);
		Route::post('/bidding', [Api\Bidding::class, 'index']);
		Route::post('/myOrder', [Api\PostOrder::class, 'myOrder']);
		Route::post('/checkOut', [Api\PostOrder::class, 'checkOut']);
		Route::post('/couponVoucher', [Api\PostOrder::class, 'couponVoucher']);
		Route::post('/invoice', [Api\Invoice::class, 'index']);
		Route::post('/updateAccount', [Api\UsersCustomer::class, 'update_account']);
	});
	
	Route::group(['middleware' => ['auth:sanctum','role:mitra']], function() {
		Route::post('/mitra/postBidding', [Api\Mitra\PostBidding::class, 'index']); 
		Route::post('/mitra/cancelBidding', [Api\Mitra\PostBidding::class, 'cancelBidding']); 
		Route::get('/mitra/getOrder', [Api\Mitra\Order::class, 'index']); 
		Route::get('/mitra/getOrderById', [Api\Mitra\Order::class, 'getOrderById']); 
		Route::get('/mitra/myBidding', [Api\Mitra\PostBidding::class, 'myBidding']);
		Route::post('/mitra/updateAccount', [Api\Mitra\UsersMitra::class, 'update_account']);
		
		//DRIVER		
		Route::post('/mitra/registrationDriver', [AuthDriverController::class, 'signup']);		
		Route::post('/mitra/updateDriver', [Api\Mitra\Driver::class, 'update']);		
		Route::delete('/mitra/deleteDriver', [Api\Mitra\Driver::class, 'delete']);		
	});
	
	Route::group(['middleware' => ['auth:sanctum','role:driver']], function() {
		Route::post('/mitra/tracking', [Api\Mitra\tracking::class, 'cancelOrder']); 
		Route::post('/driver/updateAccount', [Api\Driver\UsersDriver::class, 'update_account']);	
	});
	
    Route::post('/auth/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::post('/requestCost', [Api\RequestCost::class, 'index']);


