<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace'=>'Api'],function () {

    /*
     * Pre-auth routes
     */
    Route::group(['namespace'=>'Auth'],function (){
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
    });

    /*
     * Authenticated routes
     */
    Route::group(['middleware'=>'auth:api'],function(){

        Route::group(['namespace'=>'Access'],function(){
            Route::get('user', 'UsersController@user');
        });

        Route::group(['namespace'=>'Commerce'],function(){
            Route::get('vendor', 'ShopsController@getVendors');
            Route::post('vendor', 'ShopsController@createVendor');
        });

    });
});