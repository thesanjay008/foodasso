<?php
/*
|--------------------------------------------------------------------------
| Theme Routes
|--------------------------------------------------------------------------
|
| Here is where you can use theme routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// LOGIN / REGISTRATION
Auth::routes();
Route::post('/loginUser', 'Auth\LoginController@loginUser')->name('loginUser');
Route::post('/registerUser', 'Auth\RegisterController@register')->name('registerUser');


// First Page
Route::get('/', 'HomeController@index')->name('firstPage');


// CMS
Route::get('/about-us', 'CmsController@aboutUs')->name('about-us');
Route::get('/qr-code', 'CmsController@qrCode')->name('qr-code');
Route::get('/contact-us', 'CmsController@contactUs')->name('contact-us');
Route::get('/terms', 'CmsController@terms')->name('terms');
Route::get('/privacy', 'CmsController@privacy')->name('privacy');
Route::get('/refund', 'CmsController@refund')->name('refund');


// SHOP
Route::get('/menu', 'Theme\MenuController@index')->name('menuPage');
Route::post('/cartList', 'Theme\CartController@index')->name('cartList');
Route::get('/checkout', 'Theme\CheckoutController@index')->name('checkoutPage');
Route::get('/confirm-order', 'Theme\OrderController@confirm')->name('confirmOrder');
Route::get('/verifyPayment/{id}', 'theme\OrderController@verifyPayment')->name('verifyPayment');
Route::get('/verify-payment/{id}', 'theme\OrderController@verifyTablePayment')->name('verifyTablePayment');


// Dine-in (Table Orders)
Route::get('table/{id}', 'Theme\MenuController@tableMenu')->name('table.menu');
Route::post('/create-table-order', 'Theme\OrderController@createTableOrder')->name('createTableOrder');
Route::get('table/{id}/checkout', 'Theme\CheckoutController@table')->name('table.checkout');
//Route::get('table/checkout', 'Theme\CheckoutController@table')->name('table.checkout');
Route::get('/table/order-success/{id?}', 'Theme\OrderController@table_orderSuccess')->name('table.order_successPage');
Route::get('/table/order-failed/{id?}', 'Theme\OrderController@table_orderFailed')->name('table.order_failedPage');


// Table Booking
Route::get('/booking', 'Theme\BookingController@index')->name('bookingPage');
Route::get('/booking/checkout', 'Theme\BookingController@checkout')->name('bookingCheckoutPage');
Route::post('/create-booking', 'Theme\BookingController@create')->name('createBooking');
Route::post('/booking-checkout-list', 'Theme\BookingController@ajax_checkoutList')->name('ajax_bookingCheckoutList');
Route::post('/confirm-booking', 'Theme\BookingController@confirm')->name('ajax.confirmBooking');
Route::get('/booking-success', 'Theme\BookingController@messageSuccess')->name('booking_successPage');
Route::get('/booking-failed', 'Theme\BookingController@messageFailed')->name('booking_faileddPage');


// ORDER
Route::post('/checkNewOrder', 'HomeController@ajax_checkNewOrder')->name('checkNewOrder');
Route::post('/addtoCart', 'Theme\CartController@ajax_add')->name('addtoCart');
Route::post('/delete_cart', 'Theme\CartController@deleteCart')->name('deleteCart');
Route::post('/create-order', 'Theme\OrderController@create')->name('createOrder');
Route::post('/send-order-otp', 'Theme\OrderController@send_otp')->name('send.order.otp');
Route::get('/order-success', 'Theme\OrderController@orderSuccess')->name('order_successPage');
Route::get('/order-failed', 'Theme\OrderController@orderFailed')->name('order_failedPage');


// MY Account
Route::post('/loginPortal', 'Auth\LoginController@loginUser')->name('loginPortal');
Route::get('/my-account', 'AccountController@index')->name('myAccount');
Route::get('/my-account/orders', 'AccountController@myOrders')->name('myOrders');
Route::get('/my-account/wish-list', 'AccountController@wishList')->name('myWishList');
Route::get('/profile-settings', 'AccountController@settings')->name('profileSettings');
Route::post('/saveAddress', 'AccountController@ajax_saveAddress')->name('saveAddress');
Route::post('/updateProfile', 'AccountController@updateProfile')->name('updateProfile');
Route::post('/ajax_myOrders', 'AccountController@ajax_myOrders')->name('ajax_myOrders');