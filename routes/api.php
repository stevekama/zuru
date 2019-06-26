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

    Route::post('sag_transa_interface','MpesaController@process');

    /*
     * Pre-auth routes
     */
    Route::group(['namespace'=>'Auth'],function (){
        Route::post('login', 'LoginController@login');
        Route::post('register', 'RegisterController@register');
        Route::post('reset_code', 'RegisterController@resetCode');
        Route::post('reset_password', 'RegisterController@resetPassword');

    });

    /*
     * avatar exposes in the system
     */
    Route::group(['namespace'=>'Commerce'],function(){
        Route::get('product_avatar/{filename}', 'ProductsController@getProductImage');
        Route::get('vendor_avatar/{filename}', 'ShopsController@getVendorImage');
        Route::get('user_avatar/{filename}', 'ShopsController@getUserImage');
        Route::get('rider_mode_avatar/{filename}', 'ShopsController@getRiderModeAvatar');
        Route::get('category_avatar/{filename}', 'ShopsController@getCategoryAvatar');


        Route::get('search', 'ShopsController@getSearch');


    });

    /*
     * Authenticated routes
     */
    Route::group(['middleware'=>'auth:api'],function(){

        Route::group(['namespace'=>'Access'],function(){
            Route::get('user', 'UsersController@user');
            Route::post('user', 'UsersController@updateUser');
            Route::post('user_avatar', 'UsersController@updateUserAvatar');
        });

        Route::group(['namespace'=>'Commerce'],function(){

            #Create or retrieve vendor
            Route::get('vendor/{vendor}', 'ShopsController@getVendors');
            Route::get('self_vendor', 'ShopsController@getSelfVendors');
            Route::post('vendor', 'ShopsController@createVendor');

            #create or retrieve rider
            Route::get('self_rider', 'RiderController@getSelfRider');
            Route::post('rider', 'RiderController@storeRider');
            Route::get('rider_modes', 'RiderController@riderModes');
            Route::get('user_rider', 'RiderController@getUserRider');

            #Statistics routes
            Route::get('stats', 'StatsController@getGeneralStats');
            Route::get('earnings', 'StatsController@earnings');
            Route::get('selling_stats', 'StatsController@sellingStats');

            #customer management routes
            Route::get('self_customer', 'CustomerController@getSelfCustomer');



            #Endpoint to get all shops
            Route::get('shops', 'ShopsController@getShops');
            Route::get('shops/{category}', 'ShopsController@getCategoryShops');

            #Return all categories
            Route::get('vendor_categories', 'ShopsController@getVendorCategories');

            #products create or update
            Route::get('product/{product}', 'ProductsController@fetch');
            Route::get('rate/{product}/{rating}', 'ProductsController@rateProduct');
            Route::get('shop_products/{vendor}', 'ProductsController@shopProducts');
            Route::get('out_of_stock/{vendor}', 'ProductsController@outOfStock');
            Route::post('product', 'ProductsController@store');
            Route::get('product_qty/{product}/{qty}', 'ProductsController@updateQty');
            Route::post('product_availability', 'ProductsController@availability');
            Route::post('delete_product', 'ProductsController@deleteProduct');

            #create order endpoint
            Route::post('order', 'OrdersController@store');
            Route::group(['prefix'=>'orders'],function(){
                Route::get('/customer', 'OrdersController@customer');
                Route::post('/calculate_price', 'OrdersController@calculatePrice');
                Route::get('/rider', 'OrdersController@rider');
                Route::get('/pending', 'OrdersController@pending');
                Route::get('/vendor', 'OrdersController@vendor');
                Route::post('/accept/{order}', 'OrdersController@accept');
                Route::post('/rate/{order}', 'OrdersController@rate');
                Route::post('/accept_customer/{order}', 'OrdersController@acceptCustomer');
            });


            Route::get('account', 'AccountController@getUserAccount');
            Route::get('account', 'AccountController@getUserAccount');


            #Filtered products endpoints
            Route::get('zuru', 'ProductsController@getZuru');
            Route::get('highest_rated', 'ProductsController@getHighestRated');
            Route::get('highest_purchase', 'ProductsController@getHighestPurchase');



        });



        #Wallet control routes
        Route::group(['namespace'=>'Wallet'],function (){
            Route::post('/top_up', 'TransactionsController@topUp');
            Route::post('/withdraw', 'TransactionsController@withdraw');
        });


    });
});