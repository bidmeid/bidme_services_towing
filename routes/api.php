<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthCustomerController;
use App\Http\Controllers\AuthMitraController;
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
        Route::post('/auth/mitra/signup', 'signup')->name('api.signup');
        Route::post('/auth/mitra/sigin', 'sigin')->name('sigin');
    });

//AUTH MITRA
    Route::controller(AuthMitraController::class)->group(function () {
        Route::post('/auth/mitra/signup', 'signup')->name('api.signup');
        Route::post('/auth/mitra/sigin', 'sigin')->name('sigin');
    });
});


Route::group(['middleware' => 'auth:sanctum',], function () {
	Route::get('/user', function (Request $request) {
			return $request->user();
		});
		
	Route::middleware(['isCustomer'])->group(function () {
		Route::post('/postOrder', [Api\PostOrder::class, 'index']);
		Route::post('/bidding', [Api\Bidding::class, 'index']);
		Route::post('/myOrder', [Api\PostOrder::class, 'myOrder']);
		Route::post('/invoice', [Api\Invoice::class, 'index']);
	});
	
	Route::middleware(['isMitra'])->group(function () {
		Route::post('/postOrder', [Api\PostOrder::class, 'index']);
		Route::post('/bidding', [Api\Bidding::class, 'index']);
		Route::post('/myOrder', [Api\PostOrder::class, 'myOrder']);
		Route::post('/invoice', [Api\Invoice::class, 'index']);
	});
	
    Route::post('/auth/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::post('/requestCost', [Api\RequestCost::class, 'index']);


