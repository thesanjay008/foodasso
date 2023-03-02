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

//Localization route
Route::get('lang/{locale}', 'HomeController@lang')->name('locale');

// SUPPORT
Route::get('clear', 'SupportController@clear_cache');
Route::get('caches', 'SupportController@caches');
Route::get('migrate', 'SupportController@migration');
Route::get('seed', 'SupportController@seeding');

// COMMON
Route::post('country/list','CountryController@store')->name('post.countryList');
Route::post('state/list','StateController@list')->name('post.stateList');
Route::post('city/list','CityController@list')->name('post.cityList');

// For Website
require_once 'layouts/theme.php';

// For Backend
require_once 'layouts/backend.php';

// For Developer
require_once 'layouts/developer.php';