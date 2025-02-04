<?php

use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserHomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('check-existed-phone', [UserAuthController::class, 'checkNumberExist'])->name('checkNumberExist');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register');
    Route::post('/verify_otp', [UserAuthController::class, 'verifyOtp'])->name('verifyOtp');
    Route::post('login', [UserAuthController::class, 'login'])->name('user.login');

// Auth Check
    Route::post('/refresh', [UserAuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');


});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function () {
        Route::get('/profile', [UserAuthController::class, 'userProfile'])->name('profile');
        Route::post('/editProfile', [UserAuthController::class, 'editProfile'])->name('editProfile');
        Route::post('/me', [UserAuthController::class, 'me'])->name('me');
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

});
//homepage
Route::get('/homepage', [UserHomeController::class, 'homepage'])->name('homepage');

