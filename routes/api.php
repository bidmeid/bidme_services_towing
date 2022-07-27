<?php


use App\Http\Controllers\API\AuthController;
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

// Route::group(['middleware' => ['cors']], function () {
//     Route::controller(AuthController::class)->group(function () {
//         Route::post('/auth/signup', 'signup')->name('api.signup');
//         Route::post('/auth/sigin', 'sigin')->name('sigin');
//     });
// });


Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/signup', 'signup')->name('api.signup');
    Route::post('/auth/sigin', 'sigin')->name('api.sigin');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
	
	//Route::post('/postOrder', [Api\PostOrder::class, 'index']);
	
    Route::post('/auth/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::post('/requestCost', [Api\RequestCost::class, 'index']);
Route::post('/postOrder', [Api\PostOrder::class, 'index']);
Route::post('/invoice', [Api\Invoice::class, 'index']);
Route::post('/bidding', [Api\Bidding::class, 'index']);
