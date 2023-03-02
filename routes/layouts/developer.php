<?php
/*
|--------------------------------------------------------------------------
| Developer Routes
|--------------------------------------------------------------------------
|
| Here is where you can use Developer routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// DEVELOPER
Route::group(['middleware' => ['auth'],'prefix' => 'developer','namespace' => 'Developer'], function() {
    Route::get('/','HomeController@index')->name('developerDashboard');
	Route::resource('roles','RoleController');
	Route::resource('permissions','PermissionController')->except(['show','edit','update']);
});