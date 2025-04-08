<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;

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
    return redirect('/');
})->name('setlocale');

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
// Frontend Routes commented to open admin directely
// Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');




Route::group(['middleware' => 'language'], function () {
	// Admin Routes
	Route::prefix('admin')->group(function () {
		Route::get('/login', 					[App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
		Route::post('/login', 					[App\Http\Controllers\Auth\LoginController::class, 'login_go'])->name('login_go');
		Route::get('/logout', 					[App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
		Route::get('forget-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
		Route::post('forget-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
		Route::get('reset-password/{token}', 	[App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
		Route::post('reset-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
		// Admin Authenticated Routes
		Route::group(['middleware' => ['auth']], function () {
			Route::get('/dashboard', 			[App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('dashboard');
			// Profile
			Route::get('/profile', 				[App\Http\Controllers\Admin\UserController::class, 'profile'])->name('profile');
			Route::post('/profile/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'profile_update'])->name('profile.update');

			// User
			Route::prefix('users')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\UserController::class, 'status_update'])->name('users.status_update');
			});

			// Role
			Route::prefix('roles')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');
			});

			// Permission
			Route::prefix('permissions')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\PermissionController::class, 'create'])->name('permissions.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('permissions.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\PermissionController::class, 'edit'])->name('permissions.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('permissions.destroy');
			});

			// Currency
			Route::prefix('currencies')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('currencies.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CurrencyController::class, 'create'])->name('currencies.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CurrencyController::class, 'store'])->name('currencies.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CurrencyController::class, 'edit'])->name('currencies.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CurrencyController::class, 'update'])->name('currencies.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CurrencyController::class, 'destroy'])->name('currencies.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CurrencyController::class, 'status_update'])->name('currencies.status_update');
			});

			// Setting
			Route::prefix('setting')->group(function () {
				Route::get('/file-manager/index', 			 [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('filemanager.index');
				Route::get('/website-setting/edit', 		 [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('website-setting.edit');
				Route::post('/website-setting/update/{id}',  [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('website-setting.update');
			});

			// CMS category
			Route::prefix('cmscategories')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'index'])->name('cmscategories.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'create'])->name('cmscategories.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'store'])->name('cmscategories.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CMSCategoryController::class, 'edit'])->name('cmscategories.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CMSCategoryController::class, 'update'])->name('cmscategories.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CMSCategoryController::class, 'destroy'])->name('cmscategories.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CMSCategoryController::class, 'status_update'])->name('cmscategories.status_update');
			});

			// CMS Pages
			Route::prefix('cmspages')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CMSPageController::class, 'index'])->name('cmspages.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CMSPageController::class, 'create'])->name('cmspages.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CMSPageController::class, 'store'])->name('cmspages.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CMSPageController::class, 'edit'])->name('cmspages.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CMSPageController::class, 'update'])->name('cmspages.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CMSPageController::class, 'destroy'])->name('cmspages.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CMSPageController::class, 'status_update'])->name('cmspages.status_update');
			});

			// Testimonials
			Route::prefix('testimonials')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');
				Route::get('/create', 			[App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
				Route::post('/store', 			[App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\TestimonialController::class, 'edit'])->name('testimonials.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\TestimonialController::class, 'update'])->name('testimonials.update');
				Route::post('/destroy', 		[App\Http\Controllers\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\TestimonialController::class, 'status_update'])->name('testimonials.status_update');
			});

				Route::prefix('sms')->group(function () {
					Route::get('/index', [App\Http\Controllers\Admin\ConfigurationController::class, 'smsManager'])->name('sms.index');
					Route::get('/create',[App\Http\Controllers\Admin\ConfigurationController::class, 'smsCreate'])->name('sms.create');
					Route::post('/store',[App\Http\Controllers\Admin\ConfigurationController::class, 'smsStore'])->name('sms.store');
				});

				Route::prefix('affiliate')->group(function () {
					Route::get('/index', [App\Http\Controllers\Admin\AffilateIntegrationController::class, 'index'])->name('affiliate.index');
					Route::get('/create',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'create'])->name('affiliate.create');
					Route::post('/store',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'store'])->name('affiliate.store');
					Route::get('/edit/{id}',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'edit'])->name('affiliate.edit');
					Route::post('/update/{id}',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'update'])->name('affiliate.update');
					Route::post('/destroy', [App\Http\Controllers\Admin\AffilateIntegrationController::class, 'destroy'])->name('affiliate.destroy');
				});


				Route::prefix('affiliate')->group(function () {
					Route::get('/index', [App\Http\Controllers\Admin\AffilateIntegrationController::class, 'index'])->name('affiliate.index');
					Route::get('/create',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'create'])->name('affiliate.create');
					Route::post('/store',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'store'])->name('affiliate.store');
					Route::get('/edit/{id}',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'edit'])->name('affiliate.edit');
					Route::post('/update/{id}',[App\Http\Controllers\Admin\AffilateIntegrationController::class, 'update'])->name('affiliate.update');
					Route::post('/destroy', [App\Http\Controllers\Admin\AffilateIntegrationController::class, 'destroy'])->name('affiliate.destroy');
				});


					// User
			Route::prefix('banner')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\BannersController::class, 'index'])->name('banner.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\BannersController::class, 'create'])->name('banner.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\BannersController::class, 'store'])->name('banner.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\BannersController::class, 'edit'])->name('banner.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\BannersController::class, 'update'])->name('banner.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\BannersController::class, 'destroy'])->name('banner.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\BannersController::class, 'status_update'])->name('banner.status_update');
			});
			Route::prefix('category')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('category.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('category.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('category.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('category.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CategoryController::class, 'status_update'])->name('category.status_update');
				Route::get('/status_update_custom', 	[App\Http\Controllers\Admin\CategoryController::class, 'status_update_custom'])->name('category.status_update_custom');

			});

			Route::prefix('financial-category')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\FinancialCategoryController::class, 'index'])->name('financial-category.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\FinancialCategoryController::class, 'create'])->name('financial-category.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\FinancialCategoryController::class, 'store'])->name('financial-category.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\FinancialCategoryController::class, 'edit'])->name('financial-category.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\FinancialCategoryController::class, 'update'])->name('financial-category.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\FinancialCategoryController::class, 'destroy'])->name('financial-category.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\FinancialCategoryController::class, 'status_update'])->name('financial-category.status_update');
				Route::get('/status_update_custom', 	[App\Http\Controllers\Admin\FinancialCategoryController::class, 'status_update_custom'])->name('financial-category.status_update_custom');

			});

			Route::prefix('financial-offer')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\FinancialOfferController::class, 'index'])->name('financial-offer.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\FinancialOfferController::class, 'create'])->name('financial-offer.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\FinancialOfferController::class, 'store'])->name('financial-offer.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\FinancialOfferController::class, 'edit'])->name('financial-offer.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\FinancialOfferController::class, 'update'])->name('financial-offer.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\FinancialOfferController::class, 'destroy'])->name('financial-offer.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\FinancialOfferController::class, 'status_update'])->name('financial-offer.status_update');
				Route::get('/status_update_custom', 	[App\Http\Controllers\Admin\FinancialOfferController::class, 'status_update_custom'])->name('financial-offer.status_update_custom');

			});


			Route::prefix('brand')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brand.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\BrandController::class, 'create'])->name('brand.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\BrandController::class, 'store'])->name('brand.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('brand.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\BrandController::class, 'update'])->name('brand.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('brand.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\BrandController::class, 'status_update'])->name('brand.status_update');
				Route::get('/status_update_custom',[App\Http\Controllers\Admin\BrandController::class, 'status_update_custom'])->name('brand.status_update_custom');

			});

			Route::prefix('product')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\ProductController::class, 'index'])->name('product.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\ProductController::class, 'create'])->name('product.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\ProductController::class, 'store'])->name('product.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('product.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\ProductController::class, 'update'])->name('product.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('product.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\ProductController::class, 'status_update'])->name('product.status_update');
				Route::get('/status_update_custom',[App\Http\Controllers\Admin\ProductController::class, 'status_update_custom'])->name('product.status_update_custom');

			});

			Route::prefix('campaign')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CampaignController::class, 'index'])->name('campaign.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CampaignController::class, 'create'])->name('campaign.create');
			
				Route::post('/store', 			[App\Http\Controllers\Admin\CampaignController::class, 'store'])->name('campaign.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CampaignController::class, 'edit'])->name('campaign.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CampaignController::class, 'update'])->name('campaign.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CampaignController::class, 'destroy'])->name('campaign.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CampaignController::class, 'status_update'])->name('campaign.status_update');
				Route::get('/status_update_custom', 	[App\Http\Controllers\Admin\CampaignController::class, 'status_update_custom'])->name('campaign.status_update_custom');

			});


			Route::prefix('push-notification')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\PushNotificationController::class, 'index'])->name('pushNotification.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\PushNotificationController::class, 'create'])->name('pushNotification.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\PushNotificationController::class, 'store'])->name('pushNotification.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\PushNotificationController::class, 'edit'])->name('pushNotification.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\PushNotificationController::class, 'update'])->name('pushNotification.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\PushNotificationController::class, 'destroy'])->name('pushNotification.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\PushNotificationController::class, 'status_update'])->name('pushNotification.status_update');
				Route::get('/status_update_custom', 	[App\Http\Controllers\Admin\PushNotificationController::class, 'status_update_custom'])->name('pushNotification.status_update_custom');

			});
			
			Route::prefix('report')->group(function () {
				Route::get('/index',[App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report.index');
				Route::get('/user',[App\Http\Controllers\Admin\ReportController::class, 'userExport'])->name('report.user');
				Route::get('/click',[App\Http\Controllers\Admin\ReportController::class, 'clickExport'])->name('report.click');


			});

			Route::prefix('coupon')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupon.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupon.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupon.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupon.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupon.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupon.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CouponController::class, 'status_update'])->name('coupon.status_update');
				Route::get('/status_update_custom',[App\Http\Controllers\Admin\CouponController::class, 'status_update_custom'])->name('coupon.status_update_custom');

			});


			Route::prefix('deal')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\DealsController::class, 'index'])->name('deal.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\DealsController::class, 'create'])->name('deal.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\DealsController::class, 'store'])->name('deal.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\DealsController::class, 'edit'])->name('deal.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\DealsController::class, 'update'])->name('deal.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\DealsController::class, 'destroy'])->name('deal.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\DealsController::class, 'status_update'])->name('deal.status_update');
				Route::get('/status_update_custom',[App\Http\Controllers\Admin\DealsController::class, 'status_update_custom'])->name('deal.status_update_custom');

			});

			Route::prefix('wallet')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\WalletController::class, 'index'])->name('wallet.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\WalletController::class, 'create'])->name('wallet.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\WalletController::class, 'store'])->name('wallet.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\WalletController::class, 'edit'])->name('wallet.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\WalletController::class, 'update'])->name('wallet.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\WalletController::class, 'destroy'])->name('wallet.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\WalletController::class, 'status_update'])->name('wallet.status_update');
				Route::get('/status_update_custom',[App\Http\Controllers\Admin\WalletController::class, 'status_update_custom'])->name('wallet.status_update_custom');

			});

			Route::prefix('withdrawal')->group(function () {
					Route::get('/index', 			[App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawal.index');
					Route::get('/status_update', 	[App\Http\Controllers\Admin\WithdrawalController::class, 'statusUpdate'])->name('withdrawal.status_update');
			});

			Route::prefix('conversion')->group(function () {
				Route::get('/view',[App\Http\Controllers\Admin\ConversionController::class, 'view'])->name('conversion.view');

			});

			Route::prefix('referral')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('referral.index');
				Route::get('/reject', 			[App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('referral.reject');
				Route::get('/change-status', 	 [App\Http\Controllers\Admin\ReferralController::class, 'changeStatus'])->name('referral.change-status');

			});

		});
	});

	Route::get('/',[App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
	Route::get('/page/{page}',[App\Http\Controllers\HomeController::class, 'getpage'])->name('home.page');


});
Route::get('/affiliate-network',[App\Http\Controllers\Admin\ClockingUrlController::class, 'targetUrl']);
Route::get('/quicks-webhook', [App\Http\Controllers\PostBackController::class, 'vCommissionpostBack']);