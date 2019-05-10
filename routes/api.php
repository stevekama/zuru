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

            #Create or retrieve vendor
            Route::get('vendor/{vendor}', 'ShopsController@getVendors');
            Route::get('self_vendor', 'ShopsController@getSelfVendors');
            Route::post('vendor', 'ShopsController@createVendor');

            #create or retrieve rider

            #create or update customer

            #Endpoint to get all shops
            Route::get('shops', 'ShopsController@getShops');
            Route::get('shops/{category}', 'ShopsController@getCategoryShops');

            #Return all categories
            Route::get('vendor_categories', 'ShopsController@getVendorCategories');

            #products create or update
            Route::get('product/{product}', 'ProductsController@fetch');
            Route::get('shop_products/{vendor}', 'ProductsController@shopProducts');
            Route::post('product', 'ProductsController@store');
            Route::post('product/availability', 'ProductsController@availability');

        });

    });
});