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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('to_admin');

Route::group(['namespace'=>'Backend','prefix'=>'administration','as'=>'backend.','middleware'=>['auth','admin']],function (){
   Route::get('/','HomeController@index')->name('home');

   #Group all users routes
   Route::group([],function (){
       Route::get('users/{login_mode}','UsersController@list')->name('users');
       Route::get('administrators','UsersController@admins')->name('users.admins');
       Route::get('vendor_information/{user}','UsersController@vendorInformation')->name('users.vendor_information');
   });

   #Group all vendor categories routes
    Route::group(['as'=>'vendor_categories.'],function (){
        Route::get('list','VendorCategoriesController@list')->name('list');
        Route::get('create','VendorCategoriesController@create')->name('create');
        Route::post('store','VendorCategoriesController@store')->name('store');
        Route::get('edit/{category}','VendorCategoriesController@edit')->name('edit');
    });

    #Products management routes
    Route::group(['as'=>'products.','prefix'=>'products'],function(){
        Route::get('list','ProductsController@list')->name('list');
        Route::get('create','ProductsController@create')->name('create');
        Route::post('store','ProductsController@store')->name('store');
        Route::get('edit/{category}','ProductsController@edit')->name('edit');
    });

    Route::group(['as'=>'roles.','prefix'=>'roles','middleware'=>'permission:read-roles'],function (){
        Route::get('list','AccessController@list')->name('list');
        Route::get('create','AccessController@creteRole')->name('create');
        Route::get('edit/{role}','AccessController@editRole')->name('edit');
        Route::get('permissions/{role}','AccessController@assignForm')->name('permissions');
        Route::post('store','AccessController@storeRole')->name('store');
        Route::post('store_assign','AccessController@storeAssign')->name('store_assign');

    });

});