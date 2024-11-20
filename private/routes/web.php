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

// Route::get('/fixmemberid', 'HomeController@fixmemberid');

Route::get('/', 'HomeController@index');
Route::get('/products/all_json', 'ProductsController@all_json');
Route::get('/products/all_json_for_so', 'ProductsController@all_json_for_so');
Route::get('/products/by_default_code/{default_code}', 'ProductsController@by_default_code');
Route::get('/products', 'ProductsController@index');
Route::get('/products/category/{slug}', 'ProductsController@category');
Route::get('/products/category/{slug}/{slug_sub}', 'ProductsController@category');
Route::get('/products/{slug}', 'ProductsController@details');
Route::get('/products/review/{slug}', 'ProductsController@reviewDetail');
// Route::post('/products/review/submit', 'ProductsController@reviewSubmit');

// Route::get('/search', ['ProductsController@search', 'xssPrevention']);
Route::group(['middleware' => ['XSS']], function () {
    Route::get('/search', 'ProductsController@search');
    Route::post('/products/review/submit', 'ProductsController@reviewSubmit');
    Route::post('/member/register', 'MemberController@register_process');
});

/**
 * Rate Limiter Middleware 10x / 1 menit
 */
// Route::group(['middleware' => ['GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware:2,10', 'bindings']], function () {
Route::group(['middleware' => ['throttle:10,1', 'bindings']], function () {
    Route::post('/member/reset-password', 'MemberController@reset_password_process');
    Route::post('/member/register', 'MemberController@register_process');
});

Route::get('/products/warranty/{category_id}', 'ProductsController@warranty');
Route::get('/products/refspec/{product_id}', 'Backend\ProductController@refspec');

Route::get('/email-layout', 'WarrantyController@email_layout');

Route::get('/warranty', 'WarrantyController@index');
Route::get('/warranty/registration', 'WarrantyController@register');
Route::post('/warranty/registration', 'WarrantyController@register_progress');
Route::post('/warranty/registration-spg', 'Backend\SPGController@register_progress');
Route::get('/warranty/check-serial', 'WarrantyController@check_serial');
Route::post('/warranty/check-kode-voucher', 'WarrantyController@check_kode_voucher');
Route::get('/warranty/registration-success', 'WarrantyController@register_success');
Route::get('/request/installation/{warranty_no}', 'WarrantyController@request_installation');
Route::post('/request/installation/{warranty_no}', 'WarrantyController@request_installation_process');
Route::get('/warranty/info/{warranty_no}', 'WarrantyController@warranty_info');

Route::get('/testmail', 'WarrantyController@mail_specialvoucher');

Route::get('/warranty/{productCode}/{serialNumber}', 'WarrantyController@check_warranty');

Route::get('/warranty/get-product', 'WarrantyController@get_product');
Route::get('/warranty/{productCode}', 'WarrantyController@check_warranty');
Route::get('/service', 'ServiceController@index');

Route::get('/pages/{slug}', 'PagesController@view');
Route::get('/about', 'PagesController@about');
Route::get('/distributor', 'PagesController@distributor');
Route::get('/support', 'PagesController@support');
Route::get('/brochure', 'PagesController@brochure');
Route::get('/tradein', 'PagesController@tradein');
Route::get('/career', 'PagesController@career');

Route::get('/store-location', 'PagesController@store_location');
Route::get('/store-location/branch/{region_id}', 'PagesController@store_location');
Route::get('/cari', 'PagesController@load_store_location'); // untuk pencarian
Route::post('/filter', 'PagesController@load_store_location_filter'); // untuk pencarian


Route::get('/contact', 'PagesController@contact');

Route::get('/article', 'ArticleController@index');
Route::get('/article/category/{category_slug}', 'ArticleController@category');
Route::get('/article/read/{slug}', 'ArticleController@read');

Route::get('/catalogue', 'CatalogueController@index');
Route::get('/catalogue/find', 'CatalogueController@find');

Route::get('/gallery', 'GalleryController@index');
Route::get('/gallery/view/{slug}', 'GalleryController@view');



Route::get('/artmin/gallery', 'Backend\GalleryController@index');
Route::get('/artmin/gallery/add', 'Backend\GalleryController@add');
Route::post('/artmin/gallery/add', 'Backend\GalleryController@add_process');
Route::get('/artmin/gallery/edit/{id}', 'Backend\GalleryController@edit');
Route::post('/artmin/gallery/edit/{id}', 'Backend\GalleryController@edit_process');
Route::get('/artmin/gallery/delete/{id}', 'Backend\GalleryController@delete');

Route::get('/scan/{code}', 'QRCodeController@scan');


Route::get('/storeregion-json', 'Backend\RegionController@all_json');
Route::get('/storelocation-json', 'Backend\StoreLocationController@all_json');

Route::get('/member/login', 'MemberController@login');
Route::post('/member/login', 'MemberController@login_process');

Route::get('/member/reset-password', 'MemberController@reset_password');
Route::get('/member/reset-password/{reset_token}', 'MemberController@reset_password');
// Route::post('/member/reset-password', 'MemberController@reset_password_process');

Route::get('/member/register', 'MemberController@register');
// Route::post('/member/register', 'MemberController@register_process');

Route::get('/member/dashboard', 'MemberController@dashboard');
Route::get('/member/service', 'MemberController@service');
Route::get('/member/service/history', 'MemberController@service');
Route::get('/member/service/{service_no}', 'MemberController@service_details');
Route::get('/member/service/request/{warranty_id}', 'MemberController@service_request');
Route::post('/member/service/request/{warranty_id}', 'MemberController@service_request_process');
Route::get('/member/point', 'MemberController@point');
Route::get('/member/point/detail', 'MemberController@point');
Route::get('/member/point/detail/{point_addition_id}', 'MemberController@point');

Route::get('/member/tradein/{warranty_id}', 'MemberController@tradein');
Route::post('/member/tradein/{warranty_id}', 'MemberController@tradein_process');
Route::get('/member/tradein-success', 'MemberController@tradein_success');

Route::get('/member/cashback/{warranty_id}', 'MemberController@cashback');
Route::post('/member/cashback/{warranty_id}', 'MemberController@cashback_process');
Route::get('/member/cashback-success', 'MemberController@cashback_success');

Route::get('/member/specialvoucher/{warranty_id}', 'MemberController@specialvoucher');


Route::get('/member/profile', 'MemberController@profile');
Route::post('/member/profile', 'MemberController@update_profile');
Route::get('/member/logout', 'MemberController@logout');
Route::get('/member/warranty/{warranty_id}', 'MemberController@warranty');
Route::post('/member/point/claim/{warranty_id}', 'MemberController@claim_point');
Route::post('/member/testimony', 'MemberController@submit_testimony');

Route::get('/artmin/installation-service-notify', 'Backend\InstallationServiceNotifyController@index');

Route::get('/artmin/login', ['as' => 'login', 'uses' => 'Backend\LoginController@index']);
Route::post('/artmin/login-check', 'Backend\LoginController@login_check');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/artmin/logout', 'Backend\LoginController@logout');

    Route::get('/artmin/fix_slug', 'Backend\DashboardController@fix_slug');

    Route::get('/artmin', 'Backend\DashboardController@index');
    Route::get('/artmin/slide-show', 'Backend\DashboardController@slideshow')->name('artmin.slideshow.list');
    Route::post('/artmin/slide-show', 'Backend\DashboardController@create_update_slideshow');
    Route::post('/artmin/slide-show/show-hide', 'Backend\DashboardController@show_hide_slideshow');
    Route::get('/artmin/dashboard/export', 'Backend\DashboardController@export');
    Route::get('/artmin/dashboard/service-count', 'Backend\DashboardController@get_service_count');

    Route::get('/artmin/service/request', 'Backend\ServiceController@request')->name('artmin.service.list');
    Route::get('/artmin/service/request-details/{service_id}', 'Backend\ServiceController@request_details');
    Route::post('/artmin/service/request-details/{service_id}', 'Backend\ServiceController@request_update');
    Route::post('/artmin/service/request-details/update-city/{service_id}', 'Backend\ServiceController@request_update_city');
    Route::post('/artmin/service/request-details/progress/revisi', 'Backend\ServiceController@request_update_revisi_process');

    Route::get('/artmin/service/request/browse-warranty', 'Backend\ServiceController@browse_warranty');
    Route::get('/artmin/service/request/add-service-request/{warranty_no}', 'Backend\ServiceController@add_service_request');
    Route::post('/artmin/service/request/add-service-request', 'Backend\ServiceController@add_service_request_process');

    Route::get('/artmin/service/request/export-service-request-pdf/{from}/{to}', 'Backend\ServiceController@export_service_request_pdf');
    Route::get('/artmin/service/request/export-service-request-excel/{from}/{to}', 'Backend\ServiceController@export_service_request_excel');

    Route::post('/artmin/service/set-schedule/{service_id}', 'Backend\ServiceController@set_schedule');
    Route::get('/artmin/service/update/processing/{service_id}', 'Backend\ServiceController@update_processing');
    Route::get('/artmin/service/update/complete/{service_id}', 'Backend\ServiceController@update_complete');
    Route::get('/artmin/service/update/uncomplete/{service_id}', 'Backend\ServiceController@update_uncomplete');
    Route::get('/artmin/service/update/cancel/{service_id}', 'Backend\ServiceController@update_cancel');
    // Route::post('/artmin/service/create-so/{service_id}', 'Backend\ServiceController@create_so');

    Route::post('/artmin/service/delete', 'Backend\ServiceController@delete');
    Route::get('/artmin/service/progress-attachment/{progress_id}', 'Backend\ServiceController@get_attachments_by_progress');
    Route::post('/artmin/service/progress-attachment/{progress_id}', 'Backend\ServiceController@upload_progress_attachments');
    Route::delete('/artmin/service/progress-attachment/{attachment_id}', 'Backend\ServiceController@delete_progress_attachments');

    Route::get('/artmin/installation/request', 'Backend\InstallationController@request')->name('artmin.installation.list');
    Route::get('/artmin/installation/request-details/{service_id}', 'Backend\InstallationController@request_details');
    Route::post('/artmin/installation/request-details/{service_id}', 'Backend\InstallationController@request_update');
    Route::post('/artmin/installation/request-details/update-city/{service_id}', 'Backend\InstallationController@request_update_city');
    Route::post('/artmin/installation/set-schedule/{service_id}', 'Backend\InstallationController@set_schedule');
    Route::get('/artmin/installation/update/processing/{service_id}', 'Backend\InstallationController@update_processing');
    Route::get('/artmin/installation/update/complete/{service_id}', 'Backend\InstallationController@update_complete');
    Route::get('/artmin/installation/update/uncomplete/{service_id}', 'Backend\InstallationController@update_uncomplete');
    Route::post('/artmin/installation/request-details/progress/revisi', 'Backend\InstallationController@request_update_revisi_process');
    Route::get('/artmin/installation/update/cancel/{service_id}', 'Backend\InstallationController@update_cancel');

    Route::get('/artmin/installation/request/add-installation-request/{warranty_no}', 'Backend\InstallationController@add_installation_request');
    Route::post('/artmin/installation/request/add-installation-request', 'Backend\InstallationController@add_installation_request_process');
    Route::get('/artmin/installation/request/browse-warranty', 'Backend\InstallationController@browse_warranty');
    Route::get('/artmin/installation/request/export-installation-request-pdf/{from}/{to}', 'Backend\InstallationController@export_installation_request_pdf');
    Route::get('/artmin/installation/request/export-installation-request-excel/{from}/{to}', 'Backend\InstallationController@export_installation_request_excel');

    Route::post('/artmin/installation/delete', 'Backend\InstallationController@delete');
    Route::get('/artmin/installation/progress-attachment/{progress_id}', 'Backend\InstallationController@get_attachments_by_progress');
    Route::post('/artmin/installation/progress-attachment/{progress_id}', 'Backend\InstallationController@upload_progress_attachments');
    Route::delete('/artmin/installation/progress-attachment/{attachment_id}', 'Backend\InstallationController@delete_progress_attachments');

    Route::get('/artmin/quiz', 'Backend\QuizController@listQuiz');
    Route::post('/artmin/quiz/module_quiz/submit', 'Backend\QuizController@save_module_quiz');
    Route::post('/artmin/quiz/module_quiz/status', 'Backend\QuizController@statusQuiz');
    Route::get('/artmin/quiz/question/{quiz_id}', 'Backend\QuizController@listQuestion');
    Route::get('/artmin/quiz/question/add/{quiz_id}', 'Backend\QuizController@insertQuestion');
    Route::post('/artmin/quiz/question/add', 'Backend\QuizController@insertQuestion_save');
    Route::post('/artmin/quiz/question/submit', 'Backend\QuizController@submit_answer');
    Route::get('/artmin/quiz/result/{quiz_id}', 'Backend\QuizController@result');
    Route::get('/artmin/quiz/result_export/{from_date}/{to_date}/{quiz_id}/{status}', 'Backend\QuizController@result_export');

    
    // Route::get('/artmin/quiz', 'Backend\QuizController@listData');
    // Route::get('/artmin/quiz/add-question', 'Backend\QuizController@addQuestion');


    Route::get('/artmin/pricing', 'Backend\PricingController@listData');
    Route::get('/artmin/pricing/add', 'Backend\PricingController@addForm');


    Route::get('/artmin/warranty', 'Backend\WarrantyController@index');
    Route::get('/artmin/warranty-json', 'Backend\WarrantyController@indexJSON');
    Route::get('/artmin/warranty/add-warranty', 'Backend\WarrantyController@add_warranty');
    Route::post('/artmin/warranty/add-warranty-process', 'Backend\WarrantyController@add_warranty_process');
    Route::get('/artmin/warranty/information/{id}', 'Backend\WarrantyController@details');
    Route::get('/artmin/warranty/resend-email/{id}', 'Backend\WarrantyController@resend_warranty_email');
    Route::get('/artmin/warranty/export-warranty-pdf', 'Backend\WarrantyController@export_pdf');
    Route::get('/artmin/warranty/export-warranty-excel/{tgl_from}/{tgl_to}', 'Backend\WarrantyController@export_excel');

    Route::get('/artmin/warranty/information/sync/debug', 'Backend\WarrantyController@sync_debug');

    Route::post('/artmin/warranty/verified', 'Backend\WarrantyController@verified');
    Route::post('/artmin/warranty/revisi', 'Backend\WarrantyController@revision');

    Route::post('/artmin/warranty/assign-promotor', 'Backend\WarrantyController@assign_promotor');
    Route::get('/artmin/warranty/call/{phoneNumber}', 'Backend\WarrantyController@index');

    Route::post('/artmin/warranty/exchange', 'Backend\WarrantyController@exchange');
    Route::post('/artmin/warranty/cancel', 'Backend\WarrantyController@cancel');
    Route::post('/artmin/warranty/set-stocktype', 'Backend\WarrantyController@update_stocktype');

    Route::get('/artmin/technician', 'Backend\TechnicianController@index');
    Route::get('/artmin/technician-json', 'Backend\TechnicianController@indexJSON');
    Route::get('/artmin/technician/add-technician', 'Backend\TechnicianController@add_technician');
    Route::post('/artmin/technician/add-technician-process', 'Backend\TechnicianController@add_technician_process');
    Route::get('/artmin/technician/edit-technician/{technician_id}', 'Backend\TechnicianController@edit_technician');
    Route::post('/artmin/technician/delete-technician', 'Backend\TechnicianController@delete_technician');
    Route::post('/artmin/technician/change-status/{id}', 'Backend\TechnicianController@changeStatus');
    Route::post('/artmin/technician/edit-technician-process', 'Backend\TechnicianController@edit_technician_process');


    Route::get('/artmin/csreport/customer_products', 'Backend\CS_Report\CustomerController@customer_products');
    Route::get('/artmin/csreport/customer_products/{member_id}', 'Backend\CS_Report\CustomerController@customer_products_detail');
    Route::get('/artmin/csreport/export-customer-products/{from}/{to}', 'Backend\CS_Report\CustomerController@customer_products_export');

    Route::get('/artmin/product/list', 'Backend\ProductController@index');
    Route::get('/artmin/product/add-product', 'Backend\ProductController@add_product');
    Route::get('/artmin/product/edit-product/{product_id}', 'Backend\ProductController@edit_product');
    Route::post('/artmin/product/add-product-process', 'Backend\ProductController@add_product_process');
    Route::post('/artmin/product/edit-product-process', 'Backend\ProductController@edit_product_process');
    Route::post('/artmin/product/delete-product-process', 'Backend\ProductController@delete_product_process');
    Route::post('/artmin/product/status-product-process', 'Backend\ProductController@status_product_process');
    Route::post('/artmin/product/display-product-process', 'Backend\ProductController@display_product_process');
    Route::post('/artmin/product/delete-product-image', 'Backend\ProductController@delete_product_image_process');
    Route::get('/artmin/product/files/{product_id}', 'Backend\ProductController@files');
    Route::delete('/artmin/product/files/{file_id}', 'Backend\ProductController@deleteFile');
    Route::post('/artmin/product/files/{product_id}', 'Backend\ProductController@postProductFile');

    Route::get('/artmin/product/export', 'Backend\ProductController@exportData');

    Route::get('/artmin/product/ordering', 'Backend\ProductController@ordering');

    // Route::get('/artmin/product/import_product', 'Backend\ProductController@import_product');

    // GET DATA PRODUCT FROM ODOO
    Route::get('/artmin/product/odoo/sync', 'Backend\ProductController@odoosync');
    // END GET DATA PRODUCT FORM ODOO

    Route::get('/artmin/product/serialnumber', 'Backend\SerialNumberController@index');
    Route::get('/artmin/product/serialnumber/add-serialnumber', 'Backend\SerialNumberController@add_serialnumber');
    Route::post('/artmin/product/serialnumber/add-serialnumber-process', 'Backend\SerialNumberController@add_serialnumber_process');
    // Route::get('/artmin/product/warranty', 'Backend\ProductController@warranty');
    Route::post('/artmin/product/serialnumber/reactivate', 'Backend\SerialNumberController@reactivate');
    
    Route::get('/artmin/product/categories', 'Backend\CategoryController@listCategory');
    Route::get('/artmin/product/categories-json', 'Backend\CategoryController@indexJSON');
    Route::get('/artmin/product/categories/feature/{id}', 'Backend\CategoryController@listCategoryFeatures');
    Route::post('/artmin/product/categories/configuration', 'Backend\CategoryController@saveConfiguration');
    Route::post('/artmin/product/categories/brochure', 'Backend\CategoryController@saveBrochure');
    Route::post('/artmin/product/categories/change-status/{category_id}', 'Backend\CategoryController@changeStatus');
    Route::post('/artmin/product/categories/change-installation/{category_id}', 'Backend\CategoryController@changeInstallation');
    Route::post('/artmin/product/categories/feature', 'Backend\CategoryController@saveCategoryFeatures');

    Route::get('/artmin/product/categories/banner/{id}', 'Backend\CategoryController@listCategoryBanner');
    Route::post('/artmin/product/categories/banner', 'Backend\CategoryController@saveCategoryBanners');

    Route::get('/artmin/promotion/tradein', 'Backend\Promotion\TradeInController@list');
    Route::get('/artmin/promotion/tradein/settings', 'Backend\Promotion\TradeInController@settings');
    Route::get('/artmin/promotion/tradein/settings/add-period', 'Backend\Promotion\TradeInController@add_period');
    Route::post('/artmin/promotion/tradein/settings/add-period-process', 'Backend\Promotion\TradeInController@add_period_process');
    Route::get('/artmin/promotion/tradein/get_data_verification/{trade_id}', 'Backend\Promotion\TradeInController@get_data_verification');
    Route::post('/artmin/promotion/tradein/verified', 'Backend\Promotion\TradeInController@verified');
    Route::post('/artmin/promotion/tradein/transfer', 'Backend\Promotion\TradeInController@verified_transfer');

    Route::get('/artmin/promotion/cashback/resend/{id}', 'Backend\Promotion\CashbackController@resend');

    Route::get('/artmin/promotion/cashback/debug', 'Backend\Promotion\CashbackController@debug');

    Route::get('/artmin/promotion/cashback', 'Backend\Promotion\CashbackController@list');
    Route::get('/artmin/promotion/cashback/settings', 'Backend\Promotion\CashbackController@settings');
    Route::get('/artmin/promotion/cashback/settings/detail/{period_id}', 'Backend\Promotion\CashbackController@settings_detail');
    Route::get('/artmin/promotion/cashback/settings/add-period', 'Backend\Promotion\CashbackController@add_period');
    Route::post('/artmin/promotion/cashback/settings/add-period-process', 'Backend\Promotion\CashbackController@add_period_process');
    Route::get('/artmin/promotion/cashback/get_data_verification/{trade_id}', 'Backend\Promotion\CashbackController@get_data_verification');
    Route::post('/artmin/promotion/cashback/verified', 'Backend\Promotion\CashbackController@verified');
    Route::post('/artmin/promotion/cashback/transfer', 'Backend\Promotion\CashbackController@verified_transfer');

    // Route::get('/artmin/promotion/cashback/settings/debug_product', 'Backend\Promotion\CashbackController@debug_product');

    Route::post('/artmin/promotion/cashback/revisi', 'Backend\Promotion\CashbackController@cashback_revisi');

    Route::get('/artmin/promotion/specialvoucher', 'Backend\Promotion\SpecialVoucherController@list');
    Route::get('/artmin/promotion/specialvoucher/check_special_voucher', 'Backend\WarrantyController@check_special_voucher');

    Route::get('/artmin/promotion/specialvoucher/settings', 'Backend\Promotion\SpecialVoucherController@settings');
    Route::get('/artmin/promotion/specialvoucher/settings/add', 'Backend\Promotion\SpecialVoucherController@add_unique_number');
    Route::get('/artmin/promotion/specialvoucher/settings/edit/{id}', 'Backend\Promotion\SpecialVoucherController@edit_unique_number');
    Route::post('/artmin/promotion/specialvoucher/settings/save', 'Backend\Promotion\SpecialVoucherController@saveData');
    Route::post('/artmin/promotion/specialvoucher/settings/update', 'Backend\Promotion\SpecialVoucherController@updateData');
    Route::post('/artmin/promotion/specialvoucher/transfer_cashback', 'Backend\Promotion\SpecialVoucherController@transfer_cashback');

    Route::get('/artmin/promotion/luckydraw', 'Backend\Promotion\LuckyDrawController@settings');

    Route::get('/artmin/article', 'Backend\ArticleController@index');
    Route::get('/artmin/article/new-post', 'Backend\ArticleController@add_post');
    Route::post('/artmin/article/new-post', 'Backend\ArticleController@add_post_process');
    Route::get('/artmin/article/edit/{id}', 'Backend\ArticleController@edit_post');
    Route::post('/artmin/article/edit/{id}', 'Backend\ArticleController@edit_post_process');
    Route::get('/artmin/article/delete/{id}', 'Backend\ArticleController@delete');
    
    Route::get('/artmin/product-knowledge', 'Backend\ArticleController@index');
    Route::get('/artmin/product-knowledge/read/{slug}', 'Backend\ArticleController@read');
    Route::get('/artmin/product-knowledge/new-post', 'Backend\ArticleController@add_post');
    Route::post('/artmin/product-knowledge/new-post', 'Backend\ArticleController@add_post_process');
    Route::get('/artmin/product-knowledge/edit/{id}', 'Backend\ArticleController@edit_post');
    Route::post('/artmin/product-knowledge/edit/{id}', 'Backend\ArticleController@edit_post_process');
    Route::get('/artmin/product-knowledge/delete/{id}', 'Backend\ArticleController@delete');


    Route::get('/artmin/user', 'Backend\UserController@index')->name('artmin.user.list');
    Route::get('/artmin/user/add-user', 'Backend\UserController@add_user');
    Route::get('/artmin/user/edit-user/{userID}', 'Backend\UserController@edit_user');
    Route::post('/artmin/user/add-user-process', 'Backend\UserController@add_user_process');
    Route::post('/artmin/user/add-user-sc-process', 'Backend\UserController@add_user_sc_process');
    Route::post('/artmin/user/del-user-sc-process/{sc_user_id}', 'Backend\UserController@del_user_sc_process');
    Route::post('/artmin/user/add-user-store-process', 'Backend\UserController@add_user_store_process');
    Route::post('/artmin/user/del-user-store-process/{store_user_id}', 'Backend\UserController@del_user_store_process');
    Route::post('/artmin/user/edit-user-process', 'Backend\UserController@edit_user_process');
    Route::post('/artmin/user/reset-password', 'Backend\UserController@reset_password_admin_process');
    Route::post('/artmin/user/delete-user', 'Backend\UserController@delete_user');
    Route::post('/artmin/user/change-status/{id}', 'Backend\UserController@changeStatus');

    Route::post('/artmin/member/point/adjustment', 'Backend\MemberController@point_adjustment');
    Route::post('/artmin/member/point/approve/{point_id}', 'Backend\MemberController@point_approve');
    Route::post('/artmin/member/point/reject/{point_id}', 'Backend\MemberController@point_reject');
    Route::get('/artmin/member/point/list', 'Backend\MemberController@points')->name('artmin.memberpoint.list');
    Route::get('/artmin/member/point/export/{from}/{to}', 'Backend\MemberController@export');
    Route::get('/artmin/member/point/{member_id}', 'Backend\MemberController@detail_points');
    Route::get('/artmin/member/point/warranty/{warranty_id}', 'Backend\MemberController@detail_warranty_points');

    Route::get('/artmin/member', 'Backend\MemberController@index')->name('artmin.member.list');
    Route::get('/artmin/member-json', 'Backend\MemberController@membersJSON');
    Route::get('/artmin/member/{member_id}', 'Backend\MemberController@edit_account');
    Route::post('/artmin/member/save_edit', 'Backend\MemberController@edit_account_process');
    Route::post('/artmin/member/reset-password', 'Backend\MemberController@reset_password_process');
    Route::post('/artmin/member/marge', 'Backend\MemberController@marge_account');
    Route::post('/artmin/member/change-status/{member_id}', 'Backend\MemberController@changeStatus');

    Route::get('/artmin/reset-password', 'Backend\UserController@reset_password');
    Route::post('/artmin/reset-password-process', 'Backend\UserController@reset_password_process');

    Route::get('/artmin/registerproductcustomer', 'Backend\SPGController@register_product_customer');
    Route::get('/artmin/registerproductcustomer/listproduct/{member_id}', 'Backend\SPGController@list_product_customer');
    Route::get('/artmin/registerproductcustomer/add-product-customer', 'Backend\SPGController@add_product_customer');
    Route::get('/artmin/customer/{member_id}', 'Backend\SPGController@get_data_customer');
    Route::get('/artmin/registerproductcustomer/check_sn/{product_id}/{sn}', 'Backend\SPGController@check_serial_number');
    Route::post('/artmin/product/registerproductcustomer/add-registerproductcustomer-process', 'Backend\SPGController@add_product_customer_process');

    Route::get('/artmin/storesales/dashboard', 'Backend\StoreSalesController@dashboard');
    Route::get('/artmin/storesales', 'Backend\StoreSalesController@listSales')->name('artmin.storesales.list');
    Route::get('/artmin/storesales/report-sales', 'Backend\StoreSalesController@reportSales');
    Route::get('/artmin/storesales/products/{sales_id}', 'Backend\StoreSalesController@detail_products');
    Route::get('/artmin/storesales/edit/{sales_id}', 'Backend\StoreSalesController@edit_sales');
    Route::post('/artmin/storesales/report-sales-process', 'Backend\StoreSalesController@report_sales_process');
    Route::post('/artmin/storesales/edit-sales-process', 'Backend\StoreSalesController@edit_sales_process');
    Route::post('/artmin/storesales/delete', 'Backend\StoreSalesController@delete_sales_process');
    Route::post('/artmin/storesales/approve', 'Backend\StoreSalesController@approve_sales_process');
    Route::post('/artmin/storesales/approve-confirm', 'Backend\StoreSalesController@approve_sales_confirm');


    Route::get('/artmin/storeregion-json', 'Backend\RegionController@all_json');
    Route::get('/artmin/storelocation', 'Backend\StoreLocationController@list_region');
    Route::get('/artmin/storelocation/{regional_id}', 'Backend\StoreLocationController@list_store_location');
    Route::get('/artmin/storelocation/add/{regional_id}', 'Backend\StoreLocationController@add_store_location');
    Route::get('/artmin/storelocation/edit/{regional_id}/{store_id}', 'Backend\StoreLocationController@edit_store_location');
    Route::get('/artmin/storelocation/assignpromotor/{regional_id}/{store_id}', 'Backend\StoreLocationController@assign_promotor');
    Route::get('/artmin/storelocation-json', 'Backend\StoreLocationController@all_json');

    Route::get('/artmin/storelocation/region/add', 'Backend\StoreSalesController@add_form_region');
    Route::get('/artmin/storelocation/region/edit/{region_id}', 'Backend\StoreSalesController@edit_form_region');
    Route::get('/artmin/storelocation/region/export-excel/{from}/{to}', 'Backend\StoreSalesController@export_data');
    Route::post('/artmin/storelocation/region/process-add', 'Backend\StoreSalesController@process_add');
    Route::post('/artmin/storelocation/region/process-edit', 'Backend\StoreSalesController@process_edit');

    Route::get('/artmin/storelocation/region/export-excel/debug', 'Backend\StoreSalesController@debug');
    Route::get('/artmin/storelocation/salesreport/{regional_id}/{store_id}', 'Backend\StoreLocationController@sales_report');

    Route::post('/artmin/storelocation/assign_promotor_process', 'Backend\StoreLocationController@assign_promotor_process');
    Route::post('/artmin/storelocation/remove_promotor_process', 'Backend\StoreLocationController@remove_promotor_process');
    Route::post('/artmin/storelocation/process-add', 'Backend\StoreLocationController@add_store_location_process');
    Route::post('/artmin/storelocation/process-edit', 'Backend\StoreLocationController@edit_store_location_process');

    Route::get('/artmin/servicecenter', 'Backend\ServiceCenterController@list_region');
    Route::get('/artmin/servicecenter-json', 'Backend\ServiceCenterController@indexJSON');
    Route::get('/artmin/servicecenter/{region_id}', 'Backend\ServiceCenterController@list_service_center');
    Route::get('/artmin/servicecenter/add/{region_id}', 'Backend\ServiceCenterController@add_service_center');
    Route::get('/artmin/servicecenter/edit/{region_id}/{sc_id}', 'Backend\ServiceCenterController@edit_service_center');
    Route::post('/artmin/servicecenter/process-add', 'Backend\ServiceCenterController@add_service_center_process');
    Route::post('/artmin/servicecenter/process-edit', 'Backend\ServiceCenterController@edit_service_center_process');
    Route::delete('/artmin/servicecenter/process-delete/{region_id}/{sc_id}', 'Backend\ServiceCenterController@delete_service_center_process');

    Route::get('/artmin/region/add', 'Backend\RegionController@add_form_region');
    Route::get('/artmin/region/edit/{region_id}', 'Backend\RegionController@edit_form_region');
    Route::post('/artmin/region/process-add', 'Backend\RegionController@process_add');
    Route::post('/artmin/region/process-edit', 'Backend\RegionController@process_edit');


    // problem initial untuk memilih category yang ada problem nya
    Route::get('/artmin/problem-initial', 'Backend\Problems\ProblemInitialController@index');
    Route::get('/artmin/problem-initial/add-problem-initial', 'Backend\Problems\ProblemInitialController@add_problem_initial');
    Route::post('/artmin/problem-initial/add-problem-initial-process', 'Backend\Problems\ProblemInitialController@add_problem_initial_process');
    Route::get('/artmin/problem-initial/edit-problem-initial/{problem_initial_id}', 'Backend\Problems\ProblemInitialController@edit_problem_initial');
    Route::post('/artmin/problem-initial/delete-problem-initial', 'Backend\Problems\ProblemInitialController@delete_problem_initial');
    Route::post('/artmin/problem-initial/edit-problem-initial-process', 'Backend\Problems\ProblemInitialController@edit_problem_initial_process');
    Route::post('/artmin/problem-initial/status', 'Backend\Problems\ProblemInitialController@status_problem_initial_process');

    // problem symtom untuk membuat symtom berdasarkan id problem initial nya
    Route::get('/artmin/problem-symptom', 'Backend\Problems\ProblemSymptomController@index');
    Route::get('/artmin/problem-symptom/add-problem-symptom', 'Backend\Problems\ProblemSymptomController@add_problem_symptom');
    Route::post('/artmin/problem-symptom/add-problem-symptom-process', 'Backend\Problems\ProblemSymptomController@add_problem_symptom_process');
    Route::get('/artmin/problem-symptom/edit-problem-symptom/{problem_symptom_id}', 'Backend\Problems\ProblemSymptomController@edit_problem_symptom');
    Route::post('/artmin/problem-symptom/delete-problem-symptom', 'Backend\Problems\ProblemSymptomController@delete_problem_symptom');
    Route::post('/artmin/problem-symptom/edit-problem-symptom-process', 'Backend\Problems\ProblemSymptomController@edit_problem_symptom_process');


    // problem defect untuk membuat defect berdasarkan id problem initial nya
    Route::get('/artmin/problem-defect', 'Backend\Problems\ProblemDefectController@index');
    Route::get('/artmin/problem-defect/add-problem-defect', 'Backend\Problems\ProblemDefectController@add_problem_defect');
    Route::post('/artmin/problem-defect/add-problem-defect-process', 'Backend\Problems\ProblemDefectController@add_problem_defect_process');
    Route::get('/artmin/problem-defect/edit-problem-defect/{problem_defect_id}', 'Backend\Problems\ProblemDefectController@edit_problem_defect');
    Route::post('/artmin/problem-defect/delete-problem-defect', 'Backend\Problems\ProblemDefectController@delete_problem_defect');
    Route::post('/artmin/problem-defect/edit-problem-defect-process', 'Backend\Problems\ProblemDefectController@edit_problem_defect_process');


    // problem taken untuk membuat taken berdasarkan id problem initial nya
    Route::get('/artmin/problem-taken', 'Backend\Problems\ProblemTakenController@index');
    Route::get('/artmin/problem-taken/add-problem-taken', 'Backend\Problems\ProblemTakenController@add_problem_taken');
    Route::post('/artmin/problem-taken/add-problem-taken-process', 'Backend\Problems\ProblemTakenController@add_problem_taken_process');
    Route::get('/artmin/problem-taken/edit-problem-taken/{problem_taken_id}', 'Backend\Problems\ProblemTakenController@edit_problem_taken');
    Route::post('/artmin/problem-taken/delete-problem-taken', 'Backend\Problems\ProblemTakenController@delete_problem_taken');
    Route::post('/artmin/problem-taken/edit-problem-taken-process', 'Backend\Problems\ProblemTakenController@edit_problem_taken_process');



    Route::get('/artmin/problem-action', 'Backend\Problems\ProblemActionController@index');
    Route::get('/artmin/problem-action/add-problem-action', 'Backend\Problems\ProblemActionController@add_problem_action');
    Route::post('/artmin/problem-action/add-problem-action-process', 'Backend\Problems\ProblemActionController@add_problem_action_process');
    Route::get('/artmin/problem-action/edit-problem-action/{problem_action_id}', 'Backend\Problems\ProblemActionController@edit_problem_action');
    Route::post('/artmin/problem-action/delete-problem-action', 'Backend\Problems\ProblemActionController@delete_problem_action');
    Route::post('/artmin/problem-action/edit-problem-action-process', 'Backend\Problems\ProblemActionController@edit_problem_action_process');


    Route::get('/artmin/settings/footer', 'Backend\SettingsController@footer');
    Route::post('/artmin/settings/footer/add', 'Backend\SettingsController@footerAdd');
    Route::post('/artmin/settings/footer/update', 'Backend\SettingsController@footerUpdate');
    Route::post('/artmin/settings/footer/status', 'Backend\SettingsController@footerStatus');
    Route::post('/artmin/settings/footer/delete', 'Backend\SettingsController@footerDelete');

    Route::get('/artmin/contacts', 'Backend\ContactsController@index')->name('artmin.contacts');
    Route::post('/artmin/contacts', 'Backend\ContactsController@detailPost');
    Route::post('/artmin/contacts-delete', 'Backend\ContactsController@detailDelete');
    Route::get('/artmin/contacts/{id}', 'Backend\ContactsController@detail');

    Route::get('/artmin/whatsapp/dashboard', 'Backend\WhatsappController@dashboard');
    Route::get('/artmin/whatsapp/message_count/{period}', 'Backend\WhatsappController@message_count');
    Route::get('/artmin/whatsapp/connect-wa', 'Backend\WhatsappController@connectWhatsapp');
    Route::get('/artmin/whatsapp/wa-msg-template', 'Backend\WhatsappController@waMsgTemplate');
    Route::get('/artmin/whatsapp/wa-msg-template-json', 'Backend\WhatsappController@waMsgTemplateJson');
    Route::post('/artmin/whatsapp/wa-msg-template/save_edit', 'Backend\WhatsappController@waMsgTemplateSaveEdit');
    Route::get('/artmin/whatsapp/wa-msg-template/{id}', 'Backend\WhatsappController@waMsgTemplateForm');
    Route::get('/artmin/whatsapp/wa-msg-blast', 'Backend\WhatsappController@waMsgBlast')->name('artmin.whatsapp.wamsgblast');
    Route::get('/artmin/whatsapp/wa-msg-blast/{id}', 'Backend\WhatsappController@waMsgBlastDetail');
    Route::post('/artmin/whatsapp/wa-msg-blast', 'Backend\WhatsappController@waMsgBlastPost');
    Route::post('/artmin/whatsapp/wa-msg-blast-delete', 'Backend\WhatsappController@waMsgBlastDelete');
    Route::get('/artmin/whatsapp/wa-msg-blast-recipients/{queue_id}', 'Backend\WhatsappController@waMsgBlasRecipients')->name('artmin.whatsapp.wamsgblast.recipients');
    Route::post('/artmin/whatsapp/wa-msg-blast-recipient', 'Backend\WhatsappController@waMsgBlasRecipientsPost');
    Route::get('artmin/whatsapp/wa-setting', 'Backend\WhatsappController@waSetting');
    Route::post('artmin/whatsapp/wa-setting', 'Backend\WhatsappController@waSettingPost');

    Route::get('/artmin/faq/answer', 'Backend\FaqController@answerPageList')->name('artmin.faq-answer.list');
    Route::get('/artmin/faq/answer-json', 'Backend\FaqController@answerJSON');
    Route::post('/artmin/faq/answer', 'Backend\FaqController@saveAnswer');
    Route::post('/artmin/faq/delete-answer/{id}', 'Backend\FaqController@deleteAnswer');

    Route::get('/artmin/faq/question-answer', 'Backend\FaqController@questionAnswerPageList')->name('artmin.faq-question-answer.list');
    Route::post('/artmin/faq/question-answer', 'Backend\FaqController@saveQuestionAnswer');
    Route::post('/artmin/faq/delete-question-answer/{id}', 'Backend\FaqController@deleteQuestionAnswer');

    Route::get('/artmin/faq/faq-answer-json', 'Backend\FaqController@faqAnswerJSON');
    Route::post('/artmin/faq/delete-faq-answer/{id}', 'Backend\FaqController@deleteFaqAnswer');

    // TEST API ODOO
    // Route::get('/artmin/odoo/test', 'Backend\OdooController@test');

});

// qr code features
Route::get('/features', 'FeatureController@index');
Route::get('/features/category/{category_slug}', 'FeatureController@category');
Route::get('/features/read/{slug}', 'FeatureController@read');

Route::group(['middleware' => 'auth_api'], function () {
    Route::get('/storesales/reports', 'API\StoreController@sales_report');
    Route::get('/arn-completed', 'Backend\ServiceController@services_completed_for_so');
    Route::post('/arn-link-so', 'Backend\ServiceController@submit_link_so_with_arn');
});


// curl -X GET 'http://local.artugo.co.id/arn-completed?token=99a38c63-455b-4bb8-ac4c-4e8077feb4f3'
// curl -X POST 'http://local.artugo.co.id/arn-link-so?token=99a38c63-455b-4bb8-ac4c-4e8077feb4f3' -H "Accept: application/json" -H "Content-type: application/json" -d '{"so_id":1111,"so_number":"SO20240001","so_date":"2024-10-20","arn_number":"ARN2023050002","point_deduction":125000,"detail_items":[{"item":"Item 1","amount":25000},{"item":"Item 2","amount":100000}]}'
// curl -X GET 'http://local.artugo.co.id/storesales/reports?token=99a38c63-455b-4bb8-ac4c-4e8077feb4f3&date_from=2024-09-01&date_to=2024-09-30&operating_unit='