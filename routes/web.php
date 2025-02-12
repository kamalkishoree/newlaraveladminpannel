<?php

use Illuminate\Support\Facades\Route; 

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
    return redirect('/admin/login');
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

			Route::prefix('campaign')->group(function () {
				Route::get('/view', [App\Http\Controllers\Admin\CampaignController::class, 'view'])->name('campaign.view');
			});
		});
	});

});
