<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CmsPageController;
use App\Http\Controllers\Api\ConversionController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\UserHomeController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\UserBankAccountController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\TicketController;
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
    //homepage
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api']], function () {

    Route::get('/profile', [UserAuthController::class, 'userProfile'])->name('profile');
    Route::post('/editProfile', [UserAuthController::class, 'editProfile'])->name('editProfile');
    Route::post('/me', [UserAuthController::class, 'me'])->name('me');
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::delete('/delete-account', [UserAuthController::class, 'deleteAccount'])->name('deleteAccount');

    //CampaignController
    Route::post('/create-campaign', [CampaignController::class, 'createUserCampaign'])->name('create.campaign');
    //WalletController
    Route::get('/wallet', [WalletController::class, 'wallet'])->name('user.wallet');
    Route::get('/conversion', [ConversionController::class, 'myConversion'])->name('user.conversion');

    Route::group(['prefix' => 'bank-accounts'], function () {
    // Bank Account Routes - Fix the prefix to avoid conflicts
        Route::get('index', [UserBankAccountController::class, 'index']);
        Route::post('store', [UserBankAccountController::class, 'store']);
        Route::get('show/{bankAccount}', [UserBankAccountController::class, 'show']);
        Route::put('update/{bankAccount}', [UserBankAccountController::class, 'update']);
        Route::delete('destroy/{bankAccount}', [UserBankAccountController::class, 'destroy']);
        Route::post('setDefault/{bankAccount}/', [UserBankAccountController::class, 'setDefault']);
    });
    // Withdrawal Routes - Fix the prefix to avoid conflicts
    Route::group(['prefix' => 'withdrawal'], function () {
        Route::get('index', [WithdrawalController::class, 'index']);
        Route::post('store', [WithdrawalController::class, 'store']);
        Route::put('update/{withdrawal}', [WithdrawalController::class, 'update']);
        Route::delete('destroy/{withdrawal}', [WithdrawalController::class, 'destroy']);
    });
});

//CampaignController
Route::group(['prefix' => 'campaign', 'middleware' => ['auth:api']], function () {
    Route::post('/create', [CampaignController::class, 'createUserCampaign'])->name('create.campaign');
    Route::get('/user', [CampaignController::class, 'userCampaignAll'])->name('campaign.user.all');
});


Route::get('/homepage', [UserHomeController::class, 'homepage'])->name('homepage');
Route::get('/homepage3', [UserHomeController::class, 'homepage3'])->name('homepage3');


Route::get('/cms-page', [CmsPageController::class, 'getCmsPage'])->name('cmsPage');
Route::get('/faq', [FaqController::class, 'getFaq'])->name('faq');
//BrandController
Route::get('/brand-list/{category?}', [BrandController::class, 'brandList'])->name('brandList');
Route::get('/brand-details/{id}', [BrandController::class, 'brandDetails'])->name('brand-details');

//Deals
Route::get('/deal-list/{category?}', [DealController::class, 'dealList'])->name('dealList');
Route::get('/deal-details/{id}', [DealController::class, 'dealDetails'])->name('deal-details');

//Coupon
Route::get('/coupon-list/{category?}', [CouponController::class, 'couponList'])->name('couponList');
Route::get('/coupon-details/{id}', [CouponController::class, 'couponDetails'])->name('coupon-details');
Route::get('/quicks-webhook', [App\Http\Controllers\PostBackController::class, 'vCommissionpostBack']);

Route::group(['prefix' => 'referrals', 'middleware' => ['auth:api']], function () {
    Route::get('/generate-code', [ReferralController::class, 'generateReferralCode']);
    Route::post('/apply-code', [ReferralController::class, 'applyReferralCode']);
    Route::get('/stats', [ReferralController::class, 'getReferralStats']);
    Route::post('/redeem', [ReferralController::class, 'referralToWallet']);

});

Route::get('/referral-download', [ReferralController::class, 'refferalHandle']);
Route::get('/search-brand', [SearchController::class, 'searchBrand']);
Route::get('/search-category', [SearchController::class, 'homeCategory']);

Route::middleware(['auth:api'])->group(function () {
    // ... existing routes ...
    
    // Ticket Routes
    
    Route::group(['prefix' => 'ticket', 'middleware' => ['auth:api']], function () {
        Route::get('/', [TicketController::class, 'index']);
        Route::post('/', [TicketController::class, 'store']);
        Route::get('/{id}', [TicketController::class, 'show']);
        Route::put('/{id}', [TicketController::class, 'update']);
        Route::delete('/{id}', [TicketController::class, 'destroy']);
        Route::post('/{id}/status', [TicketController::class, 'updateStatus']);
        Route::get('/user/tickets', [TicketController::class, 'userTickets']);
    });


    Route::get('/user-conversion-report', [App\Http\Controllers\Api\ReportController::class, 'userConversionReport'])->middleware('auth:api');

});

