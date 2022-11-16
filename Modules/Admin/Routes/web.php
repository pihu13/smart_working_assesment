<?php

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

Route::get('/', [Modules\Admin\Http\Controllers\AdminController::class, 'login']);
Route::get('/admin', [Modules\Admin\Http\Controllers\AdminController::class, 'login']);


Route::get('/cmspages/{slug}', [Modules\Admin\Http\Controllers\PageController::class, 'cmspages'])->name('front.cmspages');

Route::prefix('admin')->group(function() {
    Route::get('/', [Modules\Admin\Http\Controllers\AdminController::class, 'login']);
    Route::get('/login', [Modules\Admin\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
    Route::get('/enquiry', [Modules\Admin\Http\Controllers\AdminController::class, 'enquiry'])->name('admin.enquiry');
    Route::post('/enquiry-post', [Modules\Admin\Http\Controllers\AdminController::class, 'enquiry_post'])->name('admin.enquiry.post');
    Route::post('/login-post', [Modules\Admin\Http\Controllers\AdminController::class, 'admin_login'])->name('admin.login.post');
    Route::get('/admin-forgot-password', [Modules\Admin\Http\Controllers\AdminController::class, 'forgotPassword'])->name('admin.forgot.password');
    Route::post('/admin-forgot-password-post', [Modules\Admin\Http\Controllers\AdminController::class, 'forgotUpdate'])->name('admin.forgotupdate.post');
});


Route::group(['middleware' => 'Isadmin'], function () {
    Route::prefix('admin')->group(function() {
        // === ADMIN ===
        Route::get('/', [Modules\Admin\Http\Controllers\AdminController::class, 'index']);
        Route::get('/dashboard', [Modules\Admin\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin-logout', [Modules\Admin\Http\Controllers\AdminController::class, 'admin_logout'])->name('admin.logout');

        Route::get('/edit-admin', [Modules\Admin\Http\Controllers\AdminController::class, 'editAdmin'])->name('admin.edit.admin');
        Route::post('/update-admin', [Modules\Admin\Http\Controllers\AdminController::class, 'updateAdmin'])->name('admin.update.admin');
        Route::post('/admin-change-password', [Modules\Admin\Http\Controllers\AdminController::class, 'adminChangePassword'])->name('admin.change.password');

        // === SystemSettingController ===
        Route::get('/system-settings', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'index'])->name('admin.system.settings');
        Route::get('/add-system-setting', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'create'])->name('admin.add.system.setting');
        Route::post('/add-system-setting-post', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'store'])->name('admin.add.system.setting.post');
        Route::get('/edit-system-setting/{slug}', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'edit'])->name('admin.edit.system.setting');
        Route::post('/edit-system-setting-post', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'update'])->name('admin.edit.system.setting.post');
        Route::get('/view-system-setting/{slug}', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'show'])->name('admin.view.system.setting');
        Route::get('/delete-system-setting/{slug}', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'destroy'])->name('admin.delete.system.setting');
        Route::get('option-status/{slug}', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'OptionStatus'])->name('admin.option.status');
        Route::get('/site-logo', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'indexSiteLogo'])->name('admin.site.logo');
        Route::post('/edit-site-logo', [Modules\Admin\Http\Controllers\SystemSettingController::class, 'updateSiteLogo'])->name('admin.edit.site.logo');

        // === SubAdminController ===
        Route::get('/sub-admins', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminList'])->name('admin.sub.admins');
        Route::get('/add-sub-admin', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminAdd'])->name('admin.add.sub.admin');
        Route::post('/add-sub-admin-post', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminAddStore'])->name('admin.add.sub.admin.post');
        Route::get('/edit-sub-admin/{slug}', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminEdit'])->name('admin.edit.sub.admin');
        Route::post('/edit-sub-admin-post', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminUpdate'])->name('admin.edit.sub.admin.post');
        Route::get('/view-sub-admin/{slug}', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminView'])->name('admin.view.sub.admin');
        Route::post('/delete-sub-admin', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminDestroy'])->name('admin.delete.sub.admin');
        Route::post('/sub-admin-status', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminStatus'])->name('admin.sub.admin.status');
        //Route::get('export-sub-admin', [Modules\Admin\Http\Controllers\SubAdminController::class, 'exportsubAdmin'])->name('export-sub-admin');
        Route::post('/sub-admin-change-password', [Modules\Admin\Http\Controllers\SubAdminController::class, 'subAdminChangePass'])->name('admin.sub.admin.change.password');

        // === EmailTemplateController ===
        Route::get('/emails', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'index'])->name('admin.emails');
        Route::get('/add-email', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'create'])->name('admin.add.email');
        Route::post('/add-email-post', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'store'])->name('admin.add.email.post');
        Route::get('/edit-email/{slug}', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'edit'])->name('admin.edit.email');
        Route::post('/edit-email-post', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'update'])->name('admin.edit.email.post');
        Route::get('/view-email/{slug}', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'show'])->name('admin.view.email');
        Route::get('/delete-email/{slug}', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'destroy'])->name('admin.delete.email');
        Route::get('/email-status/{slug}', [Modules\Admin\Http\Controllers\EmailTemplateController::class, 'emailStatus'])->name('admin.email.status');

        // === Customer Manager ===
        Route::get('/customers-list', [Modules\Admin\Http\Controllers\CustomerController::class, 'index'])->name('admin.customers.list');
        Route::get('/add-customer', [Modules\Admin\Http\Controllers\CustomerController::class, 'create'])->name('admin.add.customer');
        Route::post('/add-customer-post', [Modules\Admin\Http\Controllers\CustomerController::class, 'store'])->name('admin.add.customer.post');
        Route::get('/edit-customer/{slug}', [Modules\Admin\Http\Controllers\CustomerController::class, 'edit'])->name('admin.edit.customer');
        Route::post('/edit-customer-post', [Modules\Admin\Http\Controllers\CustomerController::class, 'update'])->name('admin.edit.customer.post');
        Route::get('/view-customer/{slug}', [Modules\Admin\Http\Controllers\CustomerController::class, 'show'])->name('admin.view.customer');
        Route::post('/delete-customer', [Modules\Admin\Http\Controllers\CustomerController::class, 'destroy'])->name('admin.delete.customer');
        Route::post('/customer-status', [Modules\Admin\Http\Controllers\CustomerController::class, 'customerStatus'])->name('admin.customer.status');
        //Route::get('export-user', "UserController@exportUser")->name('export-user');
        Route::post('/customer-change-password', [Modules\Admin\Http\Controllers\CustomerController::class, 'customerChangePass'])->name('admin.customer.change.password');
        
        Route::get('/softdelete-customers', [Modules\Admin\Http\Controllers\CustomerController::class, 'SoftCustomerList'])->name('admin.softdelete.customers');
        Route::get('/softdelete-view-customer/{slug}', [Modules\Admin\Http\Controllers\CustomerController::class, 'softDeleteCustomerView'])->name('admin.softdelete.view.customer');
        Route::post('/restore-customer', [Modules\Admin\Http\Controllers\CustomerController::class, 'restoreCustomer'])->name('admin.restore.customer');
        Route::post('/permanently-delete-customer', [Modules\Admin\Http\Controllers\CustomerController::class, 'destroyPermanently'])->name('admin.permanently.delete.customer');

        Route::get('/cancer-list', [Modules\Admin\Http\Controllers\CancerController::class, 'index'])->name('admin.cancers.list');
        Route::get('/add-cancer', [Modules\Admin\Http\Controllers\CancerController::class, 'create'])->name('admin.add.cancer');
        Route::post('/add-cancer-post', [Modules\Admin\Http\Controllers\CancerController::class, 'store'])->name('admin.add.cancer.post');
        Route::get('/edit-cancer/{slug}', [Modules\Admin\Http\Controllers\CancerController::class, 'edit'])->name('admin.edit.cancer');
        Route::post('/edit-cancer-post', [Modules\Admin\Http\Controllers\CancerController::class, 'update'])->name('admin.edit.cancer.post');
        Route::get('/view-cancer/{slug}', [Modules\Admin\Http\Controllers\CancerController::class, 'show'])->name('admin.view.cancer');
        Route::post('/delete-cancer', [Modules\Admin\Http\Controllers\CancerController::class, 'destroy'])->name('admin.delete.cancer');
        Route::post('/customer-cancer', [Modules\Admin\Http\Controllers\CancerController::class, 'customerStatus'])->name('admin.cancer.status');

  
    
        // === EnquiryController ===
        Route::get('/user-enquiries', [Modules\Admin\Http\Controllers\EnquiryController::class, 'index'])->name('admin.user.enquiries');
        Route::get('/view-user-enquiry/{slug}', [Modules\Admin\Http\Controllers\EnquiryController::class, 'show'])->name('admin.view.user.enquiry');
        Route::post('/delete-user-enquiry', [Modules\Admin\Http\Controllers\EnquiryController::class, 'destroy'])->name('admin.delete.user-enquiry');
        Route::get('export-user-enquiry', [Modules\Admin\Http\Controllers\EnquiryController::class, 'exportEnquiry'])->name('admin.export.user.enquiry');



  });
});
