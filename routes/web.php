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

});