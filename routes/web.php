<?php

use App\Http\Controllers\Frontend\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Auth\SocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);
Route::get('/', function () {
    return abort(404);
});
Route::get('/email', function () {
    return view('email-template');
});

//users
Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/signup', 'signup')->name('register');
    Route::get('/auth/login', 'login')->name('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Socialite 

Route::controller(\App\Http\Controllers\Auth\SocialiteController::class)->group(function () {
    Route::middleware(['cors'])->group(function () {
        Route::get('/auth/redirects/{provider}/{guest}', 'redirectToProvider');
        Route::get('/auth/redirect/{provider}/callback-url', 'hadleProviderCallback');
    });
});

/* // Socialite 
Route::controller(\App\Http\Controllers\Auth\SocialiteCustomerController::class)->group(function () {
    Route::middleware(['cors'])->group(function () {
        Route::get('/auth/customer/redirect/{provider}', 'redirectToProvider');
        Route::get('/auth/customer/redirect/{provider}/callback-url', 'hadleProviderCallback');
    });
});

// Socialite 
Route::controller(\App\Http\Controllers\Auth\SocialiteMitraController::class)->group(function () {
    Route::middleware(['cors'])->group(function () {
        Route::get('/auth/mitra/redirect/{provider}', 'redirectToProvider');
        Route::get('/auth/mitra/redirect/{provider}/callback-url', 'hadleProviderCallback');
    });
}); */


