<?php
/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can use Backend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// BACKEND
Route::group(['middleware' => ['auth'],'prefix' => 'backend','namespace' => 'Backend'], function() {
	Route::get('/','HomeController@index')->name('homePage');
	Route::get('/dashboard','HomeController@dashboard')->name('dashboard');

	// Users (Roles Wise Management)
	Route::get('manage/{role}', 'UserManagementController@index')->name('user.management');
	Route::get('manage/{role}/add', 'UserManagementController@create')->name('user.management.add');
	Route::get('manage/{role}/{id}', 'UserManagementController@show')->name('user.management.edit');
	Route::post('manage/store', 'UserManagementController@store')->name('user.management.store');
	Route::post('manage-user/ajax', 'UserManagementController@ajax_list')->name('ajax.user.management.list');
	Route::post('manage-user/status', 'UserManagementController@change_status')->name('ajax.user.change.status');
	Route::post('manage-user/delete','UserManagementController@destroy')->name('ajax.user.management.delete');
	Route::post('viewsubScription','UserManagementController@viewsubScription')->name('ajax.user.management.viewsubscription');
	Route::post('/deliveryboy_vendor_list','UserManagementController@deliveryboy_vendor_list')->name('deliveryboy.vendortype.assignlist');
	Route::get('/refreel-history','UserManagementController@refreelHistory')->name('page.refreel-history');
	Route::post('/refreel-history-list','UserManagementController@ajax_refreelHistory')->name('ajax.refreel-history');
	Route::post('/update-wallet','UserManagementController@updateWallet')->name('ajax.user.update.wallet');
	
	// ORDERS
	Route::resource('orders', 'OrderController');
	Route::get('/create-order','OrderController@create')->name('create.new.order');
	Route::get('/open-orders','OrderController@open')->name('open.order.list');
	Route::post('/orders/ajax','OrderController@ajax')->name('orderList');
	Route::post('/orders/status','OrderController@status')->name('orders_status');
	Route::post('/orderCount', 'OrderController@ajax_count')->name('ajax_orderCount');
	Route::post('/checkNewOrders', 'OrderController@ajax_checkNewOrders')->name('ajax_checkNewOrders');
	
	
	// Variation Groups 
	Route::resource('variation_groups', 'VariationGroupController');
	Route::post('/variation_groups/ajax','VariationGroupController@ajax')->name('variation.groupList');
	Route::post('/delete_variation_group','VariationGroupController@destroy')->name('delete.variationGroup');
	
	// Variations 
	Route::resource('variations', 'VariationController');
	Route::post('/variation/ajax','VariationController@ajax')->name('variations_list');
	Route::post('/delete_variation','VariationController@destroy')->name('delete_variation');
	
	// Addon Groups 
	Route::resource('addon_groups', 'AddonGroupController');
	Route::post('/addon_groups/ajax','AddonGroupController@ajax')->name('addon_groups_list');
	Route::post('/delete_addon_group','AddonGroupController@destroy')->name('delete_addon_group');

	// Addon 
	Route::resource('addons', 'AddonController');
	Route::post('/addons/ajax','AddonController@ajax')->name('addons_list');
	Route::post('/delete_addon','AddonController@destroy')->name('delete_addon');
	
	// Menu Category 
	Route::resource('menu_categories', 'MenuCategoryController');
	Route::post('/menu_category/ajax','MenuCategoryController@ajax')->name('menu_catergories_list');
	Route::post('/delete_menu_categories','MenuCategoryController@destroy')->name('delete_menu_categories');
	
	// PRODUCT
	Route::resource('products', 'ProductController');
	Route::post('/products/ajax','ProductController@ajax')->name('productList');
	Route::post('/saveProduct','ProductController@store')->name('saveProduct');
	Route::post('/deleteProduct','ProductController@destroy')->name('deleteProduct');
	
	// Offer
	Route::resource('offers', 'OfferController');
	Route::post('/offer/ajax','OfferController@ajax')->name('offer_list');
	Route::post('/delete_offer','OfferController@destroy')->name('delete_offer');
	
	// Coupon 
	Route::resource('coupons', 'CouponController');
	Route::post('/coupon/ajax','CouponController@ajax')->name('coupons_list');
	Route::post('/delete_coupons','CouponController@destroy')->name('delete_coupons');
	
	// Outlets 
	Route::resource('outlets', 'OutletController');
	Route::post('/outlets/ajax','OutletController@ajax')->name('ajax_outlet_list');
	Route::post('/saveOutlet','OutletController@store')->name('ajax_saveOutlet');
	Route::post('/updateOutlet','OutletController@update')->name('ajax_updateOutlet');
	Route::post('/outlet/status','OutletController@change_status')->name('ajax_change_outletStatus');
	
	// Table
	Route::resource('table', 'TableController');
	Route::post('/table/ajax','TableController@ajax')->name('ajax_table_list');
	Route::post('/saveTable','TableController@store')->name('ajax_saveTable');
	Route::post('/updateTable','TableController@update')->name('ajax_updateTable');
	Route::post('/table/save-qr','TableController@saveQR')->name('ajax.table.save.qr');
	Route::post('/table/status','TableController@change_status')->name('ajax_change_tableStatus');
	
	// Table Booking
	Route::resource('bookings', 'BookingController');
	Route::post('/bookings/ajax','BookingController@ajax')->name('ajax.booking.list');
	Route::post('/bookings/status','BookingController@change_status')->name('ajax_change_BookingStatus');
	
	// Coupon 
	Route::resource('customers', 'CustomerController');
	Route::post('/customers/ajax','CustomerController@ajax')->name('ajax.customer.list');
	Route::post('/change-customer-status','CustomerController@change_status')->name('ajax.change.customer.status');
	
	// LOCATIONS
	Route::resource('countries', 'Locations\CountryController');
	Route::post('/country-list','Locations\CountryController@ajax_list')->name('ajax.country.list');
	Route::post('/saveCountry','Locations\CountryController@store')->name('ajax.save.country');
	Route::post('/change-country-status','Locations\CountryController@change_status')->name('ajax.change.country.status');
	Route::post('/delete-country','Locations\CountryController@destroy')->name('ajax.delete.country');
	
	Route::resource('states', 'Locations\StateController');
	Route::post('/state-list','Locations\StateController@ajax_list')->name('ajax.state.list');
	Route::post('/save-state','Locations\StateController@store')->name('ajax.state.city');
	Route::post('/change-state-status','Locations\StateController@change_status')->name('ajax.change.state.status');
	Route::post('/delete-state','Locations\StateController@destroy')->name('ajax.delete.state');
	
	Route::resource('cities', 'Locations\CityController');
	Route::post('/city-list','Locations\CityController@ajax_list')->name('ajax.city.list');
	Route::post('/saveCity','Locations\CityController@store')->name('ajax.save.city');
	Route::post('/change-city-status','Locations\CityController@change_status')->name('ajax.change.city.status');
	Route::post('/delete-city','Locations\CityController@destroy')->name('ajax.delete.city');
	
	Route::resource('areas', 'Locations\AreaController');
	Route::post('/area-list','Locations\AreaController@ajax_list')->name('ajax.area.list');
	Route::post('/saveArea','Locations\AreaController@store')->name('ajax.save.area');
	Route::post('/change-area-status','Locations\AreaController@change_status')->name('ajax.change.area.status');
	Route::post('/delete-area','Locations\AreaController@destroy')->name('ajax.delete.area');
	
	// SETTINGS
	Route::get('/my-profile','ProfileController@index')->name('my-profile');
	Route::post('save-profile','ProfileController@saveProfile')->name('ajax.profile.update');
	Route::post('update-profile-image','SettingController@profile_logo')->name('ajax.update.profile.image');
	Route::get('/change-password','ProfileController@change_password')->name('change_password');
	Route::post('/change-password','ProfileController@ajax_change_password')->name('changePassword');
	
	Route::get('/general-settings','SettingController@general_settings')->name('general-settings');
	Route::post('/general-setting/store','SettingController@store')->name('ajax.store.general-settings');
	Route::post('/general-setting/store-logo','SettingController@store_logo')->name('ajax.store.logo');
	Route::post('/general-setting/store-qr','SettingController@store_qr')->name('ajax.store.qr-code');
	
	Route::get('/paymentGateways','PaymentGatewayController@index')->name('paymentGateways');
	Route::post('/paymentGateway/list','PaymentGatewayController@ajax')->name('ajax.paymentGateway.list');
	Route::post('/paymentGateway/edit','PaymentGatewayController@ajax')->name('paymentGateway.edit');
	Route::post('/paymentGateway/status','PaymentGatewayController@change_status')->name('change.paymentGateway.status');
});