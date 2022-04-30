<?php

use App\Http\Controllers\Frontend\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/signup', 'signup')->name('signup');
    Route::get('/auth/sigin', 'sigin')->name('sigin');
});

Auth::routes();

// Socialite 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::controller(SocialiteController::class)->group(function () {
    Route::get('/auth/redirect/{provider}', 'redirectToProvider');
    Route::get('/auth/redirect/{provider}/callback-url', 'hadleProviderCallback');
});
